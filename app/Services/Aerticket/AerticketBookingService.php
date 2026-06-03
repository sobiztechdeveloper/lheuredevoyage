<?php

namespace App\Services\Aerticket;

use App\Models\AerticketFlightOffer;

class AerticketBookingService
{
    public function __construct(
        protected AerticketConfig $config,
        protected AerticketHttpClient $client,
    ) {}

    /**
     * @param  array<string, mixed>  $passengerData
     */
    public function createBooking(AerticketFlightOffer $offer, array $passengerData): array
    {
        $this->config->ensureConfigured();

        $payload = [
            'offerId' => $offer->external_offer_id,
            'sessionId' => $offer->flightSearch->external_session_id,
            'agencyCode' => config('aerticket.agency_code'),
            'passengers' => $passengerData['passengers'] ?? [],
            'contact' => $passengerData['contact'] ?? [],
            'remarks' => $passengerData['remarks'] ?? null,
        ];

        return $this->client->post('booking', 'book', [], array_filter($payload));
    }

    public function checkAvailability(AerticketFlightOffer $offer): array
    {
        $this->config->ensureConfigured();

        return $this->client->get('availability', 'availability', [
            'offerId' => $offer->external_offer_id,
        ], [
            'sessionId' => $offer->flightSearch->external_session_id,
        ]);
    }
}
