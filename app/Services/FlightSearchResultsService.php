<?php

namespace App\Services;

use App\Models\FlightSearchResult;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class FlightSearchResultsService
{
    /** @var array<string, string> */
    public const TIME_SLOT_LABELS = [
        '1' => '00:00 - 05:59',
        '2' => '06:00 - 11:59',
        '3' => '12:00 - 17:59',
        '4' => '18:00 - 23:59',
    ];

    /** @var array<string, string> */
    public const CABIN_LABELS = [
        'economy' => 'Economy',
        'premium_economy' => 'Premium Economy',
        'business' => 'Business',
        'first' => 'First Class',
    ];

    /** @var array<string, string> */
    public const STOP_LABELS = [
        '0' => 'Non Stop',
        '1' => '1 Stop',
        '2' => '2 Stop',
        '3plus' => '3+ Stop',
    ];

    /** @var array<string, string> */
    public const REFUNDABLE_LABELS = [
        'non_refundable' => 'Non Refundable',
        'refundable' => 'Refundable',
        'as_per_rules' => 'As Per Rules',
    ];

    /**
     * @param  Collection<int, FlightSearchResult>  $results
     * @return Collection<int, FlightSearchResult>
     */
    public function filterAndSort(Collection $results, Request $request): Collection
    {
        $filtered = $results->values();

        $classFilters = $this->arrayInput($request, 'flight_class');
        if ($classFilters !== []) {
            $filtered = $filtered->filter(
                fn (FlightSearchResult $result) => in_array($result->cabin_class, $classFilters, true)
            );
        }

        if ($request->has('price_min')) {
            $filtered = $filtered->filter(
                fn (FlightSearchResult $result) => (float) $result->price >= (float) $request->input('price_min')
            );
        }
        if ($request->has('price_max')) {
            $filtered = $filtered->filter(
                fn (FlightSearchResult $result) => (float) $result->price <= (float) $request->input('price_max')
            );
        }

        $timeFilters = $this->arrayInput($request, 'flight_time');
        if ($timeFilters !== []) {
            $filtered = $filtered->filter(function (FlightSearchResult $result) use ($timeFilters) {
                $hour = (int) $result->departure_at->format('G');

                foreach ($timeFilters as $slot) {
                    if ($this->hourMatchesTimeSlot($hour, $slot)) {
                        return true;
                    }
                }

                return false;
            });
        }

        $stopFilters = $this->arrayInput($request, 'flight_stop');
        if ($stopFilters !== []) {
            $filtered = $filtered->filter(function (FlightSearchResult $result) use ($stopFilters) {
                foreach ($stopFilters as $value) {
                    if ($value === '3plus' && $result->stops >= 3) {
                        return true;
                    }
                    if ((string) $result->stops === (string) $value) {
                        return true;
                    }
                }

                return false;
            });
        }

        $airlineFilters = $this->arrayInput($request, 'flight_airline');
        if ($airlineFilters !== []) {
            $filtered = $filtered->filter(
                fn (FlightSearchResult $result) => in_array($this->airlineSlug($result->airline), $airlineFilters, true)
            );
        }

        $weightFilters = $this->arrayInput($request, 'flight_weight');
        if ($weightFilters !== []) {
            $allowed = array_map('intval', $weightFilters);
            $filtered = $filtered->filter(
                fn (FlightSearchResult $result) => in_array((int) $result->baggage_kg, $allowed, true)
            );
        }

        $refundFilters = $this->arrayInput($request, 'flight_refundable');
        if ($refundFilters !== []) {
            $filtered = $filtered->filter(
                fn (FlightSearchResult $result) => in_array($result->refundable_type, $refundFilters, true)
            );
        }

        $sort = $request->query('sort', $request->input('sort', 'price_asc'));
        if (! in_array($sort, ['price_asc', 'price_desc', 'departure_asc', 'arrival_asc', 'duration_asc'], true)) {
            $sort = 'price_asc';
        }

        return $this->sort($filtered->values(), $sort);
    }

    /**
     * @param  Collection<int, FlightSearchResult>  $results
     * @return array<string, mixed>
     */
    public function computeFacets(Collection $results): array
    {
        $priceMin = $results->isEmpty() ? 0 : (float) $results->min('price');
        $priceMax = $results->isEmpty() ? 1000 : (float) $results->max('price');

        return [
            'price_min' => $priceMin,
            'price_max' => $priceMax > $priceMin ? $priceMax : max($priceMin + 100, 1000),
            'classes' => $this->facetClasses($results),
            'times' => $this->facetTimeSlots($results),
            'stops' => $this->facetStops($results),
            'airlines' => $this->facetAirlines($results),
            'weights' => $this->facetWeights($results),
            'refundable' => $this->facetRefundable($results),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function activeFilters(Request $request): array
    {
        $sort = $request->query('sort', $request->input('sort', 'price_asc'));
        if (! in_array($sort, ['price_asc', 'price_desc', 'departure_asc', 'arrival_asc', 'duration_asc'], true)) {
            $sort = 'price_asc';
        }

        return [
            'flight_class' => $this->arrayInput($request, 'flight_class'),
            'flight_time' => $this->arrayInput($request, 'flight_time'),
            'flight_stop' => $this->arrayInput($request, 'flight_stop'),
            'flight_airline' => $this->arrayInput($request, 'flight_airline'),
            'flight_weight' => $this->arrayInput($request, 'flight_weight'),
            'flight_refundable' => $this->arrayInput($request, 'flight_refundable'),
            'price_min' => $request->query('price_min'),
            'price_max' => $request->query('price_max'),
            'sort' => $sort,
        ];
    }

    public function airlineSlug(?string $airline): string
    {
        return Str::slug((string) $airline);
    }

    /**
     * @param  Collection<int, FlightSearchResult>  $results
     * @return Collection<int, FlightSearchResult>
     */
    private function sort(Collection $results, string $sort): Collection
    {
        return match ($sort) {
            'price_desc' => $results->sortByDesc('price')->values(),
            'departure_asc' => $results->sortBy('departure_at')->values(),
            'arrival_asc' => $results->sortBy('arrival_at')->values(),
            'duration_asc' => $results->sortBy(fn (FlightSearchResult $r) => $this->durationMinutes($r))->values(),
            default => $results->sortBy('price')->values(),
        };
    }

    private function durationMinutes(FlightSearchResult $result): int
    {
        if ($result->departure_at && $result->arrival_at) {
            return (int) $result->departure_at->diffInMinutes($result->arrival_at);
        }

        if (preg_match('/(\d+)h\s*(\d+)m/', (string) $result->duration, $matches)) {
            return ((int) $matches[1] * 60) + (int) $matches[2];
        }

        return 0;
    }

    private function hourMatchesTimeSlot(int $hour, string $slot): bool
    {
        return match ($slot) {
            '1' => $hour >= 0 && $hour <= 5,
            '2' => $hour >= 6 && $hour <= 11,
            '3' => $hour >= 12 && $hour <= 17,
            '4' => $hour >= 18 && $hour <= 23,
            default => false,
        };
    }

    /**
     * @return list<string>
     */
    private function arrayInput(Request $request, string $key): array
    {
        $legacyKey = str_replace('_', '-', $key);
        $candidates = [$key, $legacyKey, $key.'[]', $legacyKey.'[]'];

        foreach ($candidates as $candidate) {
            $value = $request->query($candidate);
            if ($value === null) {
                $value = $request->input($candidate);
            }

            if ($value !== null && $value !== '') {
                return array_values(array_filter((array) $value, fn ($item) => $item !== null && $item !== ''));
            }
        }

        $all = $request->query();
        if (isset($all[$key]) && is_array($all[$key])) {
            return array_values(array_filter($all[$key], fn ($item) => $item !== null && $item !== ''));
        }

        return [];
    }

    /**
     * @param  Collection<int, FlightSearchResult>  $results
     * @return list<array{value: string, label: string, count: int}>
     */
    private function facetClasses(Collection $results): array
    {
        $facets = [];
        foreach ($results->pluck('cabin_class')->unique()->sort() as $cabin) {
            $facets[] = [
                'value' => $cabin,
                'label' => self::CABIN_LABELS[$cabin] ?? ucfirst(str_replace('_', ' ', $cabin)),
                'count' => $results->where('cabin_class', $cabin)->count(),
            ];
        }

        return $facets;
    }

    /**
     * @param  Collection<int, FlightSearchResult>  $results
     * @return list<array{value: string, label: string, count: int}>
     */
    private function facetTimeSlots(Collection $results): array
    {
        $facets = [];
        foreach (self::TIME_SLOT_LABELS as $slot => $label) {
            $count = $results->filter(function (FlightSearchResult $result) use ($slot) {
                return $this->hourMatchesTimeSlot((int) $result->departure_at->format('G'), $slot);
            })->count();
            if ($count > 0) {
                $facets[] = ['value' => $slot, 'label' => $label, 'count' => $count];
            }
        }

        return $facets;
    }

    /**
     * @param  Collection<int, FlightSearchResult>  $results
     * @return list<array{value: string, label: string, count: int}>
     */
    private function facetStops(Collection $results): array
    {
        $facets = [];
        foreach (['0', '1', '2'] as $stop) {
            $count = $results->where('stops', (int) $stop)->count();
            if ($count > 0) {
                $facets[] = [
                    'value' => $stop,
                    'label' => self::STOP_LABELS[$stop],
                    'count' => $count,
                ];
            }
        }
        $plus = $results->where('stops', '>=', 3)->count();
        if ($plus > 0) {
            $facets[] = ['value' => '3plus', 'label' => self::STOP_LABELS['3plus'], 'count' => $plus];
        }

        return $facets;
    }

    /**
     * @param  Collection<int, FlightSearchResult>  $results
     * @return list<array{value: string, label: string, count: int}>
     */
    private function facetAirlines(Collection $results): array
    {
        return $results->groupBy('airline')
            ->map(fn (Collection $group, string $airline) => [
                'value' => $this->airlineSlug($airline),
                'label' => $airline,
                'count' => $group->count(),
            ])
            ->sortBy('label')
            ->values()
            ->all();
    }

    /**
     * @param  Collection<int, FlightSearchResult>  $results
     * @return list<array{value: string, label: string, count: int}>
     */
    private function facetWeights(Collection $results): array
    {
        return $results->groupBy('baggage_kg')
            ->map(fn (Collection $group, $kg) => [
                'value' => (string) (int) $kg,
                'label' => (int) $kg.' Kg',
                'count' => $group->count(),
            ])
            ->sortByDesc('value')
            ->values()
            ->all();
    }

    /**
     * @param  Collection<int, FlightSearchResult>  $results
     * @return list<array{value: string, label: string, count: int}>
     */
    private function facetRefundable(Collection $results): array
    {
        $facets = [];
        foreach ($results->pluck('refundable_type')->unique() as $type) {
            $facets[] = [
                'value' => $type,
                'label' => self::REFUNDABLE_LABELS[$type] ?? ucfirst(str_replace('_', ' ', $type)),
                'count' => $results->where('refundable_type', $type)->count(),
            ];
        }

        return $facets;
    }
}
