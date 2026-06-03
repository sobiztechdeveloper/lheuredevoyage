<?php

namespace App\Services\Aerticket;

use App\Models\AerticketApiLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AerticketApiLogger
{
    public function start(string $service): string
    {
        return (string) Str::uuid();
    }

    public function log(
        string $correlationId,
        string $service,
        string $method,
        string $endpoint,
        ?int $statusCode,
        int $durationMs,
        ?array $requestPayload,
        ?array $responsePayload,
        ?string $errorMessage = null,
    ): void {
        if (config('aerticket.log_requests')) {
            AerticketApiLog::query()->create([
                'correlation_id' => $correlationId,
                'service' => $service,
                'method' => $method,
                'endpoint' => $endpoint,
                'status_code' => $statusCode,
                'duration_ms' => $durationMs,
                'request_payload' => $this->redact($requestPayload),
                'response_payload' => $this->truncate($responsePayload),
                'error_message' => $errorMessage,
            ]);
        }

        $channel = config('aerticket.log_channel');

        Log::channel($channel)->info('AERTiCKET API', [
            'correlation_id' => $correlationId,
            'service' => $service,
            'method' => $method,
            'endpoint' => $endpoint,
            'status' => $statusCode,
            'duration_ms' => $durationMs,
            'error' => $errorMessage,
        ]);
    }

    private function redact(?array $payload): ?array
    {
        if ($payload === null) {
            return null;
        }

        $json = json_encode($payload);
        $json = preg_replace('/"loginPassword"\s*:\s*"[^"]*"/', '"loginPassword":"***"', $json) ?? $json;
        $json = preg_replace('/"password"\s*:\s*"[^"]*"/', '"password":"***"', $json) ?? $json;

        return json_decode($json, true);
    }

    private function truncate(?array $payload, int $maxLength = 50000): ?array
    {
        if ($payload === null) {
            return null;
        }

        $encoded = json_encode($payload);

        if ($encoded !== false && strlen($encoded) > $maxLength) {
            return ['_truncated' => true, 'preview' => substr($encoded, 0, $maxLength)];
        }

        return $payload;
    }
}
