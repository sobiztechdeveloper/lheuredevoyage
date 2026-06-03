<?php

namespace App\Services\Aerticket;

class AerticketTicketingService
{
    public function __construct(
        protected AerticketConfig $config,
        protected AerticketHttpClient $client,
    ) {}

    public function issueTicket(string $externalBookingId, array $paymentContext = []): array
    {
        $this->config->ensureConfigured();

        $payload = array_filter([
            'agencyCode' => config('aerticket.agency_code'),
            'payment' => $paymentContext,
        ]);

        return $this->client->post('ticketing', 'ticket', [
            'bookingId' => $externalBookingId,
        ], $payload);
    }
}
