<?php

namespace App\Services;

use App\Enums\DestinationType;
use App\Models\TravelDestination;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DestinationService
{
    public function __construct(
        protected ActivityLogService $activityLog,
        protected FlightService $flightService,
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
            : TravelDestination::generateSlug($data['name'], $data['type'] ?? null);

        foreach (['code', 'country', 'city', 'region', 'description'] as $nullable) {
            if (array_key_exists($nullable, $data) && $data[$nullable] === '') {
                $data[$nullable] = null;
            }
        }

        foreach (['latitude', 'longitude'] as $coord) {
            if (array_key_exists($coord, $data) && ($data[$coord] === '' || $data[$coord] === null)) {
                $data[$coord] = null;
            }
        }

        return $data;
    }

    /**
     * @return Collection<int, TravelDestination>
     */
    public function suggestions(array $types = [], ?int $limit = null): Collection
    {
        $limit = $limit ?? (int) config('destinations.suggest_limit', 15);

        $query = TravelDestination::query()->active()->ordered();

        if ($types !== []) {
            $query->ofType($types);
        }

        return $query->limit($limit)->get();
    }

    /**
     * @return Collection<int, TravelDestination>
     */
    public function searchForApi(string $query, array $types = [], ?string $format = null): Collection
    {
        $query = trim($query);
        $minChars = (int) config('destinations.search_min_chars', 2);

        if ($query === '' || mb_strlen($query) < $minChars) {
            return $this->suggestions($types);
        }

        return TravelDestination::query()
            ->autocomplete($query, $types)
            ->get();
    }

    /**
     * @return list<string>
     */
    public function resolveTypesFromRequest(Request $request): array
    {
        $type = $request->input('type', $request->input('types'));

        if (is_string($type) && str_contains($type, ',')) {
            return array_values(array_filter(array_map('trim', explode(',', $type))));
        }

        if (is_array($type)) {
            return array_values(array_filter($type));
        }

        if (is_string($type) && $type !== '') {
            return [$type];
        }

        return [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function apiSearchResults(string $query, array $types, string $format = 'default', ?string $context = null): array
    {
        if ($this->shouldUseSerpapiFlightAutocomplete($context)) {
            return $this->flightService->searchAirports($query);
        }

        return $this->searchForApi($query, $types, $format)
            ->map(function (TravelDestination $destination) use ($format) {
                $payload = $destination->toSearchArray();

                if ($format === 'airport') {
                    $payload['text'] = $destination->displayLabel('airport');
                } else {
                    $payload['text'] = $destination->displayLabel();
                }

                return $payload;
            })
            ->values()
            ->all();
    }

    protected function shouldUseSerpapiFlightAutocomplete(?string $context): bool
    {
        if (! $this->flightService->isConfigured()) {
            return false;
        }

        return in_array($context, ['flight_from', 'flight_to'], true);
    }

    /**
     * @return array<string, string>
     */
    public function countryOptions(): array
    {
        return TravelDestination::query()
            ->active()
            ->ofType(DestinationType::Country->value)
            ->ordered()
            ->pluck('name', 'name')
            ->all();
    }

    public function logMutation(string $action, TravelDestination $destination, array $properties = []): void
    {
        $this->activityLog->log($action, $destination, $properties);
    }

    public function applyBulkAction(string $action, array $ids): int
    {
        $query = TravelDestination::query()->whereIn('id', $ids);
        $count = $query->count();

        match ($action) {
            'activate' => $query->update(['is_active' => true]),
            'deactivate' => $query->update(['is_active' => false]),
            'delete' => $query->each(fn (TravelDestination $item) => $item->delete()),
            default => $count = 0,
        };

        return $count;
    }
}
