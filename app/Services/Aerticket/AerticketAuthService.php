<?php

namespace App\Services\Aerticket;

use App\Exceptions\Aerticket\AerticketApiException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AerticketAuthService
{
    public function __construct(
        protected AerticketConfig $config,
        protected AerticketApiLogger $logger,
    ) {}

    public function token(): string
    {
        $this->config->ensureConfigured();

        return Cache::remember('aerticket.auth.token', config('aerticket.token_cache_ttl'), function () {
            return $this->authenticate();
        });
    }

    public function clearToken(): void
    {
        Cache::forget('aerticket.auth.token');
    }

    public function authenticate(): string
    {
        $correlationId = $this->logger->start('auth');
        $endpoint = $this->config->endpoint('authenticate');
        $body = array_filter($this->config->credentials());
        $started = microtime(true);

        try {
            $response = Http::timeout(config('aerticket.timeout'))
                ->connectTimeout(config('aerticket.connect_timeout'))
                ->acceptJson()
                ->asJson()
                ->post($endpoint, $body);

            $durationMs = (int) round((microtime(true) - $started) * 1000);
            $json = $response->json() ?? [];

            $this->logger->log(
                $correlationId,
                'auth',
                'POST',
                $endpoint,
                $response->status(),
                $durationMs,
                ['body' => $this->redactBody($body)],
                $json,
                $response->failed() ? 'Authentication failed' : null,
            );

            if ($response->failed()) {
                throw new AerticketApiException(
                    'AERTiCKET authentication failed.',
                    $response->status(),
                    $json,
                    $correlationId,
                );
            }

            $token = $json['token']
                ?? $json['accessToken']
                ?? $json['sessionToken']
                ?? $json['data']['token']
                ?? null;

            if (! $token) {
                throw new AerticketApiException(
                    'AERTiCKET authentication response did not include a token.',
                    $response->status(),
                    $json,
                    $correlationId,
                );
            }

            return (string) $token;
        } catch (AerticketApiException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new AerticketApiException(
                'AERTiCKET authentication error: '.$e->getMessage(),
                correlationId: $correlationId,
                previous: $e,
            );
        }
    }

    private function redactBody(array $body): array
    {
        if (isset($body['loginPassword'])) {
            $body['loginPassword'] = '***';
        }

        return $body;
    }
}
