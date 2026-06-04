<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\HotelSearch;
use App\Models\HotelSearchResult;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class HotelCatalogService
{
    /**
     * @return Collection<int, Hotel>
     */
    public function hotelsForSearch(HotelSearch $search): Collection
    {
        $query = Hotel::query()->active()->orderBy('price');

        $destination = trim((string) $search->destination);

        if ($destination !== '') {
            $matched = (clone $query)->search($destination)->get();

            if ($matched->isNotEmpty()) {
                return $this->filterByRoomType($matched, $search->room_type);
            }
        }

        // No destination match: show full active catalog (same as flight route fallback).
        return $this->filterByRoomType($query->get(), $search->room_type);
    }

    /**
     * @param  Collection<int, Hotel>  $hotels
     * @return Collection<int, Hotel>
     */
    private function filterByRoomType(Collection $hotels, ?string $roomType): Collection
    {
        $roomType = trim((string) $roomType);

        if ($roomType === '') {
            return $hotels;
        }

        $keywords = $this->roomTypeKeywords($roomType);

        $matched = $hotels->filter(function (Hotel $hotel) use ($keywords) {
            return $hotel->activeRooms()
                ->where(function (Builder $q) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $q->orWhere('room_type', 'like', '%'.$keyword.'%')
                            ->orWhere('name', 'like', '%'.$keyword.'%')
                            ->orWhere('bed_type', 'like', '%'.$keyword.'%');
                    }
                })
                ->exists();
        })->values();

        // Keep results visible when UI labels (e.g. "Double Room") differ from admin room names.
        return $matched->isNotEmpty() ? $matched : $hotels;
    }

    /**
     * Map public search labels to admin catalog room vocabulary.
     *
     * @return list<string>
     */
    private function roomTypeKeywords(string $roomType): array
    {
        $normalized = strtolower($roomType);

        $keywords = match ($normalized) {
            'single room' => ['single'],
            'double room' => ['double', 'standard'],
            'deluxe room' => ['deluxe'],
            default => array_filter([$normalized, str_replace(' room', '', $normalized)]),
        };

        return array_values(array_unique(array_filter($keywords)));
    }

    public function syncSearchResults(HotelSearch $search): HotelSearch
    {
        $search->results()->delete();

        $hotels = $this->hotelsForSearch($search);
        $rooms = max(1, (int) $search->rooms);

        foreach ($hotels as $hotel) {
            HotelSearchResult::query()->create([
                'hotel_search_id' => $search->id,
                'hotel_id' => $hotel->id,
                'slug' => $hotel->slug,
                'title' => $hotel->title,
                'location' => $hotel->location,
                'star_rating' => $hotel->starCount(),
                'rating' => (float) ($hotel->rating ?? 0),
                'review_count' => (int) ($hotel->review_count ?? 0),
                'price' => (float) $hotel->price * $rooms,
                'featured_image' => $hotel->featured_image ?? $hotel->image,
                'is_featured' => (bool) $hotel->is_featured,
            ]);
        }

        return $search->load('results.catalogHotel');
    }

    public function resultsAreStale(HotelSearch $search): bool
    {
        if (! $search->results()->exists()) {
            return true;
        }

        if ($search->results()->whereNull('hotel_id')->exists()) {
            return true;
        }

        $expected = $this->hotelsForSearch($search)->count();

        if ($expected !== $search->results()->count()) {
            return true;
        }

        $latestCatalog = Hotel::query()->active()->max('updated_at');
        $latestResult = $search->results()->max('updated_at');

        if (! $latestCatalog || ! $latestResult) {
            return true;
        }

        return Carbon::parse($latestCatalog)->gt(Carbon::parse($latestResult));
    }

    /**
     * @return array<string, mixed>
     */
    public function defaultBrowseCriteria(): array
    {
        $hotel = Hotel::query()->active()->orderBy('id')->first();

        return [
            'destination' => $hotel?->location ?? $hotel?->title ?? 'Hotels',
            'journey_date' => now()->addDays(7)->toDateString(),
            'return_date' => now()->addDays(8)->toDateString(),
            'adult' => 2,
            'children' => 0,
            'infant' => 0,
            'rooms' => 1,
            'room_type' => 'Double Room',
        ];
    }
}
