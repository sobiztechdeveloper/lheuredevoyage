<?php

namespace App\Services;

use App\Models\Flight;
use App\Models\FlightSearch;
use App\Models\FlightSearchResult;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class FlightCatalogService
{
    /**
     * @return Collection<int, Flight>
     */
    public function flightsForSearch(FlightSearch $search): Collection
    {
        $from = trim((string) $search->from_destination);
        $to = trim((string) $search->to_destination);
        $cabinClass = trim((string) $search->cabin_class);

        $query = Flight::query()->active()->orderBy('price');
        $flights = collect();

        if ($from !== '' && $to !== '') {
            $flights = (clone $query)->where(function ($q) use ($from) {
                $this->applyCityMatch($q, $from, ['departure_city', 'location', 'title']);
            })->where(function ($q) use ($to) {
                $this->applyCityMatch($q, $to, ['arrival_city', 'location', 'title']);
            })->get();
        }

        if ($flights->isEmpty() && $from !== '') {
            $flights = (clone $query)->where(function ($q) use ($from) {
                $this->applyCityMatch($q, $from, ['departure_city', 'arrival_city', 'location', 'title']);
            })->get();
        }

        if ($flights->isEmpty() && $to !== '') {
            $flights = (clone $query)->where(function ($q) use ($to) {
                $this->applyCityMatch($q, $to, ['arrival_city', 'departure_city', 'location', 'title']);
            })->get();
        }

        if ($flights->isEmpty() && ($from !== '' || $to !== '')) {
            return collect();
        }

        if ($flights->isEmpty()) {
            $flights = $query->get();
        }

        if ($cabinClass !== '' && ($from !== '' || $to !== '')) {
            $byCabin = $flights->filter(
                fn (Flight $flight) => $flight->normalizedCabinClass() === $cabinClass,
            )->values();

            if ($byCabin->isNotEmpty()) {
                return $byCabin;
            }
        }

        return $flights;
    }

    private function applyCityMatch($query, string $term, array $columns): void
    {
        $needles = app(CatalogListSearchService::class)->destinationNeedles($term);

        $query->where(function ($outer) use ($needles, $columns) {
            foreach ($needles as $needle) {
                $outer->orWhere(function ($inner) use ($needle, $columns) {
                    foreach ($columns as $column) {
                        $inner->orWhere($column, 'like', '%'.$needle.'%');
                    }
                });
            }
        });
    }

    public function syncSearchResults(FlightSearch $search): FlightSearch
    {
        $search->results()->delete();

        $flights = $this->flightsForSearch($search);
        $journeyDate = Carbon::parse($search->journey_date);

        foreach ($flights->values() as $index => $flight) {
            [$departureAt, $arrivalAt, $duration] = $this->resolveSchedule($flight, $journeyDate, $index);

            FlightSearchResult::query()->create([
                'flight_search_id' => $search->id,
                'flight_id' => $flight->id,
                'airline' => $flight->airline ?: $flight->name,
                'flight_number' => $flight->flight_number ?: ('LV'.$flight->id),
                'from_destination' => $flight->departure_city ?: $search->from_destination,
                'to_destination' => $flight->arrival_city ?: $search->to_destination,
                'departure_at' => $departureAt,
                'arrival_at' => $arrivalAt,
                'duration' => $duration,
                'stops' => (int) ($flight->stops ?? 0),
                'cabin_class' => $flight->normalizedCabinClass(),
                'price' => (float) $flight->price * max(1, (int) $search->adult),
                'currency' => 'USD',
                'refundable_type' => $flight->normalizedRefundableType(),
                'baggage_kg' => (int) ($flight->baggage_kg ?? 23),
            ]);
        }

        return $search->load('results');
    }

    public function resultsAreStale(FlightSearch $search): bool
    {
        if (! $search->results()->exists()) {
            return true;
        }

        if ($search->results()->whereNull('flight_id')->exists()) {
            return true;
        }

        $activeCount = Flight::query()->active()->count();
        if ($activeCount !== $search->results()->count()) {
            return true;
        }

        $latestCatalog = Flight::query()->active()->max('updated_at');
        $latestResult = $search->results()->max('updated_at');

        if (! $latestCatalog || ! $latestResult) {
            return true;
        }

        return Carbon::parse($latestCatalog)->gt(Carbon::parse($latestResult));
    }

    /**
     * @return array{0: Carbon, 1: Carbon, 2: string}
     */
    private function resolveSchedule(Flight $flight, Carbon $journeyDate, int $index): array
    {
        if ($flight->departure_time) {
            $departureAt = Carbon::parse($journeyDate->toDateString().' '.$flight->departure_time);
        } else {
            $departureAt = $journeyDate->copy()->setTime(6 + ($index % 10), ($index * 17) % 60);
        }

        if ($flight->arrival_time) {
            $arrivalAt = Carbon::parse($journeyDate->toDateString().' '.$flight->arrival_time);
            if ($arrivalAt->lte($departureAt)) {
                $arrivalAt->addDay();
            }
        } else {
            $minutes = $this->durationMinutesFromString($flight->duration) ?: (240 + ($index * 30));
            $arrivalAt = $departureAt->copy()->addMinutes($minutes);
        }

        $duration = $flight->duration ?: $this->formatDuration($departureAt, $arrivalAt);

        return [$departureAt, $arrivalAt, $duration];
    }

    private function durationMinutesFromString(?string $duration): ?int
    {
        if (! $duration || ! preg_match('/(\d+)\s*h\s*(\d+)\s*m/i', $duration, $matches)) {
            if ($duration && preg_match('/(\d+)\s*h/i', $duration, $hourOnly)) {
                return (int) $hourOnly[1] * 60;
            }

            return null;
        }

        return ((int) $matches[1] * 60) + (int) $matches[2];
    }

    private function formatDuration(Carbon $from, Carbon $to): string
    {
        $minutes = (int) $from->diffInMinutes($to);
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;

        return $hours.'h '.$mins.'m';
    }

    public function defaultBrowseCriteria(): array
    {
        $flight = Flight::query()->active()->orderBy('id')->first();

        return [
            'trip_type' => 'one_way',
            'from_destination' => '',
            'to_destination' => '',
            'journey_date' => now()->addDays(14)->toDateString(),
            'return_date' => null,
            'adult' => 1,
            'children' => 0,
            'infant' => 0,
            'cabin_class' => 'economy',
        ];
    }
}
