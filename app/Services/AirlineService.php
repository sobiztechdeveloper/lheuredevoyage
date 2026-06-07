<?php

namespace App\Services;

use App\Models\Airline;
use Illuminate\Support\Str;

class AirlineService
{
    public function __construct(
        protected ActivityLogService $activityLog,
        protected AirlineLogoResolver $logoResolver,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function prepareForStorage(array $data, bool $isActive = true): array
    {
        $data['is_active'] = $isActive;
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['slug'] = ! empty($data['slug'])
            ? Str::slug($data['slug'])
            : Airline::generateSlug($data['name']);
        $data['code'] = isset($data['code']) && $data['code'] !== ''
            ? strtoupper(trim((string) $data['code']))
            : null;
        $data['aliases'] = $this->parseAliases($data['aliases'] ?? null);

        if (array_key_exists('description', $data) && $data['description'] === '') {
            $data['description'] = null;
        }

        return $data;
    }

    /**
     * @return list<string>|null
     */
    public function parseAliases(mixed $raw): ?array
    {
        if ($raw === null || $raw === '') {
            return null;
        }

        if (is_array($raw)) {
            $aliases = array_values(array_filter(array_map(
                fn ($value) => trim((string) $value),
                $raw
            )));

            return $aliases === [] ? null : $aliases;
        }

        $parts = preg_split('/[\n,]+/', (string) $raw) ?: [];
        $aliases = array_values(array_filter(array_map('trim', $parts)));

        return $aliases === [] ? null : $aliases;
    }

    public function logMutation(string $action, Airline $airline, array $properties = []): void
    {
        $this->activityLog->log($action, $airline, $properties);
        $this->logoResolver->flush();
    }
}
