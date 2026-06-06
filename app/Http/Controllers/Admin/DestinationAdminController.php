<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DestinationType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BulkDestinationRequest;
use App\Http\Requests\Admin\ImportDestinationRequest;
use App\Http\Requests\Admin\StoreDestinationRequest;
use App\Http\Requests\Admin\UpdateDestinationRequest;
use App\Models\TravelDestination;
use App\Services\DestinationImportService;
use App\Services\DestinationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DestinationAdminController extends Controller
{
    public function __construct(
        protected DestinationService $destinationService,
        protected DestinationImportService $importService,
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', TravelDestination::class);

        $items = TravelDestination::query()
            ->searchTerm($request->input('q'))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->input('type')))
            ->when($request->input('status') === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->input('status') === 'inactive', fn ($q) => $q->where('is_active', false))
            ->ordered()
            ->paginate(20)
            ->withQueryString();

        return view('admin.destinations.index', [
            'items' => $items,
            'search' => $request->input('q'),
            'filterType' => $request->input('type'),
            'filterStatus' => $request->input('status'),
            'typeOptions' => DestinationType::options(),
        ]);
    }

    public function trashed(Request $request): View
    {
        $this->authorize('viewAny', TravelDestination::class);

        $items = TravelDestination::onlyTrashed()
            ->searchTerm($request->input('q'))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->input('type')))
            ->latest('deleted_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.destinations.trashed', [
            'items' => $items,
            'search' => $request->input('q'),
            'filterType' => $request->input('type'),
            'typeOptions' => DestinationType::options(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', TravelDestination::class);

        return view('admin.destinations.form', [
            'destination' => new TravelDestination([
                'is_active' => true,
                'sort_order' => (int) TravelDestination::query()->max('sort_order') + 1,
            ]),
            'typeOptions' => DestinationType::options(),
            'countryOptions' => $this->destinationService->countryOptions(),
        ]);
    }

    public function store(StoreDestinationRequest $request): RedirectResponse
    {
        $this->authorize('create', TravelDestination::class);

        $data = $this->destinationService->prepareForStorage(
            $request->validated(),
            $request->boolean('is_active', true),
        );

        $destination = TravelDestination::query()->create($data);
        $this->destinationService->logMutation('destination.created', $destination);

        return redirect()->route('admin.destinations.index')->with('success', 'Destination created.');
    }

    public function edit(TravelDestination $destination): View
    {
        $this->authorize('update', $destination);

        return view('admin.destinations.form', [
            'destination' => $destination,
            'typeOptions' => DestinationType::options(),
            'countryOptions' => $this->destinationService->countryOptions(),
        ]);
    }

    public function update(UpdateDestinationRequest $request, TravelDestination $destination): RedirectResponse
    {
        $this->authorize('update', $destination);

        $data = $this->destinationService->prepareForStorage(
            $request->validated(),
            $request->boolean('is_active', true),
        );

        $destination->update($data);
        $this->destinationService->logMutation('destination.updated', $destination);

        return redirect()->route('admin.destinations.index')->with('success', 'Destination updated.');
    }

    public function destroy(TravelDestination $destination): RedirectResponse
    {
        $this->authorize('delete', $destination);

        $destination->delete();
        $this->destinationService->logMutation('destination.deleted', $destination);

        return redirect()->route('admin.destinations.index')->with('success', 'Destination moved to trash.');
    }

    public function restore(int $id): RedirectResponse
    {
        $destination = TravelDestination::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $destination);

        $destination->restore();
        $this->destinationService->logMutation('destination.restored', $destination);

        return redirect()->route('admin.destinations.trashed')->with('success', 'Destination restored.');
    }

    public function toggleStatus(TravelDestination $destination): RedirectResponse
    {
        $this->authorize('update', $destination);

        $destination->update(['is_active' => ! $destination->is_active]);
        $this->destinationService->logMutation('destination.toggled', $destination, [
            'is_active' => $destination->is_active,
        ]);

        return back()->with('success', 'Destination status updated.');
    }

    public function bulk(BulkDestinationRequest $request): RedirectResponse
    {
        $this->authorize('viewAny', TravelDestination::class);

        $count = $this->destinationService->applyBulkAction(
            $request->validated('action'),
            $request->validated('ids'),
        );

        return back()->with('success', "Bulk action applied to {$count} destination(s).");
    }

    public function importForm(): View
    {
        $this->authorize('import', TravelDestination::class);

        return view('admin.destinations.import', [
            'datasets' => array_keys(config('destinations.import_mappings', [])),
        ]);
    }

    public function import(ImportDestinationRequest $request): RedirectResponse
    {
        $this->authorize('import', TravelDestination::class);

        $stats = $this->importService->importFromCsv(
            $request->file('file'),
            $request->validated('dataset'),
        );

        $message = "Import complete: {$stats['created']} created, {$stats['updated']} updated, {$stats['skipped']} skipped.";

        if ($stats['errors'] !== []) {
            $message .= ' '.count($stats['errors']).' row error(s).';
        }

        return redirect()
            ->route('admin.destinations.index')
            ->with('success', $message)
            ->with('import_errors', $stats['errors']);
    }
}
