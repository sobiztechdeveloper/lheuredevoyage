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

        $query = Flight::query()->active()->orderBy('price');

        if ($from !== '' && $to !== '') {
            $matched = (clone $query)->where(function ($q) use ($from) {
                $q->where('departure_city', 'like', "%{$from}%")
                    ->orWhere('location', 'like', "%{$from}%")
                    ->orWhere('title', 'like', "%{$from}%");
            })->where(function ($q) use ($to) {
                $q->where('arrival_city', 'like', "%{$to}%")
                    ->orWhere('location', 'like', "%{$to}%")
                    ->orWhere('title', 'like', "%{$to}%");
            })->get();

            if ($matched->isNotEmpty()) {
                return $matched;
            }
        }

        return $query->get();
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
            'from_destination' => $flight?->departure_city ?? '',
            'to_destination' => $flight?->arrival_city ?? '',
            'journey_date' => now()->addDays(14)->toDateString(),
            'return_date' => null,
            'adult' => 1,
            'children' => 0,
            'infant' => 0,
            'cabin_class' => $flight?->normalizedCabinClass() ?? 'economy',
        ];
    }
}
