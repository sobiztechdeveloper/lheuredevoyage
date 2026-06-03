<?php

namespace App\Services;

use App\Exceptions\Aerticket\AerticketApiException;
use App\Models\AerticketFlightOffer;
use App\Models\FlightSearch;
use App\Services\Aerticket\AerticketConfig;
use App\Services\Aerticket\AerticketFareRuleService;
use App\Services\Aerticket\AerticketFlightSearchService;

class FlightSearchOrchestrator
{
    public function __construct(
        protected AerticketConfig $aerticketConfig,
        protected FlightSearchService $mockSearch,
        protected AerticketFlightSearchService $aerticketSearch,
        protected AerticketFareRuleService $fareRules,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function createSearch(array $data): FlightSearch
    {
        if ($this->aerticketConfig->isEnabled()) {
            return $this->createAerticketSearch($data);
        }

        return $this->mockSearch->createSearch($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function createAerticketSearch(array $data): FlightSearch
    {
        $search = FlightSearch::query()->create([
            'user_id' => auth()->id(),
            'provider' => 'aerticket',
            'status' => 'pending',
            'trip_type' => $data['trip_type'],
            'from_destination' => $data['from_destination'],
            'to_destination' => $data['to_destination'],
            'journey_date' => $data['journey_date'],
            'return_date' => $data['return_date'] ?? null,
            'adult' => $data['adult'],
            'children' => $data['children'] ?? 0,
            'infant' => $data['infant'] ?? 0,
            'cabin_class' => $data['cabin_class'],
            'search_payload' => $data,
        ]);

        try {
            return $this->aerticketSearch->search($search, $data);
        } catch (AerticketApiException $e) {
            $search->update(['status' => 'failed']);

            throw $e;
        }
    }

    public function getOfferDetail(FlightSearch $flightSearch, string $offerId): AerticketFlightOffer
    {
        $offer = $flightSearch->aerticketOffers()
            ->where('external_offer_id', $offerId)
            ->firstOrFail();

        if ($flightSearch->usesAerticket() && $this->aerticketConfig->isEnabled()) {
            return $this->aerticketSearch->getOfferDetail($offer);
        }

        return $offer;
    }

    public function getFareRules(FlightSearch $flightSearch, string $offerId): array
    {
        $offer = $flightSearch->aerticketOffers()
            ->where('external_offer_id', $offerId)
            ->firstOrFail();

        if ($offer->fare_rules_response) {
            return $offer->fare_rules_response;
        }

        if ($flightSearch->usesAerticket() && $this->aerticketConfig->isEnabled()) {
            return $this->fareRules->getFareRules($offer);
        }

        return [
            'rules' => [],
            'raw' => [],
            'message' => 'Fare rules are available when AERTiCKET is enabled.',
        ];
    }
}
