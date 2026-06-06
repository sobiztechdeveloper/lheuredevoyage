<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesCatalog;
use App\Models\Cruise;
use App\Services\CruiseSearchService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CruiseController extends Controller
{
    use HandlesCatalog;

    public function __construct(
        protected CruiseSearchService $cruiseSearch,
    ) {}

    protected function catalogModel(): string
    {
        return Cruise::class;
    }

    protected function catalogListView(): string
    {
        return 'pages.publicView.cruise.cruiseList';
    }

    protected function catalogDetailView(): string
    {
        return 'pages.publicView.cruise.show';
    }

    protected function catalogBookingView(): string
    {
        return 'pages.publicView.cruise.cruiseQuoteWizard';
    }

    protected function catalogRoutePrefix(): string
    {
        return 'cruise';
    }

    protected function catalogMasterDataKey(): ?string
    {
        return 'cruise';
    }

    public function index(Request $request): View
    {
        return $this->cruiseListing($request);
    }

    public function search(Request $request): RedirectResponse
    {
        return redirect()->route('cruise', $request->query());
    }

    protected function cruiseListing(Request $request): View
    {
        $items = $this->catalogQuery($request)->paginate(12)->withQueryString();

        return view($this->catalogListView(), $this->catalogListViewData($request, $items));
    }

    /**
     * @return array<string, mixed>
     */
    protected function catalogListViewData(Request $request, $items): array
    {
        $priceBounds = $this->cruiseSearch->priceBounds();

        return [
            'items' => $items,
            'routePrefix' => $this->catalogRoutePrefix(),
            'filterGroups' => $this->catalogFilterGroups(),
            'cruiseLineOptions' => $this->cruiseSearch->cruiseLineOptions(),
            'regionOptions' => $this->cruiseSearch->regionOptions(),
            'durationOptions' => $this->cruiseSearch->durationOptions(),
            'cabinTypeOptions' => config('cruise.cabin_types', []),
            'priceBounds' => $priceBounds,
            'searchQuery' => $this->cruiseSearchQuery($request),
            'activeDestination' => $request->input('destination', $request->input('q')),
            'activeMinPrice' => $request->input('min_price', $priceBounds['min']),
            'activeMaxPrice' => $request->input('max_price', $priceBounds['max']),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function cruiseSearchQuery(Request $request): array
    {
        return array_filter(
            $this->cruiseSearch->searchParamsFromRequest($request),
            fn ($v) => $v !== null && $v !== '' && $v !== [],
        );
    }

    public function show(string $slug): View
    {
        $item = Cruise::query()
            ->active()
            ->where('slug', $slug)
            ->with(['itineraryDays', 'cabins', 'galleryImages', 'categories', 'facilities'])
            ->firstOrFail();

        $related = Cruise::query()
            ->active()
            ->where('id', '!=', $item->id)
            ->when($item->cruise_region, fn ($q) => $q->where('cruise_region', $item->cruise_region))
            ->ordered()
            ->take(3)
            ->get();

        return view($this->catalogDetailView(), [
            'item' => $item,
            'related' => $related,
            'routePrefix' => $this->catalogRoutePrefix(),
            'bookableType' => $this->catalogRoutePrefix(),
            'catalogLabel' => $this->catalogLabel(),
            'includedOptions' => config('cruise.included_services', []),
            'notIncludedOptions' => config('cruise.not_included_services', []),
        ]);
    }

    public function book(string $slug): RedirectResponse
    {
        $query = array_filter([
            'journey-date' => request('journey-date', request('departure_date')),
            'return-date' => request('return-date', request('return_date')),
            'cruise_line' => request('cruise_line'),
            'adult' => request('adult'),
            'children' => request('children'),
            'infant' => request('infant'),
        ]);
        $url = route('cruise.quote.wizard', ['cruise' => $slug]);

        return redirect()->to($query ? $url.'?'.http_build_query($query) : $url);
    }

    protected function catalogQuery(Request $request): Builder
    {
        $query = Cruise::query()->active()->with(['cabins'])->withCount('cabins');

        $this->cruiseSearch->applyFilters($query, $request);

        app(\App\Services\CatalogMasterDataService::class)->applyMasterFilters($query, $request, 'cruise');

        $this->cruiseSearch->applySort($query, $request);

        return $query;
    }
}
