<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAirlineRequest;
use App\Http\Requests\Admin\UpdateAirlineRequest;
use App\Models\Airline;
use App\Services\AirlineService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AirlineAdminController extends Controller
{
    public function __construct(
        protected AirlineService $airlineService,
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Airline::class);

        $items = Airline::query()
            ->searchTerm($request->input('q'))
            ->when($request->input('status') === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->input('status') === 'inactive', fn ($q) => $q->where('is_active', false))
            ->ordered()
            ->paginate(20)
            ->withQueryString();

        return view('admin.airlines.index', [
            'items' => $items,
            'search' => $request->input('q'),
            'filterStatus' => $request->input('status'),
        ]);
    }

    public function trashed(Request $request): View
    {
        $this->authorize('viewAny', Airline::class);

        $items = Airline::onlyTrashed()
            ->searchTerm($request->input('q'))
            ->latest('deleted_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.airlines.trashed', [
            'items' => $items,
            'search' => $request->input('q'),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Airline::class);

        return view('admin.airlines.form', [
            'airline' => new Airline([
                'is_active' => true,
                'sort_order' => (int) Airline::query()->max('sort_order') + 1,
            ]),
        ]);
    }

    public function store(StoreAirlineRequest $request): RedirectResponse
    {
        $this->authorize('create', Airline::class);

        $data = $this->airlineService->prepareForStorage(
            $request->safe()->except('logo'),
            $request->boolean('is_active', true),
        );

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('airlines', 'public');
        }

        $airline = Airline::query()->create($data);
        $this->airlineService->logMutation('airline.created', $airline);

        return redirect()->route('admin.airlines.index')->with('success', 'Airline created.');
    }

    public function edit(Airline $airline): View
    {
        $this->authorize('update', $airline);

        return view('admin.airlines.form', ['airline' => $airline]);
    }

    public function update(UpdateAirlineRequest $request, Airline $airline): RedirectResponse
    {
        $this->authorize('update', $airline);

        $data = $this->airlineService->prepareForStorage(
            $request->safe()->except(['logo', 'remove_logo']),
            $request->boolean('is_active', true),
        );

        if ($request->boolean('remove_logo') && $airline->logo) {
            Storage::disk('public')->delete($airline->logo);
            $data['logo'] = null;
        }

        if ($request->hasFile('logo')) {
            if ($airline->logo) {
                Storage::disk('public')->delete($airline->logo);
            }
            $data['logo'] = $request->file('logo')->store('airlines', 'public');
        }

        $airline->update($data);
        $this->airlineService->logMutation('airline.updated', $airline);

        return redirect()->route('admin.airlines.index')->with('success', 'Airline updated.');
    }

    public function destroy(Airline $airline): RedirectResponse
    {
        $this->authorize('delete', $airline);

        $airline->delete();
        $this->airlineService->logMutation('airline.deleted', $airline);

        return redirect()->route('admin.airlines.index')->with('success', 'Airline moved to trash.');
    }

    public function restore(int $id): RedirectResponse
    {
        $airline = Airline::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $airline);

        $airline->restore();
        $this->airlineService->logMutation('airline.restored', $airline);

        return redirect()->route('admin.airlines.trashed')->with('success', 'Airline restored.');
    }

    public function toggleStatus(Airline $airline): RedirectResponse
    {
        $this->authorize('update', $airline);

        $airline->update(['is_active' => ! $airline->is_active]);
        $this->airlineService->logMutation('airline.toggled', $airline, [
            'is_active' => $airline->is_active,
        ]);

        return back()->with('success', 'Airline status updated.');
    }
}
