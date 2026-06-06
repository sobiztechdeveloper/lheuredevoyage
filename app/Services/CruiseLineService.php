<?php

namespace App\Services;

use App\Models\CruiseLine;
use Illuminate\Support\Str;

class CruiseLineService
{
    public function __construct(
        protected ActivityLogService $activityLog,
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
            : CruiseLine::generateSlug($data['name']);

        if (array_key_exists('description', $data) && $data['description'] === '') {
            $data['description'] = null;
        }

        return $data;
    }

    /**
     * @return array<string, string>
     */
    public function activeOptions(): array
    {
        return CruiseLine::query()
            ->active()
            ->ordered()
            ->pluck('name', 'name')
            ->all();
    }

    public function logMutation(string $action, CruiseLine $line, array $properties = []): void
    {
        $this->activityLog->log($action, $line, $properties);
    }
}
