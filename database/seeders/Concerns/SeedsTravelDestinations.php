<?php

namespace Database\Seeders\Concerns;

use App\Models\TravelDestination;

trait SeedsTravelDestinations
{
    /**
     * @param  array<string, mixed>  $data
     */
    protected function seedDestination(array $data): TravelDestination
    {
        $match = ['type' => $data['type']];

        if (! empty($data['code'])) {
            $match['code'] = $data['code'];
        } else {
            $match['name'] = $data['name'];
        }

        return TravelDestination::query()->updateOrCreate(
            $match,
            array_merge([
                'slug' => TravelDestination::generateSlug($data['name'], $data['type']),
                'is_active' => true,
                'sort_order' => $data['sort_order'] ?? 0,
            ], $data),
        );
    }
}
