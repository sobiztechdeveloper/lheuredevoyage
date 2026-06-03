<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMasterDataRequest;
use App\Http\Requests\Admin\UpdateMasterDataRequest;
use App\Models\Master\MasterDataModel;
use App\Services\MasterDataRegistry;
use App\Services\MasterDataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MasterDataController extends Controller
{
    public function __construct(
        protected MasterDataService $masterDataService,
    ) {}

    public function index(Request $request, string $type): View
    {
        $config = MasterDataRegistry::config($type);
        $modelClass = $config['model'];
        $this->authorize('viewAny', $modelClass);

        $items = $modelClass::query()
            ->search($request->input('q'))
            ->ordered()
            ->paginate(20)
            ->withQueryString();

        return view('admin.master-data.index', [
            'type' => $config['key'],
            'config' => $config,
            'items' => $items,
            'search' => $request->input('q'),
        ]);
    }

    public function trashed(Request $request, string $type): View
    {
        $config = MasterDataRegistry::config($type);
        $modelClass = $config['model'];
        $this->authorize('viewAny', $modelClass);

        $items = $modelClass::onlyTrashed()
            ->search($request->input('q'))
            ->ordered()
            ->paginate(20)
            ->withQueryString();

        return view('admin.master-data.trashed', [
            'type' => $config['key'],
            'config' => $config,
            'items' => $items,
            'search' => $request->input('q'),
        ]);
    }

    public function create(string $type): View
    {
        $config = MasterDataRegistry::config($type);
        $modelClass = $config['model'];
        $this->authorize('create', $modelClass);

        return view('admin.master-data.form', [
            'type' => $config['key'],
            'config' => $config,
            'item' => new $modelClass,
        ]);
    }

    public function store(StoreMasterDataRequest $request, string $type): RedirectResponse
    {
        $config = MasterDataRegistry::config($type);
        $modelClass = $config['model'];
        $this->authorize('create', $modelClass);

        $data = $this->masterDataService->prepareForSave(
            array_merge($request->validated(), ['is_active' => $request->boolean('is_active')]),
        );
        if ($data['sort_order'] === 0) {
            $data['sort_order'] = (int) $modelClass::query()->max('sort_order') + 1;
        }

        $modelClass::query()->create($data);

        return redirect()->route(MasterDataRegistry::routeName($type, 'index'))
            ->with('success', $config['label'].' created.');
    }

    public function edit(string $type, MasterDataModel $masterRecord): View
    {
        $config = MasterDataRegistry::config($type);
        $this->authorize('update', $masterRecord);

        return view('admin.master-data.form', [
            'type' => $config['key'],
            'config' => $config,
            'item' => $masterRecord,
        ]);
    }

    public function update(UpdateMasterDataRequest $request, string $type, MasterDataModel $masterRecord): RedirectResponse
    {
        $config = MasterDataRegistry::config($type);
        $this->authorize('update', $masterRecord);

        $masterRecord->update($this->masterDataService->prepareForSave(
            array_merge($request->validated(), ['is_active' => $request->boolean('is_active')]),
            $masterRecord,
        ));

        return redirect()->route(MasterDataRegistry::routeName($type, 'index'))
            ->with('success', $config['label'].' updated.');
    }

    public function destroy(string $type, MasterDataModel $masterRecord): RedirectResponse
    {
        $config = MasterDataRegistry::config($type);
        $this->authorize('delete', $masterRecord);
        $masterRecord->delete();

        return redirect()->route(MasterDataRegistry::routeName($type, 'index'))
            ->with('success', $config['label'].' deleted.');
    }

    public function restore(string $type, int $id): RedirectResponse
    {
        $config = MasterDataRegistry::config($type);
        $modelClass = $config['model'];
        $item = $modelClass::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $item);
        $item->restore();

        return redirect()->route(MasterDataRegistry::routeName($type, 'trashed'))
            ->with('success', $config['label'].' restored.');
    }

    public function toggleStatus(string $type, MasterDataModel $masterRecord): RedirectResponse
    {
        $this->authorize('update', $masterRecord);
        $masterRecord->update(['is_active' => ! $masterRecord->is_active]);

        return back()->with('success', 'Status updated.');
    }
}
