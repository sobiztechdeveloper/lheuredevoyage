<?php

namespace App\Http\Controllers;

use App\Http\Requests\HotelSearchRequest;
use App\Models\HotelSearch;
use App\Services\HotelSearchResultsService;
use App\Services\HotelSearchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class HotelSearchController extends Controller
{
    public function __construct(
        protected HotelSearchService $hotelSearchService,
        protected HotelSearchResultsService $hotelSearchResultsService,
    ) {}

    public function store(HotelSearchRequest $request): RedirectResponse
    {
        $search = $this->hotelSearchService->createSearch($request->validated());

        session(['last_hotel_search_id' => $search->id]);

        return redirect()->route('hotel.search', array_merge(
            ['hotel_search' => $search->id],
            $this->preserveFilterQuery($request),
        ));
    }

    public function update(HotelSearch $hotelSearch, HotelSearchRequest $request): RedirectResponse
    {
        $search = $this->hotelSearchService->updateSearch($hotelSearch, $request->validated());

        session(['last_hotel_search_id' => $search->id]);

        return redirect()->route('hotel.search', array_merge(
            ['hotel_search' => $search->id],
            $this->preserveFilterQuery($request),
        ));
    }

    public function hotelsPage(Request $request): View|RedirectResponse
    {
        if ($legacy = $this->legacySearchCriteria($request)) {
            $validator = Validator::make($legacy, (new HotelSearchRequest)->rules());

            if ($validator->passes()) {
                $search = $this->hotelSearchService->createSearch($validator->validated());
                session(['last_hotel_search_id' => $search->id]);

                return redirect()->route('hotel.search', array_merge(
                    ['hotel_search' => $search->id],
                    $this->preserveFilterQuery($request),
                ));
            }
        }

        $search = $this->resolveDisplaySearch($request);
        session(['last_hotel_search_id' => $search->id]);

        return $this->buildResultsView($search, $request, 'hotel.search');
    }

    public function results(HotelSearch $hotelSearch, Request $request): View
    {
        session(['last_hotel_search_id' => $hotelSearch->id]);

        return $this->buildResultsView($hotelSearch, $request, 'hotel.search.results');
    }

    protected function buildResultsView(HotelSearch $hotelSearch, Request $request, string $filterRoute): View
    {
        $hotelSearch = $this->hotelSearchService->refreshSearchResultsIfStale($hotelSearch);
        $hotelSearch->load([
            'results.catalogHotel.facilities',
            'results.catalogHotel.sports',
            'results.catalogHotel.wellnesses',
            'results.catalogHotel.beachTypes',
            'results.catalogHotel.roomTypes',
            'results.catalogHotel.roomFacilities',
            'results.catalogHotel.mealPlans',
        ]);

        $allResults = $hotelSearch->results;
        $totalCount = $allResults->count();
        $facets = $this->hotelSearchResultsService->computeFacets($allResults);
        $results = $this->hotelSearchResultsService->filterAndSort($allResults, $request);
        $activeFilters = $this->hotelSearchResultsService->activeFilters($request);

        $priceMin = $request->has('price_min')
            ? (int) $request->input('price_min')
            : (int) $facets['price_min'];
        $priceMax = $request->has('price_max')
            ? (int) $request->input('price_max')
            : (int) $facets['price_max'];

        $filterAction = $filterRoute === 'hotel.search.results'
            ? route('hotel.search.results', $hotelSearch)
            : route('hotel.search');

        return view('pages.publicView.hotel.hotelList', [
            'search' => $hotelSearch,
            'results' => $results,
            'resultsCount' => $results->count(),
            'totalResultsCount' => $totalCount,
            'facets' => $facets,
            'activeFilters' => $activeFilters,
            'filterGroups' => app(\App\Services\CatalogMasterDataService::class)->filterGroupsForCatalog('hotel'),
            'sort' => $activeFilters['sort'],
            'priceMin' => $priceMin,
            'priceMax' => $priceMax,
            'filterAction' => $filterAction,
        ]);
    }

    protected function resolveDisplaySearch(Request $request): HotelSearch
    {
        if ($request->filled('hotel_search')) {
            return HotelSearch::query()->findOrFail($request->integer('hotel_search'));
        }

        if ($id = session('last_hotel_search_id')) {
            $search = HotelSearch::query()->find($id);
            if ($search) {
                return $search;
            }
        }

        return $this->hotelSearchService->getOrCreateBrowseSearch();
    }

    /**
     * @return array<string, mixed>|null
     */
    private function legacySearchCriteria(Request $request): ?array
    {
        if ($request->filled('hotel_search') || ! $request->filled('destination')) {
            return null;
        }

        $journey = $request->input('journey_date') ?? $request->input('journey-date');
        if (! $journey) {
            return null;
        }

        return [
            'destination' => $request->input('destination'),
            'journey_date' => $journey,
            'return_date' => $request->input('return_date') ?? $request->input('return-date'),
            'adult' => (int) $request->input('adult', 2),
            'children' => (int) $request->input('children', 0),
            'infant' => (int) $request->input('infant', 0),
            'rooms' => (int) ($request->input('rooms') ?? $request->input('room', 1)),
            'room_type' => $request->input('room_type') ?? $request->input('room-type'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function preserveFilterQuery(Request $request): array
    {
        $params = $request->only([
            'facilities',
            'sports',
            'wellnesses',
            'beach_types',
            'room_types',
            'room_facilities',
            'meal_plans',
            'stars',
            'price_min',
            'price_max',
            'sort',
            'hotel_search',
        ]);

        foreach (['facilities', 'sports', 'wellnesses', 'beach_types', 'room_types', 'room_facilities', 'meal_plans', 'stars'] as $key) {
            if ($request->has($key)) {
                $params[$key] = $request->input($key);
            }
        }

        return $params;
    }
}
