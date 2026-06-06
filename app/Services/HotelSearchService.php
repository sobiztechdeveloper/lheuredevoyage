<?php

namespace App\Services;

use App\Models\HotelSearch;

class HotelSearchService
{
    public function __construct(
        protected HotelCatalogService $hotelCatalogService,
    ) {}

    public function getOrCreateBrowseSearch(): HotelSearch
    {
        $search = HotelSearch::query()
            ->where('provider', 'browse')
            ->latest()
            ->first();

        if ($search) {
            $criteria = $this->hotelCatalogService->defaultBrowseCriteria();

            if ($search->destination !== $criteria['destination']
                || $this->hotelCatalogService->resultsAreStale($search)) {
                $search = $this->updateSearch($search, $criteria);
                $search->update(['provider' => 'browse']);
            }

            return $search->load(['results.catalogHotel']);
        }

        $search = $this->createSearch($this->hotelCatalogService->defaultBrowseCriteria());
        $search->update(['provider' => 'browse']);

        return $search;
    }

    public function createSearch(array $data): HotelSearch
    {
        $search = HotelSearch::query()->create([
            'user_id' => auth()->id(),
            'provider' => 'catalog',
            'status' => 'completed',
            'destination' => $data['destination'],
            'journey_date' => $data['journey_date'],
            'return_date' => $data['return_date'] ?? null,
            'adult' => $data['adult'],
            'children' => $data['children'] ?? 0,
            'infant' => $data['infant'] ?? 0,
            'rooms' => $data['rooms'],
            'room_type' => $data['room_type'] ?? null,
        ]);

        $this->hotelCatalogService->syncSearchResults($search);

        return $search->load(['results.catalogHotel']);
    }

    public function updateSearch(HotelSearch $search, array $data): HotelSearch
    {
        $search->update([
            'destination' => $data['destination'],
            'journey_date' => $data['journey_date'],
            'return_date' => $data['return_date'] ?? null,
            'adult' => $data['adult'],
            'children' => $data['children'] ?? 0,
            'infant' => $data['infant'] ?? 0,
            'rooms' => $data['rooms'],
            'room_type' => $data['room_type'] ?? null,
        ]);

        $this->hotelCatalogService->syncSearchResults($search);

        return $search->load(['results.catalogHotel']);
    }

    public function refreshSearchResultsIfStale(HotelSearch $search): HotelSearch
    {
        if ($this->hotelCatalogService->resultsAreStale($search)) {
            $this->hotelCatalogService->syncSearchResults($search);
        }

        return $search->load(['results.catalogHotel']);
    }
}
