<?php

namespace App\Services;

use App\Models\HotelSearchResult;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class HotelSearchResultsService
{
    /** @var array<string, string> */
    public const SORT_OPTIONS = [
        'default' => 'Sort By Default',
        'popular' => 'Sort By Popular',
        'price_asc' => 'Sort By Low Price',
        'price_desc' => 'Sort By High Price',
        'rating_desc' => 'Sort By Rating',
    ];

    public function __construct(
        protected CatalogMasterDataService $masterData,
    ) {}

    /**
     * @param  Collection<int, HotelSearchResult>  $results
     * @return Collection<int, HotelSearchResult>
     */
    public function filterAndSort(Collection $results, Request $request): Collection
    {
        $filtered = $results->values();

        $filtered = $this->applyMasterDataFilters($filtered, $request);

        $currency = app(CurrencyService::class);
        $storageCurrency = $currency->catalogSource();

        if ($request->has('price_min')) {
            $min = $currency->fromDisplay((float) $request->input('price_min'), $storageCurrency);
            $filtered = $filtered->filter(
                fn (HotelSearchResult $result) => (float) $result->price >= $min
            );
        }

        if ($request->has('price_max')) {
            $max = $currency->fromDisplay((float) $request->input('price_max'), $storageCurrency);
            $filtered = $filtered->filter(
                fn (HotelSearchResult $result) => (float) $result->price <= $max
            );
        }

        $stars = $this->intArrayInput($request, 'stars');
        if ($stars !== []) {
            $filtered = $filtered->filter(
                fn (HotelSearchResult $result) => in_array($result->starCount(), $stars, true)
            );
        }

        $sort = $this->resolveSort($request);

        return $this->sort($filtered->values(), $sort);
    }

    /**
     * @param  Collection<int, HotelSearchResult>  $results
     * @return Collection<int, HotelSearchResult>
     */
    private function applyMasterDataFilters(Collection $results, Request $request): Collection
    {
        $filtered = $results;

        foreach (MasterDataRegistry::catalogRelations('hotel') as $relation => $config) {
            $ids = array_filter(array_map('intval', (array) $request->input($config['param'], [])));

            if ($ids === []) {
                continue;
            }

            $filtered = $filtered->filter(function (HotelSearchResult $result) use ($relation, $ids) {
                $hotel = $result->catalogHotel;

                if (! $hotel || ! method_exists($hotel, $relation)) {
                    return false;
                }

                return $hotel->{$relation}()->whereIn($hotel->{$relation}()->getRelated()->getTable().'.id', $ids)->exists();
            });
        }

        return $filtered->values();
    }

    /**
     * @param  Collection<int, HotelSearchResult>  $results
     * @return array<string, mixed>
     */
    public function computeFacets(Collection $results): array
    {
        $currency = app(CurrencyService::class);
        $storageCurrency = $currency->catalogSource();
        $priceMin = $results->isEmpty() ? 0 : (float) $results->min('price');
        $priceMax = $results->isEmpty() ? 1000 : (float) $results->max('price');
        $displayMin = (int) floor($currency->toDisplay($priceMin, $storageCurrency));
        $displayMax = (int) ceil($currency->toDisplay(
            $priceMax > $priceMin ? $priceMax : max($priceMin + 100, 1000),
            $storageCurrency
        ));

        $starFacets = [];
        foreach ([5, 4, 3, 2, 1] as $star) {
            $count = $results->where('star_rating', $star)->count();
            if ($count > 0) {
                $starFacets[] = [
                    'value' => $star,
                    'label' => $star.' Star',
                    'count' => $count,
                ];
            }
        }

        return [
            'price_min' => $displayMin,
            'price_max' => $displayMax > $displayMin ? $displayMax : max($displayMin + 100, 1000),
            'stars' => $starFacets,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function activeFilters(Request $request): array
    {
        $active = [
            'stars' => $this->intArrayInput($request, 'stars'),
            'price_min' => $request->query('price_min'),
            'price_max' => $request->query('price_max'),
            'sort' => $this->resolveSort($request),
        ];

        foreach ($this->masterData->filterGroupsForCatalog('hotel') as $group) {
            $active[$group['param']] = array_map('intval', (array) $request->input($group['param'], []));
        }

        return $active;
    }

    /**
     * @param  Collection<int, HotelSearchResult>  $results
     * @return Collection<int, HotelSearchResult>
     */
    private function sort(Collection $results, string $sort): Collection
    {
        return match ($sort) {
            'price_desc' => $results->sortByDesc('price')->values(),
            'rating_desc' => $results->sort(function (HotelSearchResult $a, HotelSearchResult $b) {
                return [(float) $b->rating, (int) $b->review_count] <=> [(float) $a->rating, (int) $a->review_count];
            })->values(),
            'popular' => $results->sortByDesc('is_featured')->sortByDesc('review_count')->values(),
            'price_asc' => $results->sortBy('price')->values(),
            default => $results->sortByDesc('is_featured')->sortByDesc('id')->values(),
        };
    }

    public function resolveSort(Request $request): string
    {
        $sort = (string) $request->query('sort', $request->input('sort', 'default'));

        return array_key_exists($sort, self::SORT_OPTIONS) ? $sort : 'default';
    }

    /**
     * @return array<int, int>
     */
    private function intArrayInput(Request $request, string $key): array
    {
        return array_values(array_filter(array_map('intval', (array) $request->input($key, []))));
    }
}
