<?php

namespace App\Services;

use App\Models\FlightSearch;

class FlightSearchService
{
    public function __construct(
        protected FlightCatalogService $flightCatalogService,
    ) {}

    public function getOrCreateBrowseSearch(): FlightSearch
    {
        $search = FlightSearch::query()
            ->where('provider', 'browse')
            ->latest()
            ->first();

        if ($search) {
            if ($this->flightCatalogService->resultsAreStale($search)) {
                $this->flightCatalogService->syncSearchResults($search);
            }

            return $search->load(['results.catalogFlight']);
        }

        $search = $this->createSearch($this->flightCatalogService->defaultBrowseCriteria());
        $search->update(['provider' => 'browse']);

        return $search;
    }

    public function createSearch(array $data): FlightSearch
    {
        $search = FlightSearch::query()->create([
            'user_id' => auth()->id(),
            'provider' => 'mock',
            'status' => 'completed',
            'trip_type' => $data['trip_type'],
            'from_destination' => $data['from_destination'],
            'to_destination' => $data['to_destination'],
            'journey_date' => $data['journey_date'],
            'return_date' => $data['return_date'] ?? null,
            'adult' => $data['adult'],
            'children' => $data['children'] ?? 0,
            'infant' => $data['infant'] ?? 0,
            'cabin_class' => $data['cabin_class'],
        ]);

        $this->flightCatalogService->syncSearchResults($search);

        return $search->load(['results.catalogFlight']);
    }

    public function updateSearch(FlightSearch $search, array $data): FlightSearch
    {
        $search->update([
            'trip_type' => $data['trip_type'],
            'from_destination' => $data['from_destination'],
            'to_destination' => $data['to_destination'],
            'journey_date' => $data['journey_date'],
            'return_date' => $data['return_date'] ?? null,
            'adult' => $data['adult'],
            'children' => $data['children'] ?? 0,
            'infant' => $data['infant'] ?? 0,
            'cabin_class' => $data['cabin_class'],
        ]);

        $this->flightCatalogService->syncSearchResults($search);

        return $search->load(['results.catalogFlight']);
    }

    public function refreshSearchResultsIfStale(FlightSearch $search): FlightSearch
    {
        if ($search->usesAerticket()) {
            return $search->load(['results.catalogFlight']);
        }

        if ($this->flightCatalogService->resultsAreStale($search)) {
            $this->flightCatalogService->syncSearchResults($search);
        }

        return $search->load(['results.catalogFlight']);
    }
}
