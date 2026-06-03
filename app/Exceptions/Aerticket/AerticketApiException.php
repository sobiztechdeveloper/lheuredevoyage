<?php

namespace App\Exceptions\Aerticket;

use Exception;
use Throwable;

class AerticketApiException extends Exception
{
    public function __construct(
        string $message,
        public readonly ?int $statusCode = null,
        public readonly ?array $responseBody = null,
        public readonly ?string $correlationId = null,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $statusCode ?? 0, $previous);
    }

    public function userMessage(): string
    {
        return config('aerticket.enabled')
            ? 'We could not complete your flight request with our airline partner. Please try again or contact support.'
            : 'Flight search is temporarily unavailable.';
    }
}
