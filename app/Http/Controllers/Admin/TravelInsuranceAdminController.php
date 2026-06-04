<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesCatalog;
use App\Http\Controllers\Controller;
use App\Models\TravelInsurance;
use App\Services\InsuranceProductAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TravelInsuranceAdminController extends Controller
{
    use ManagesCatalog {
        index as catalogIndex;
        destroy as catalogDestroy;
    }

    public function __construct(
        protected InsuranceProductAdminService $productService,
    ) {}

    protected function catalogModel(): string
    {
        return TravelInsurance::class;
    }

    protected function catalogLabel(): string
    {
        return 'Travel Insurance';
    }

    protected function catalogFields(): array
    {
        return [];
    }

    protected function catalogRules(): array
    {
        return [];
    }

    protected function catalogUploadDirectory(): string
    {
        return 'insurance-products';
    }

    protected function resourceKey(): string
    {
        return 'insurances';
    }

    protected function catalogMasterDataKey(): ?string
    {
        return null;
    }

    public function index(Request $request): View
    {
        return $this->catalogIndex($request);
    }

    public function create(): View
    {
        $this->authorizeCatalog('create');

        return view('admin.insurances.form', [
            'item' => new TravelInsurance,
            'planTypes' => config('insurance.plan_types', []),
            'currencies' => config('insurance.coverage_currencies', []),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeCatalog('create');
        $this->productService->save($request);

        return redirect()->route('admin.insurances.index')
            ->with('success', 'Insurance plan created.');
    }

    public function edit(string $key): View
    {
        $item = $this->resolveCatalogItem($key);
        $this->authorizeCatalog('update', $item);
        $item->load(['benefits', 'exclusions', 'galleryImages']);

        return view('admin.insurances.form', [
            'item' => $item,
            'planTypes' => config('insurance.plan_types', []),
            'currencies' => config('insurance.coverage_currencies', []),
        ]);
    }

    public function update(Request $request, string $key): RedirectResponse
    {
        $item = $this->resolveCatalogItem($key);
        $this->authorizeCatalog('update', $item);
        $this->productService->save($request, $item);

        return redirect()->route('admin.insurances.index')
            ->with('success', 'Insurance plan updated.');
    }

    public function destroy(string $key): RedirectResponse
    {
        return $this->catalogDestroy($key);
    }
}
