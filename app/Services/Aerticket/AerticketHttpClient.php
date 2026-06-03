<?php

namespace App\Services\Aerticket;

use App\Exceptions\Aerticket\AerticketApiException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class AerticketHttpClient
{
    public function __construct(
        protected AerticketConfig $config,
        protected AerticketApiLogger $logger,
        protected AerticketAuthService $auth,
    ) {}

    public function get(string $service, string $endpointKey, array $replacements = [], array $query = [], bool $authenticated = true): array
    {
        return $this->request('GET', $service, $endpointKey, $replacements, $query, null, $authenticated);
    }

    public function post(string $service, string $endpointKey, array $replacements = [], ?array $body = null, bool $authenticated = true): array
    {
        return $this->request('POST', $service, $endpointKey, $replacements, [], $body, $authenticated);
    }

    public function request(
        string $method,
        string $service,
        string $endpointKey,
        array $replacements = [],
        array $query = [],
        ?array $body = null,
        bool $authenticated = true,
    ): array {
        $this->config->ensureConfigured();

        $correlationId = $this->logger->start($service);
        $endpoint = $this->config->endpoint($endpointKey, $replacements);
        $started = microtime(true);

        try {
            $pending = Http::timeout($this->config->isEnabled() ? config('aerticket.timeout') : 30)
                ->connectTimeout(config('aerticket.connect_timeout'))
                ->acceptJson()
                ->asJson();

            if ($authenticated) {
                $pending = $pending->withToken($this->auth->token());
            }

            if (! empty(config('aerticket.access_key'))) {
                $pending = $pending->withHeaders([
                    'X-Access-Key' => config('aerticket.access_key'),
                ]);
            }

            $response = match (strtoupper($method)) {
                'GET' => $pending->get($endpoint, $query),
                'POST' => $pending->post($endpoint, $body ?? []),
                'PUT' => $pending->put($endpoint, $body ?? []),
                'PATCH' => $pending->patch($endpoint, $body ?? []),
                'DELETE' => $pending->delete($endpoint, $body ?? []),
                default => throw new AerticketApiException("Unsupported HTTP method: {$method}", correlationId: $correlationId),
            };

            $durationMs = (int) round((microtime(true) - $started) * 1000);
            $json = $response->json() ?? [];

            $this->logger->log(
                $correlationId,
                $service,
                $method,
                $endpoint,
                $response->status(),
                $durationMs,
                array_filter(['query' => $query, 'body' => $body]),
                $json,
            );

            if ($response->failed()) {
                throw new AerticketApiException(
                    $this->extractErrorMessage($json, $response->status()),
                    $response->status(),
                    $json,
                    $correlationId,
                );
            }

            if ($this->responseIndicatesFailure($json)) {
                throw new AerticketApiException(
                    $this->extractErrorMessage($json),
                    $response->status(),
                    $json,
                    $correlationId,
                );
            }

            return $json;
        } catch (AerticketApiException $e) {
            throw $e;
        } catch (ConnectionException|RequestException $e) {
            $durationMs = (int) round((microtime(true) - $started) * 1000);

            $this->logger->log(
                $correlationId,
                $service,
                $method,
                $endpoint,
                $e->response?->status(),
                $durationMs,
                array_filter(['query' => $query, 'body' => $body]),
                null,
                $e->getMessage(),
            );

            throw new AerticketApiException(
                'AERTiCKET API connection failed: '.$e->getMessage(),
                $e->response?->status(),
                correlationId: $correlationId,
                previous: $e,
            );
        }
    }

    private function responseIndicatesFailure(array $json): bool
    {
        if (isset($json['success']) && $json['success'] === false) {
            return true;
        }

        if (isset($json['error']) && $json['error']) {
            return true;
        }

        if (isset($json['status']) && in_array(strtolower((string) $json['status']), ['error', 'failed'], true)) {
            return true;
        }

        return false;
    }

    private function extractErrorMessage(array $json, ?int $status = null): string
    {
        $message = $json['message']
            ?? $json['errorMessage']
            ?? $json['error']['message']
            ?? $json['errors'][0]['message']
            ?? null;

        if ($message) {
            return (string) $message;
        }

        return $status
            ? "AERTiCKET API returned HTTP {$status}"
            : 'AERTiCKET API returned an error response';
    }
}
