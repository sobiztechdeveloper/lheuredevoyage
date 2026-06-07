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
    public const DURATION_BUCKET_LABELS = [
        '0-5' => '0 - 5 hours',
        '5-10' => '5 - 10 hours',
        '10-15' => '10 - 15 hours',
        '15plus' => '15+ hours',
    ];

    /** @var array<string, string> */
    public const REFUNDABLE_LABELS = [
        'non_refundable' => 'Non Refundable',
        'refundable' => 'Refundable',
        'as_per_rules' => 'As Per Rules',
    ];

    /** @var array<string, string> */
    public const OVERNIGHT_LABELS = [
        'same_day' => 'Same Day Arrival',
        'overnight' => 'Overnight',
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

        $currency = app(CurrencyService::class);
        $storageCurrency = $currency->serpapiSource();

        if ($request->has('price_min')) {
            $min = $currency->fromDisplay((float) $request->input('price_min'), $storageCurrency);
            $filtered = $filtered->filter(
                fn (FlightSearchResult $result) => (float) $result->price >= $min
            );
        }
        if ($request->has('price_max')) {
            $max = $currency->fromDisplay((float) $request->input('price_max'), $storageCurrency);
            $filtered = $filtered->filter(
                fn (FlightSearchResult $result) => (float) $result->price <= $max
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

        $arrivalTimeFilters = $this->arrayInput($request, 'flight_arrival_time');
        if ($arrivalTimeFilters !== []) {
            $filtered = $filtered->filter(function (FlightSearchResult $result) use ($arrivalTimeFilters) {
                $hour = (int) $result->arrival_at->format('G');

                foreach ($arrivalTimeFilters as $slot) {
                    if ($this->hourMatchesTimeSlot($hour, $slot)) {
                        return true;
                    }
                }

                return false;
            });
        }

        $durationFilters = $this->arrayInput($request, 'flight_duration');
        if ($durationFilters !== []) {
            $filtered = $filtered->filter(function (FlightSearchResult $result) use ($durationFilters) {
                $minutes = $this->durationMinutes($result);

                foreach ($durationFilters as $bucket) {
                    if ($this->durationMatchesBucket($minutes, $bucket)) {
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

        $overnightFilters = $this->arrayInput($request, 'flight_overnight');
        if ($overnightFilters !== []) {
            $filtered = $filtered->filter(function (FlightSearchResult $result) use ($overnightFilters) {
                $value = $this->isOvernightFlight($result) ? 'overnight' : 'same_day';

                return in_array($value, $overnightFilters, true);
            });
        }

        $layoverFilters = $this->arrayInput($request, 'flight_layover');
        if ($layoverFilters !== []) {
            $filtered = $filtered->filter(function (FlightSearchResult $result) use ($layoverFilters) {
                $slugs = array_column($this->layoverAirports($result), 'slug');

                foreach ($layoverFilters as $slug) {
                    if (in_array($slug, $slugs, true)) {
                        return true;
                    }
                }

                return false;
            });
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
        if (! in_array($sort, ['price_asc', 'price_desc', 'departure_asc', 'arrival_asc', 'duration_asc', 'stops_asc'], true)) {
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
        $currency = app(CurrencyService::class);
        $storageCurrency = $currency->serpapiSource();
        $priceMin = $results->isEmpty() ? 0 : (float) $results->min('price');
        $priceMax = $results->isEmpty() ? 1000 : (float) $results->max('price');
        $displayMin = (int) floor($currency->toDisplay($priceMin, $storageCurrency));
        $displayMax = (int) ceil($currency->toDisplay(
            $priceMax > $priceMin ? $priceMax : max($priceMin + 100, 1000),
            $storageCurrency
        ));

        return [
            'price_min' => $displayMin,
            'price_max' => $displayMax > $displayMin ? $displayMax : max($displayMin + 100, 1000),
            'classes' => $this->facetClasses($results),
            'times' => $this->facetTimeSlots($results),
            'arrival_times' => $this->facetArrivalTimeSlots($results),
            'durations' => $this->facetDurations($results),
            'stops' => $this->facetStops($results),
            'overnight' => $this->facetOvernight($results),
            'layovers' => $this->facetLayoverAirports($results),
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
        if (! in_array($sort, ['price_asc', 'price_desc', 'departure_asc', 'arrival_asc', 'duration_asc', 'stops_asc'], true)) {
            $sort = 'price_asc';
        }

        return [
            'flight_class' => $this->arrayInput($request, 'flight_class'),
            'flight_time' => $this->arrayInput($request, 'flight_time'),
            'flight_arrival_time' => $this->arrayInput($request, 'flight_arrival_time'),
            'flight_duration' => $this->arrayInput($request, 'flight_duration'),
            'flight_stop' => $this->arrayInput($request, 'flight_stop'),
            'flight_overnight' => $this->arrayInput($request, 'flight_overnight'),
            'flight_layover' => $this->arrayInput($request, 'flight_layover'),
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

    public function layoverSlug(string $airport): string
    {
        return Str::slug($airport);
    }

    public function isOvernightFlight(FlightSearchResult $result): bool
    {
        if (! $result->departure_at || ! $result->arrival_at) {
            return false;
        }

        return $result->arrival_at->toDateString() !== $result->departure_at->toDateString();
    }

    /**
     * @return list<array{slug: string, label: string}>
     */
    public function layoverAirports(FlightSearchResult $result): array
    {
        if (! is_array($result->raw_offer)) {
            return [];
        }

        $airports = [];

        foreach ($result->raw_offer['layovers'] ?? [] as $layover) {
            $label = trim((string) ($layover['name'] ?? $layover['id'] ?? ''));

            if ($label === '') {
                continue;
            }

            $slug = $this->layoverSlug($label);
            $airports[$slug] = ['slug' => $slug, 'label' => $label];
        }

        return array_values($airports);
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
            'stops_asc' => $results->sortBy('stops')->values(),
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

    private function durationMatchesBucket(int $minutes, string $bucket): bool
    {
        return match ($bucket) {
            '0-5' => $minutes >= 0 && $minutes < 300,
            '5-10' => $minutes >= 300 && $minutes < 600,
            '10-15' => $minutes >= 600 && $minutes < 900,
            '15plus' => $minutes >= 900,
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

        foreach (self::CABIN_LABELS as $cabin => $label) {
            $facets[] = [
                'value' => $cabin,
                'label' => $label,
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
    private function facetArrivalTimeSlots(Collection $results): array
    {
        $facets = [];
        foreach (self::TIME_SLOT_LABELS as $slot => $label) {
            $count = $results->filter(function (FlightSearchResult $result) use ($slot) {
                return $this->hourMatchesTimeSlot((int) $result->arrival_at->format('G'), $slot);
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
    private function facetDurations(Collection $results): array
    {
        $facets = [];
        foreach (self::DURATION_BUCKET_LABELS as $bucket => $label) {
            $count = $results->filter(function (FlightSearchResult $result) use ($bucket) {
                return $this->durationMatchesBucket($this->durationMinutes($result), $bucket);
            })->count();
            if ($count > 0) {
                $facets[] = ['value' => $bucket, 'label' => $label, 'count' => $count];
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
    private function facetOvernight(Collection $results): array
    {
        $facets = [];

        foreach (self::OVERNIGHT_LABELS as $value => $label) {
            $count = $results->filter(function (FlightSearchResult $result) use ($value) {
                $bucket = $this->isOvernightFlight($result) ? 'overnight' : 'same_day';

                return $bucket === $value;
            })->count();

            if ($count > 0) {
                $facets[] = ['value' => $value, 'label' => $label, 'count' => $count];
            }
        }

        return $facets;
    }

    /**
     * @param  Collection<int, FlightSearchResult>  $results
     * @return list<array{value: string, label: string, count: int}>
     */
    private function facetLayoverAirports(Collection $results): array
    {
        $counts = [];

        foreach ($results as $result) {
            foreach ($this->layoverAirports($result) as $airport) {
                $counts[$airport['slug']] ??= ['value' => $airport['slug'], 'label' => $airport['label'], 'count' => 0];
                $counts[$airport['slug']]['count']++;
            }
        }

        return collect($counts)->sortBy('label')->values()->all();
    }

    /**
     * @param  Collection<int, FlightSearchResult>  $results
     * @return list<array{value: string, label: string, count: int}>
     */
    private function facetAirlines(Collection $results): array
    {
        $resolver = app(AirlineLogoResolver::class);

        return $results->groupBy('airline')
            ->map(function (Collection $group, string $airline) use ($resolver) {
                $sample = $group->first();

                return [
                    'value' => $this->airlineSlug($airline),
                    'label' => $airline,
                    'logo_url' => $sample instanceof FlightSearchResult ? $sample->airlineLogoUrl() : $resolver->resolve($airline),
                    'count' => $group->count(),
                ];
            })
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
        $facets = $results->groupBy('baggage_kg')
            ->filter(fn (Collection $group, $kg) => (int) $kg > 0)
            ->map(fn (Collection $group, $kg) => [
                'value' => (string) (int) $kg,
                'label' => (int) $kg.' Kg',
                'count' => $group->count(),
            ])
            ->sortByDesc('value')
            ->values()
            ->all();

        return $facets;
    }

    /**
     * @param  Collection<int, FlightSearchResult>  $results
     * @return list<array{value: string, label: string, count: int}>
     */
    private function facetRefundable(Collection $results): array
    {
        $types = $results->pluck('refundable_type')->unique()->values();

        if ($types->count() === 1 && $types->first() === 'as_per_rules') {
            return [];
        }

        $facets = [];
        foreach ($types as $type) {
            $facets[] = [
                'value' => $type,
                'label' => self::REFUNDABLE_LABELS[$type] ?? ucfirst(str_replace('_', ' ', $type)),
                'count' => $results->where('refundable_type', $type)->count(),
            ];
        }

        return $facets;
    }
}
