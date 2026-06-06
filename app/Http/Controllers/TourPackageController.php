<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesCatalog;
use App\Models\TourPackage;
use App\Services\CatalogMasterDataService;
use App\Services\TourPackageSearchService;
use App\Support\BookingWizardSummary;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TourPackageController extends Controller
{
    use HandlesCatalog;

    public function __construct(
        protected TourPackageSearchService $tourPackageSearch,
    ) {}

    protected function catalogModel(): string
    {
        return TourPackage::class;
    }

    protected function catalogListView(): string
    {
        return 'pages.publicView.tourPackage.tourPackageList';
    }

    protected function catalogDetailView(): string
    {
        return 'pages.publicView.catalog.detail';
    }

    protected function catalogBookingView(): string
    {
        return 'pages.publicView.tourPackage.tourPackageBookingWizard';
    }

    public function book(string $slug): View
    {
        $item = $this->findCatalogItem($slug);
        $travelers = $this->catalogBookingTravelers(request());

        return view($this->catalogBookingView(), array_merge(
            $this->catalogViewData($item),
            [
                'travelers' => $travelers,
                'summary' => BookingWizardSummary::forTourPackage($item, $travelers),
            ],
        ));
    }

    protected function catalogRoutePrefix(): string
    {
        return 'tourpackage';
    }

    protected function catalogMasterDataKey(): ?string
    {
        return 'tourpackage';
    }

    public function index(Request $request): View
    {
        $items = $this->catalogQuery($request)->paginate(12)->withQueryString();

        return view($this->catalogListView(), $this->catalogListViewData($request, $items));
    }

    /**
     * @return array<string, mixed>
     */
    protected function catalogListViewData(Request $request, $items): array
    {
        $priceBounds = $this->tourPackageSearch->priceBounds();

        return [
            'items' => $items,
            'routePrefix' => $this->catalogRoutePrefix(),
            'filterGroups' => $this->catalogFilterGroups(),
            'countryOptions' => $this->tourPackageSearch->countryOptions(),
            'holidayTypeOptions' => $this->tourPackageSearch->holidayTypeOptions(),
            'durationOptions' => $this->tourPackageSearch->durationOptions(),
            'travelMonthOptions' => $this->tourPackageSearch->travelMonthOptions(),
            'priceBounds' => $priceBounds,
            'searchQuery' => $this->tourPackageSearch->searchParamsFromRequest($request),
            'activeMinPrice' => $request->input('min_price', $priceBounds['min']),
            'activeMaxPrice' => $request->input('max_price', $priceBounds['max']),
        ];
    }

    protected function catalogQuery(Request $request): Builder
    {
        $query = TourPackage::query()->active();

        $this->tourPackageSearch->applyFilters($query, $request);

        app(CatalogMasterDataService::class)->applyMasterFilters($query, $request, 'tourpackage');

        $this->applyCatalogSort($query, $request);

        return $query;
    }

    /**
     * @return array<string, mixed>
     */
    protected function catalogSearchQuery(Request $request): array
    {
        return $this->tourPackageSearch->searchParamsFromRequest($request);
    }
}
