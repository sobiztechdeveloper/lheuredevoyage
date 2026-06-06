<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCruiseLineRequest;
use App\Http\Requests\Admin\UpdateCruiseLineRequest;
use App\Models\CruiseLine;
use App\Services\CruiseLineService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CruiseLineAdminController extends Controller
{
    public function __construct(
        protected CruiseLineService $cruiseLineService,
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', CruiseLine::class);

        $items = CruiseLine::query()
            ->searchTerm($request->input('q'))
            ->when($request->input('status') === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->input('status') === 'inactive', fn ($q) => $q->where('is_active', false))
            ->ordered()
            ->paginate(20)
            ->withQueryString();

        return view('admin.cruise-lines.index', [
            'items' => $items,
            'search' => $request->input('q'),
            'filterStatus' => $request->input('status'),
        ]);
    }

    public function trashed(Request $request): View
    {
        $this->authorize('viewAny', CruiseLine::class);

        $items = CruiseLine::onlyTrashed()
            ->searchTerm($request->input('q'))
            ->latest('deleted_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.cruise-lines.trashed', [
            'items' => $items,
            'search' => $request->input('q'),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', CruiseLine::class);

        return view('admin.cruise-lines.form', [
            'line' => new CruiseLine([
                'is_active' => true,
                'sort_order' => (int) CruiseLine::query()->max('sort_order') + 1,
            ]),
        ]);
    }

    public function store(StoreCruiseLineRequest $request): RedirectResponse
    {
        $this->authorize('create', CruiseLine::class);

        $data = $this->cruiseLineService->prepareForStorage(
            $request->safe()->except('logo'),
            $request->boolean('is_active', true),
        );

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('cruise-lines', 'public');
        }

        $line = CruiseLine::query()->create($data);
        $this->cruiseLineService->logMutation('cruise_line.created', $line);

        return redirect()->route('admin.cruise-lines.index')->with('success', 'Cruise line created.');
    }

    public function edit(CruiseLine $cruise_line): View
    {
        $this->authorize('update', $cruise_line);

        return view('admin.cruise-lines.form', ['line' => $cruise_line]);
    }

    public function update(UpdateCruiseLineRequest $request, CruiseLine $cruise_line): RedirectResponse
    {
        $this->authorize('update', $cruise_line);

        $data = $this->cruiseLineService->prepareForStorage(
            $request->safe()->except(['logo', 'remove_logo']),
            $request->boolean('is_active', true),
        );

        if ($request->boolean('remove_logo') && $cruise_line->logo) {
            Storage::disk('public')->delete($cruise_line->logo);
            $data['logo'] = null;
        }

        if ($request->hasFile('logo')) {
            if ($cruise_line->logo) {
                Storage::disk('public')->delete($cruise_line->logo);
            }
            $data['logo'] = $request->file('logo')->store('cruise-lines', 'public');
        }

        $cruise_line->update($data);
        $this->cruiseLineService->logMutation('cruise_line.updated', $cruise_line);

        return redirect()->route('admin.cruise-lines.index')->with('success', 'Cruise line updated.');
    }

    public function destroy(CruiseLine $cruise_line): RedirectResponse
    {
        $this->authorize('delete', $cruise_line);

        $cruise_line->delete();
        $this->cruiseLineService->logMutation('cruise_line.deleted', $cruise_line);

        return redirect()->route('admin.cruise-lines.index')->with('success', 'Cruise line moved to trash.');
    }

    public function restore(int $id): RedirectResponse
    {
        $line = CruiseLine::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $line);

        $line->restore();
        $this->cruiseLineService->logMutation('cruise_line.restored', $line);

        return redirect()->route('admin.cruise-lines.trashed')->with('success', 'Cruise line restored.');
    }

    public function toggleStatus(CruiseLine $cruise_line): RedirectResponse
    {
        $this->authorize('update', $cruise_line);

        $cruise_line->update(['is_active' => ! $cruise_line->is_active]);
        $this->cruiseLineService->logMutation('cruise_line.toggled', $cruise_line, [
            'is_active' => $cruise_line->is_active,
        ]);

        return back()->with('success', 'Cruise line status updated.');
    }
}
