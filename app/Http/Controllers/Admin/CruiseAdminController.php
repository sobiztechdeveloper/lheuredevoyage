<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesCatalog;
use App\Http\Controllers\Controller;
use App\Models\Cruise;
use App\Services\CatalogMasterDataService;
use App\Services\CruiseProductAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CruiseAdminController extends Controller
{
    use ManagesCatalog {
        index as catalogIndex;
        destroy as catalogDestroy;
    }

    public function __construct(
        protected CruiseProductAdminService $productService,
        protected CatalogMasterDataService $masterData,
    ) {}

    protected function catalogModel(): string
    {
        return Cruise::class;
    }

    protected function catalogLabel(): string
    {
        return 'Cruise';
    }

    protected function catalogMasterDataKey(): ?string
    {
        return 'cruise';
    }

    protected function resourceKey(): string
    {
        return 'cruises';
    }

    protected function catalogUploadDirectory(): string
    {
        return 'cruises';
    }

    protected function catalogFields(): array
    {
        return [];
    }

    protected function catalogRules(): array
    {
        return [];
    }

    public function index(Request $request): View
    {
        return $this->catalogIndex($request);
    }

    public function create(): View
    {
        $this->authorizeCatalog('create');

        return view('admin.cruises.form', $this->formViewData(new Cruise));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeCatalog('create');
        $this->productService->save($request);

        return redirect()->route('admin.cruises.index')->with('success', 'Cruise created.');
    }

    public function edit(string $key): View
    {
        $item = $this->resolveCatalogItem($key);
        $this->authorizeCatalog('update', $item);
        $item->load(['itineraryDays', 'cabins', 'galleryImages', 'categories', 'facilities']);

        return view('admin.cruises.form', $this->formViewData($item));
    }

    public function update(Request $request, string $key): RedirectResponse
    {
        $item = $this->resolveCatalogItem($key);
        $this->authorizeCatalog('update', $item);
        $this->productService->save($request, $item);

        return redirect()->route('admin.cruises.index')->with('success', 'Cruise updated.');
    }

    public function destroy(string $key): RedirectResponse
    {
        return $this->catalogDestroy($key);
    }

    /**
     * @return array<string, mixed>
     */
    protected function formViewData(Cruise $item): array
    {
        return [
            'item' => $item,
            'regions' => config('cruise.regions', []),
            'shipClasses' => config('cruise.ship_classes', []),
            'cabinTypes' => config('cruise.cabin_types', []),
            'includedOptions' => config('cruise.included_services', []),
            'notIncludedOptions' => config('cruise.not_included_services', []),
            'masterDataOptions' => $this->masterData->optionsForCatalog('cruise', $item),
            'masterDataSelected' => $item->exists ? $this->masterData->selectedIdsForCatalog('cruise', $item) : [],
        ];
    }
}
