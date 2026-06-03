<?php

namespace App\Services\Aerticket;

use App\Exceptions\Aerticket\AerticketNotConfiguredException;

class AerticketConfig
{
    public function isEnabled(): bool
    {
        return (bool) config('aerticket.enabled');
    }

    public function ensureConfigured(): void
    {
        if (! $this->isEnabled()) {
            throw new AerticketNotConfiguredException;
        }

        foreach (['api_url', 'username', 'password'] as $key) {
            if (empty(config("aerticket.{$key}"))) {
                throw new AerticketNotConfiguredException;
            }
        }
    }

    public function apiUrl(): string
    {
        return (string) config('aerticket.api_url');
    }

    public function endpoint(string $key, array $replacements = []): string
    {
        $path = config("aerticket.endpoints.{$key}", '');

        foreach ($replacements as $placeholder => $value) {
            $path = str_replace('{'.$placeholder.'}', $value, $path);
        }

        return $this->apiUrl().$path;
    }

    public function credentials(): array
    {
        return [
            'accessKey' => config('aerticket.access_key'),
            'loginName' => config('aerticket.username'),
            'loginPassword' => config('aerticket.password'),
            'agencyCode' => config('aerticket.agency_code'),
        ];
    }
}
