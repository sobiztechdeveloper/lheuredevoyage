<?php

namespace Database\Seeders;

use App\Enums\DestinationType;
use Database\Seeders\Concerns\SeedsTravelDestinations;
use Illuminate\Database\Seeder;

class InsuranceRegionSeeder extends Seeder
{
    use SeedsTravelDestinations;

    public function run(): void
    {
        $regions = [
            'Worldwide',
            'Europe',
            'Schengen',
            'Asia',
            'North America',
        ];

        foreach ($regions as $index => $name) {
            $this->seedDestination([
                'type' => DestinationType::InsuranceRegion->value,
                'name' => $name,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
