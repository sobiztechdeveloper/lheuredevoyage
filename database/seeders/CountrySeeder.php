<?php

namespace Database\Seeders;

use App\Enums\DestinationType;
use Database\Seeders\Concerns\SeedsTravelDestinations;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    use SeedsTravelDestinations;

    public function run(): void
    {
        $countries = [
            ['name' => 'Switzerland', 'code' => 'CH', 'sort_order' => 1],
            ['name' => 'France', 'code' => 'FR', 'sort_order' => 2],
            ['name' => 'Germany', 'code' => 'DE', 'sort_order' => 3],
            ['name' => 'Italy', 'code' => 'IT', 'sort_order' => 4],
            ['name' => 'United Kingdom', 'code' => 'GB', 'sort_order' => 5],
            ['name' => 'Spain', 'code' => 'ES', 'sort_order' => 6],
            ['name' => 'United States', 'code' => 'US', 'sort_order' => 7],
            ['name' => 'United Arab Emirates', 'code' => 'AE', 'sort_order' => 8],
        ];

        foreach ($countries as $index => $country) {
            $this->seedDestination([
                'type' => DestinationType::Country->value,
                'name' => $country['name'],
                'code' => $country['code'],
                'sort_order' => $country['sort_order'] ?? ($index + 1),
            ]);
        }
    }
}
