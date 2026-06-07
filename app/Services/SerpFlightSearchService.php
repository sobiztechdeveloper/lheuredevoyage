<?php

namespace App\Services;

use App\Exceptions\SerpApi\SerpApiException;
use App\Models\FlightSearch;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SerpFlightSearchService
{
    public function __construct(
        protected FlightService $flightService,
    ) {}

    /**
     * @param  array<string, mixed>  $criteria
     */
    public function search(FlightSearch $flightSearch, array $criteria): FlightSearch
    {
        $departureId = (string) ($criteria['from_departure_id'] ?? '');
        $arrivalId = (string) ($criteria['to_arrival_id'] ?? '');

        $payload = array_merge($criteria, [
            'departure_id' => $departureId !== '' ? $departureId : null,
            'arrival_id' => $arrivalId !== '' ? $arrivalId : null,
            'outbound_date' => $criteria['journey_date'] ?? null,
        ]);

        $response = $this->flightService->searchFlights($payload);
        $offers = $response['flights'] ?? [];

        if ($offers === []) {
            throw SerpApiException::fromResponse('No flights found for your search.');
        }

        DB::transaction(function () use ($flightSearch, $offers, $payload, $response): void {
            $flightSearch->update([
                'provider' => 'serpapi',
                'status' => 'completed',
                'search_payload' => $payload,
                'search_response' => $response['raw'] ?? [],
            ]);

            $flightSearch->results()->delete();
            $flightSearch->aerticketOffers()->delete();

            foreach ($offers as $offer) {
                $flightSearch->results()->create([
                    'external_offer_id' => $offer['external_offer_id'],
                    'airline' => $offer['airline'],
                    'flight_number' => $offer['flight_number'],
                    'from_destination' => $offer['from_destination'],
                    'to_destination' => $offer['to_destination'],
                    'departure_at' => Carbon::parse($offer['departure_at']),
                    'arrival_at' => Carbon::parse($offer['arrival_at']),
                    'duration' => $offer['duration'],
                    'stops' => $offer['stops'],
                    'cabin_class' => $offer['cabin_class'],
                    'price' => $offer['price'],
                    'currency' => $offer['currency'],
                    'refundable_type' => $offer['refundable_type'],
                    'baggage_kg' => $offer['baggage_kg'],
                    'raw_offer' => array_merge($offer['raw_offer'] ?? [], [
                        'airline_logo' => $offer['airline_logo'] ?? null,
                        'deep_link' => $offer['deep_link'] ?? null,
                    ]),
                ]);
            }
        });

        return $flightSearch->fresh(['results']);
    }
}
