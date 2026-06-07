<?php

namespace App\Services;

use App\Models\Airline;

class AirlineLogoResolver
{
    /** @var array<string, string>|null */
    private static ?array $lookup = null;

    public function resolve(?string $airlineName, ?string $flightNumber = null): ?string
    {
        $this->ensureLookup();

        foreach ($this->matchKeys($airlineName, $flightNumber) as $key) {
            if (isset(self::$lookup[$key])) {
                return self::$lookup[$key];
            }
        }

        return null;
    }

    public function flush(): void
    {
        self::$lookup = null;
    }

    private function ensureLookup(): void
    {
        if (self::$lookup !== null) {
            return;
        }

        self::$lookup = [];

        foreach (Airline::query()->active()->ordered()->get() as $airline) {
            $logoUrl = $airline->logo_url;

            if (! $logoUrl) {
                continue;
            }

            $this->remember($airline->name, $logoUrl);

            if ($airline->code) {
                $this->remember($airline->code, $logoUrl);
            }

            foreach ($airline->aliasList() as $alias) {
                $this->remember($alias, $logoUrl);
            }
        }
    }

    private function remember(string $value, string $logoUrl): void
    {
        $key = $this->normalizeKey($value);

        if ($key !== '') {
            self::$lookup[$key] = $logoUrl;
        }
    }

    /**
     * @return list<string>
     */
    private function matchKeys(?string $airlineName, ?string $flightNumber): array
    {
        $keys = [];

        if ($airlineName) {
            $keys[] = $this->normalizeKey($airlineName);
        }

        $code = $this->extractAirlineCode($flightNumber);

        if ($code) {
            $keys[] = $this->normalizeKey($code);
        }

        return array_values(array_unique(array_filter($keys)));
    }

    private function normalizeKey(string $value): string
    {
        return strtoupper(preg_replace('/\s+/', ' ', trim($value)) ?? '');
    }

    private function extractAirlineCode(?string $flightNumber): ?string
    {
        if (! $flightNumber) {
            return null;
        }

        $flightNumber = strtoupper(trim($flightNumber));

        if (preg_match('/^([A-Z0-9]{2})\s*\d/', $flightNumber, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
