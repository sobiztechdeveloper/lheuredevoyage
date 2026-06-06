<?php

namespace Database\Seeders;

use App\Enums\DestinationType;
use Database\Seeders\Concerns\SeedsTravelDestinations;
use Illuminate\Database\Seeder;

class CruiseRegionSeeder extends Seeder
{
    use SeedsTravelDestinations;

    public function run(): void
    {
        $regions = [
            'Mediterranean',
            'Caribbean',
            'Alaska',
            'Northern Europe',
            'Asia',
            'Norwegian Fjords',
        ];

        foreach ($regions as $index => $name) {
            $this->seedDestination([
                'type' => DestinationType::CruiseRegion->value,
                'name' => $name,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
