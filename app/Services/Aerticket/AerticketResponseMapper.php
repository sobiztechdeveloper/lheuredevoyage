<?php

namespace App\Services\Aerticket;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AerticketResponseMapper
{
    public function extractSessionId(array $response): ?string
    {
        return Arr::get($response, 'sessionId')
            ?? Arr::get($response, 'session_id')
            ?? Arr::get($response, 'data.sessionId')
            ?? Arr::get($response, 'searchSessionId');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function mapSearchOffers(array $response, array $searchCriteria): array
    {
        $offers = Arr::get($response, 'offers')
            ?? Arr::get($response, 'data.offers')
            ?? Arr::get($response, 'results')
            ?? Arr::get($response, 'data.results')
            ?? [];

        if (! is_array($offers)) {
            return [];
        }

        return collect($offers)
            ->map(fn ($offer, $index) => $this->mapOffer($offer, $searchCriteria, $index))
            ->filter(fn ($offer) => ! empty($offer['external_offer_id']))
            ->values()
            ->all();
    }

    public function mapOffer(array $offer, array $searchCriteria, int $index = 0): array
    {
        $segments = Arr::get($offer, 'segments')
            ?? Arr::get($offer, 'itineraries.0.segments')
            ?? Arr::get($offer, 'flights')
            ?? [];

        $first = is_array($segments) ? ($segments[0] ?? []) : [];
        $last = is_array($segments) ? (collect($segments)->last() ?? []) : [];

        $departureAt = $this->parseDateTime(
            Arr::get($first, 'departureDateTime')
            ?? Arr::get($first, 'departure_at')
            ?? Arr::get($offer, 'departureDateTime')
        );

        $arrivalAt = $this->parseDateTime(
            Arr::get($last, 'arrivalDateTime')
            ?? Arr::get($last, 'arrival_at')
            ?? Arr::get($offer, 'arrivalDateTime')
        );

        $price = Arr::get($offer, 'price.total')
            ?? Arr::get($offer, 'totalPrice')
            ?? Arr::get($offer, 'price')
            ?? 0;

        return [
            'external_offer_id' => (string) (
                Arr::get($offer, 'id')
                ?? Arr::get($offer, 'offerId')
                ?? Arr::get($offer, 'offer_id')
                ?? 'offer-'.($index + 1).'-'.Str::random(8)
            ),
            'airline' => Arr::get($first, 'airline.name')
                ?? Arr::get($first, 'carrier')
                ?? Arr::get($offer, 'airline')
                ?? 'Airline',
            'flight_number' => Arr::get($first, 'flightNumber')
                ?? Arr::get($first, 'flight_number')
                ?? Arr::get($offer, 'flightNumber')
                ?? '',
            'from_destination' => Arr::get($first, 'departure.airport')
                ?? Arr::get($first, 'origin')
                ?? $searchCriteria['from_destination'],
            'to_destination' => Arr::get($last, 'arrival.airport')
                ?? Arr::get($last, 'destination')
                ?? $searchCriteria['to_destination'],
            'departure_at' => $departureAt,
            'arrival_at' => $arrivalAt,
            'duration' => Arr::get($offer, 'duration') ?? $this->formatDuration($departureAt, $arrivalAt),
            'stops' => max(0, (is_countable($segments) ? count($segments) : 1) - 1),
            'cabin_class' => Arr::get($offer, 'cabinClass')
                ?? Arr::get($first, 'cabinClass')
                ?? $searchCriteria['cabin_class'],
            'price' => is_array($price) ? (float) ($price['amount'] ?? 0) : (float) $price,
            'currency' => Arr::get($offer, 'price.currency')
                ?? Arr::get($offer, 'currency')
                ?? 'USD',
            'summary' => $offer,
        ];
    }

    public function mapFareRules(array $response): array
    {
        return [
            'rules' => Arr::get($response, 'fareRules')
                ?? Arr::get($response, 'rules')
                ?? Arr::get($response, 'data.fareRules')
                ?? [],
            'raw' => $response,
        ];
    }

    private function parseDateTime(mixed $value): ?Carbon
    {
        if (! $value) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }

    private function formatDuration(?Carbon $from, ?Carbon $to): ?string
    {
        if (! $from || ! $to) {
            return null;
        }

        $minutes = $from->diffInMinutes($to);

        return intdiv($minutes, 60).'h '.($minutes % 60).'m';
    }
}
