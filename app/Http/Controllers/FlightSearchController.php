<?php

namespace App\Http\Controllers;

use App\Exceptions\Aerticket\AerticketApiException;
use App\Http\Requests\FlightSearchRequest;
use App\Models\FlightSearch;
use App\Services\FlightSearchOrchestrator;
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
    ) {}

    public function store(FlightSearchRequest $request): RedirectResponse
    {
        if ($request->isBrowseOnly()) {
            session()->forget('last_flight_search_id');

            return redirect()->route('flight', $this->preserveFilterQuery($request));
        }

        try {
            $search = $this->flightSearchOrchestrator->createSearch($request->validated());

            session(['last_flight_search_id' => $search->id]);

            return redirect()->route('flight', array_merge(
                ['flight_search' => $search->id],
                $this->preserveFilterQuery($request),
            ));
        } catch (AerticketApiException $e) {
            return back()
                ->withInput()
                ->withErrors(['flight_search' => $e->userMessage()]);
        }
    }

    public function update(FlightSearch $flightSearch, FlightSearchRequest $request): RedirectResponse
    {
        if ($request->isBrowseOnly()) {
            session()->forget('last_flight_search_id');

            return redirect()->route('flight', $this->preserveFilterQuery($request));
        }

        try {
            if ($flightSearch->usesAerticket()) {
                $search = $this->flightSearchOrchestrator->createSearch($request->validated());
            } else {
                $search = $this->flightSearchService->updateSearch($flightSearch, $request->validated());
            }

            session(['last_flight_search_id' => $search->id]);

            return redirect()->route('flight', array_merge(
                ['flight_search' => $search->id],
                $this->preserveFilterQuery($request),
            ));
        } catch (AerticketApiException $e) {
            return back()
                ->withInput()
                ->withErrors(['flight_search' => $e->userMessage()]);
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

    protected function resolveDisplaySearch(Request $request): FlightSearch
    {
        if ($request->filled('flight_search')) {
            return FlightSearch::query()->findOrFail($request->integer('flight_search'));
        }

        return $this->flightSearchService->getOrCreateBrowseSearch();
    }

    /**
     * @return array<string, mixed>
     */
    private function preserveFilterQuery(Request $request): array
    {
        return $request->only([
            'flight_class',
            'flight_time',
            'flight_stop',
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
