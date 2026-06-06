<?php

namespace Database\Seeders;

use App\Enums\DestinationType;
use Database\Seeders\Concerns\SeedsTravelDestinations;
use Illuminate\Database\Seeder;

class CityDestinationSeeder extends Seeder
{
    use SeedsTravelDestinations;

    public function run(): void
    {
        $cities = [
            ['name' => 'Zurich', 'country' => 'Switzerland'],
            ['name' => 'Geneva', 'country' => 'Switzerland'],
            ['name' => 'Bern', 'country' => 'Switzerland'],
            ['name' => 'Lausanne', 'country' => 'Switzerland'],
            ['name' => 'Paris', 'country' => 'France'],
            ['name' => 'Rome', 'country' => 'Italy'],
            ['name' => 'London', 'country' => 'United Kingdom'],
            ['name' => 'Barcelona', 'country' => 'Spain'],
            ['name' => 'Dubai', 'country' => 'United Arab Emirates'],
        ];

        foreach ($cities as $index => $city) {
            $this->seedDestination([
                'type' => DestinationType::City->value,
                'name' => $city['name'],
                'country' => $city['country'],
                'sort_order' => $index + 1,
            ]);
        }
    }
}
