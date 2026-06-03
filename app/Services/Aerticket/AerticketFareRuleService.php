<?php

namespace App\Services\Aerticket;

use App\Models\AerticketFlightOffer;

class AerticketFareRuleService
{
    public function __construct(
        protected AerticketConfig $config,
        protected AerticketHttpClient $client,
        protected AerticketResponseMapper $mapper,
    ) {}

    public function getFareRules(AerticketFlightOffer $offer): array
    {
        $this->config->ensureConfigured();

        $response = $this->client->get('fare_rules', 'fare_rules', [
            'offerId' => $offer->external_offer_id,
        ]);

        $mapped = $this->mapper->mapFareRules($response);

        $offer->update([
            'fare_rules_response' => $mapped,
            'fare_rules_fetched_at' => now(),
        ]);

        return $mapped;
    }
}
