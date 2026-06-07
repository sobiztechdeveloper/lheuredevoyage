<?php

namespace App\Http\Controllers;

use App\Exceptions\Aerticket\AerticketApiException;
use App\Exceptions\SerpApi\SerpApiException;
use App\Http\Requests\FlightSearchRequest;
use App\Models\FlightSearch;
use App\Services\FlightSearchOrchestrator;
use App\Services\FlightSearchParamsService;
use App\Services\FlightSearchResultsService;
use App\Services\FlightSearchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FlightSearchController extends Controller
{
    public function __construct(
        protected FlightSearchOrchestrator $flightSearchOrchestrator,
        protected FlightSearchService $flightSearchService,
        protected FlightSearchResultsService $flightSearchResultsService,
        protected FlightSearchParamsService $flightSearchParamsService,
    ) {}

    public function store(FlightSearchRequest $request): RedirectResponse
    {
        if ($request->isBrowseOnly()) {
            session()->forget('last_flight_search_id');

            return redirect()->route('flight', $this->redirectQuery($request));
        }

        $search = null;

        try {
            $search = $this->flightSearchOrchestrator->createSearch($request->validated());

            session(['last_flight_search_id' => $search->id]);

            return redirect()->route('flight', $this->redirectQuery($request, $search));
        } catch (AerticketApiException|SerpApiException $e) {
            return $this->redirectFlightSearchFailure($request, $e, $search);
        }
    }

    public function update(FlightSearch $flightSearch, FlightSearchRequest $request): RedirectResponse
    {
        if ($request->isBrowseOnly()) {
            session()->forget('last_flight_search_id');

            return redirect()->route('flight', $this->redirectQuery($request));
        }

        $search = null;

        try {
            $search = $this->flightSearchOrchestrator->createSearch($request->validated());

            session(['last_flight_search_id' => $search->id]);

            return redirect()->route('flight', $this->redirectQuery($request, $search));
        } catch (AerticketApiException|SerpApiException $e) {
            return $this->redirectFlightSearchFailure($request, $e, $search);
        }
    }

    public function flightsPage(Request $request): View
    {
        $search = $this->resolveDisplaySearch($request);
        session(['last_flight_search_id' => $search->id]);

        return $this->buildResultsView($search, $request, 'flight');
    }

    public function results(FlightSearch $flightSearch, Request $request): View
    {
        session(['last_flight_search_id' => $flightSearch->id]);

        return $this->buildResultsView($flightSearch, $request, 'flight.search.results');
    }

    public function showOffer(FlightSearch $flightSearch, string $offerId): View
    {
        $offer = $this->flightSearchOrchestrator->getOfferDetail($flightSearch, $offerId);

        return view('pages.publicView.flight.flightOfferDetail', [
            'search' => $flightSearch,
            'offer' => $offer,
        ]);
    }

    public function fareRules(FlightSearch $flightSearch, string $offerId): View
    {
        $offer = $flightSearch->aerticketOffers()
            ->where('external_offer_id', $offerId)
            ->firstOrFail();

        $fareRules = $this->flightSearchOrchestrator->getFareRules($flightSearch, $offerId);

        return view('pages.publicView.flight.flightFareRules', [
            'search' => $flightSearch,
            'offer' => $offer,
            'fareRules' => $fareRules,
        ]);
    }

    protected function buildResultsView(FlightSearch $flightSearch, Request $request, string $filterRoute): View
    {
        $panelSearch = $this->flightSearchParamsService->panelValues($request, $flightSearch);

        if (($panelSearch['cabin_class'] ?? '') !== '' && $panelSearch['cabin_class'] !== $flightSearch->cabin_class) {
            try {
                $flightSearch = $this->refreshSearchForCabinClass($flightSearch, $panelSearch);
                session(['last_flight_search_id' => $flightSearch->id]);
            } catch (AerticketApiException|SerpApiException) {
                // Keep the previous search if the cabin-class refresh fails.
            }
        }

        $flightSearch = $this->flightSearchService->refreshSearchResultsIfStale($flightSearch);
        $flightSearch->load(['results.catalogFlight', 'aerticketOffers']);

        $allResults = $flightSearch->results;
        $totalCount = $allResults->count();
        $facets = $this->flightSearchResultsService->computeFacets($allResults);
        $filterRequest = $this->requestWithSearchPanelFilters($request, $flightSearch);
        $results = $this->flightSearchResultsService->filterAndSort($allResults, $filterRequest);
        $activeFilters = $this->flightSearchResultsService->activeFilters($filterRequest);

        $priceMin = $request->has('price_min')
            ? (int) $request->input('price_min')
            : (int) $facets['price_min'];
        $priceMax = $request->has('price_max')
            ? (int) $request->input('price_max')
            : (int) $facets['price_max'];

        $filterAction = $filterRoute === 'flight'
            ? route('flight')
            : route('flight.search.results', $flightSearch);

        return view('pages.publicView.flight.flightList', [
            'search' => $flightSearch,
            'panelSearch' => $panelSearch,
            'searchQuery' => $this->flightSearchParamsService->searchQueryForView($request, $flightSearch),
            'results' => $results,
            'resultsCount' => $results->count(),
            'totalResultsCount' => $totalCount,
            'facets' => $facets,
            'activeFilters' => $activeFilters,
            'sort' => $activeFilters['sort'],
            'priceMin' => $priceMin,
            'priceMax' => $priceMax,
            'filterAction' => $filterAction,
            'usesAerticket' => $flightSearch->usesAerticket(),
        ]);
    }

    protected function requestWithSearchPanelFilters(Request $request, FlightSearch $flightSearch): Request
    {
        $classFilters = array_filter((array) $request->input('flight_class', $request->input('flight-class', [])));

        if ($classFilters === [] && $request->filled('cabin_class')) {
            $merged = $request->duplicate();
            $merged->merge(['flight_class' => [$request->input('cabin_class')]]);

            return $merged;
        }

        if ($classFilters !== [] || ! $flightSearch->cabin_class) {
            return $request;
        }

        if (trim((string) $flightSearch->from_destination) === '' && trim((string) $flightSearch->to_destination) === '') {
            return $request;
        }

        $merged = $request->duplicate();
        $merged->merge(['flight_class' => [$flightSearch->cabin_class]]);

        return $merged;
    }

    /**
     * @param  array<string, mixed>  $panel
     */
    protected function refreshSearchForCabinClass(FlightSearch $flightSearch, array $panel): FlightSearch
    {
        $criteria = array_merge($flightSearch->toSearchCriteria(), [
            'cabin_class' => $panel['cabin_class'],
            'from_destination' => $panel['from_destination'] ?: $flightSearch->from_destination,
            'to_destination' => $panel['to_destination'] ?: $flightSearch->to_destination,
            'trip_type' => $panel['trip_type'] ?? $flightSearch->trip_type,
            'adult' => $panel['adult'] ?? $flightSearch->adult,
            'children' => $panel['children'] ?? $flightSearch->children,
            'infant' => $panel['infant'] ?? $flightSearch->infant,
            'journey_date' => normalize_user_date($panel['journey_date'] ?? '') ?: $flightSearch->journey_date?->format('Y-m-d'),
            'return_date' => normalize_user_date($panel['return_date'] ?? '') ?: $flightSearch->return_date?->format('Y-m-d'),
        ]);

        if ($flightSearch->usesSerpapi() || $flightSearch->usesAerticket()) {
            $from = trim((string) $criteria['from_destination']);
            $to = trim((string) $criteria['to_destination']);

            if ($from !== '' && $to !== '') {
                $payload = array_merge($criteria, array_filter([
                    'from_departure_id' => $panel['from_departure_id'] ?? null,
                    'to_arrival_id' => $panel['to_arrival_id'] ?? null,
                ]));

                return $this->flightSearchOrchestrator->createSearch($payload);
            }
        }

        return $this->flightSearchService->updateSearch($flightSearch, $criteria);
    }

    protected function resolveDisplaySearch(Request $request): FlightSearch
    {
        if ($request->filled('flight_search')) {
            return FlightSearch::query()->findOrFail($request->integer('flight_search'));
        }

        return $this->flightSearchService->getOrCreateBrowseSearch();
    }

    protected function redirectFlightSearchFailure(
        FlightSearchRequest $request,
        AerticketApiException|SerpApiException $e,
        ?FlightSearch $search,
    ): RedirectResponse {
        if ($search) {
            session(['last_flight_search_id' => $search->id]);
        }

        return redirect()
            ->route('flight', $this->redirectQuery($request, $search))
            ->withInput()
            ->withErrors(['flight_search' => $e->userMessage()]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function redirectQuery(Request $request, ?FlightSearch $search = null): array
    {
        $panelQuery = $search
            ? $this->flightSearchParamsService->queryParamsFromSearch($search)
            : $this->flightSearchParamsService->queryParamsFromRequest($request, $search?->id);

        return array_merge($this->preserveFilterQuery($request), $panelQuery);
    }

    /**
     * @return array<string, mixed>
     */
    private function preserveFilterQuery(Request $request): array
    {
        return $request->only([
            'flight_class',
            'flight_time',
            'flight_arrival_time',
            'flight_duration',
            'flight_stop',
            'flight_overnight',
            'flight_layover',
            'flight_airline',
            'flight_weight',
            'flight_refundable',
            'price_min',
            'price_max',
            'sort',
            'flight_search',
        ]);
    }
}
