<?php

namespace App\Services\Aerticket;

use App\Models\AerticketFlightOffer;
use App\Models\FlightSearch;

class AerticketFlightSearchService
{
    public function __construct(
        protected AerticketConfig $config,
        protected AerticketHttpClient $client,
        protected AerticketResponseMapper $mapper,
    ) {}

    /**
     * @param  array<string, mixed>  $criteria
     */
    public function search(FlightSearch $flightSearch, array $criteria): FlightSearch
    {
        $this->config->ensureConfigured();

        $payload = $this->buildSearchPayload($criteria);
        $response = $this->client->post('flight_search', 'search', [], $payload);

        $offers = $this->mapper->mapSearchOffers($response, $criteria);
        $sessionId = $this->mapper->extractSessionId($response);

        $flightSearch->update([
            'provider' => 'aerticket',
            'external_session_id' => $sessionId,
            'search_payload' => $payload,
            'search_response' => $response,
            'status' => 'completed',
        ]);

        $flightSearch->results()->delete();
        $flightSearch->aerticketOffers()->delete();

        foreach ($offers as $offer) {
            AerticketFlightOffer::query()->create(array_merge($offer, [
                'flight_search_id' => $flightSearch->id,
            ]));

            $flightSearch->results()->create([
                'external_offer_id' => $offer['external_offer_id'],
                'airline' => $offer['airline'],
                'flight_number' => $offer['flight_number'],
                'from_destination' => $offer['from_destination'],
                'to_destination' => $offer['to_destination'],
                'departure_at' => $offer['departure_at'],
                'arrival_at' => $offer['arrival_at'],
                'duration' => $offer['duration'],
                'stops' => $offer['stops'],
                'cabin_class' => $offer['cabin_class'],
                'price' => $offer['price'],
                'currency' => $offer['currency'],
                'raw_offer' => $offer['summary'],
            ]);
        }

        return $flightSearch->fresh(['results', 'aerticketOffers']);
    }

    public function getOfferDetail(AerticketFlightOffer $offer): AerticketFlightOffer
    {
        $this->config->ensureConfigured();

        $response = $this->client->get('flight_detail', 'offer_detail', [
            'offerId' => $offer->external_offer_id,
        ]);

        $mapped = $this->mapper->mapOffer(
            $response['offer'] ?? $response['data'] ?? $response,
            $offer->flightSearch->toSearchCriteria(),
        );

        $offer->update([
            'detail_response' => $response,
            'detail_fetched_at' => now(),
            'summary' => array_merge($offer->summary ?? [], $mapped['summary'] ?? []),
            'airline' => $mapped['airline'] ?? $offer->airline,
            'flight_number' => $mapped['flight_number'] ?? $offer->flight_number,
            'departure_at' => $mapped['departure_at'] ?? $offer->departure_at,
            'arrival_at' => $mapped['arrival_at'] ?? $offer->arrival_at,
            'duration' => $mapped['duration'] ?? $offer->duration,
            'price' => $mapped['price'] ?? $offer->price,
            'currency' => $mapped['currency'] ?? $offer->currency,
        ]);

        return $offer->fresh();
    }

    /**
     * @param  array<string, mixed>  $criteria
     */
    private function buildSearchPayload(array $criteria): array
    {
        $passengers = [
            ['type' => 'ADT', 'count' => (int) $criteria['adult']],
        ];

        if (! empty($criteria['children'])) {
            $passengers[] = ['type' => 'CHD', 'count' => (int) $criteria['children']];
        }

        if (! empty($criteria['infant'])) {
            $passengers[] = ['type' => 'INF', 'count' => (int) $criteria['infant']];
        }

        $legs = [[
            'origin' => $criteria['from_destination'],
            'destination' => $criteria['to_destination'],
            'departureDate' => $criteria['journey_date'],
        ]];

        if (($criteria['trip_type'] ?? '') === 'round_trip' && ! empty($criteria['return_date'])) {
            $legs[] = [
                'origin' => $criteria['to_destination'],
                'destination' => $criteria['from_destination'],
                'departureDate' => $criteria['return_date'],
            ];
        }

        return array_filter([
            'agencyCode' => config('aerticket.agency_code'),
            'tripType' => ($criteria['trip_type'] ?? 'one_way') === 'round_trip' ? 'RT' : 'OW',
            'cabinClass' => strtoupper($criteria['cabin_class'] ?? 'economy'),
            'passengers' => $passengers,
            'legs' => $legs,
            'origin' => $criteria['from_destination'],
            'destination' => $criteria['to_destination'],
            'departureDate' => $criteria['journey_date'],
            'returnDate' => $criteria['return_date'] ?? null,
        ]);
    }
}
