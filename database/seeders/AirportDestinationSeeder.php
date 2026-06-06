<?php

namespace Database\Seeders;

use App\Enums\DestinationType;
use Database\Seeders\Concerns\SeedsTravelDestinations;
use Illuminate\Database\Seeder;

class AirportDestinationSeeder extends Seeder
{
    use SeedsTravelDestinations;

    public function run(): void
    {
        $airports = [
            ['name' => 'Zurich Airport', 'code' => 'ZRH', 'city' => 'Zurich', 'country' => 'Switzerland', 'latitude' => 47.464699, 'longitude' => 8.549169],
            ['name' => 'Geneva Airport', 'code' => 'GVA', 'city' => 'Geneva', 'country' => 'Switzerland', 'latitude' => 46.238098, 'longitude' => 6.108950],
            ['name' => 'Paris Charles de Gaulle Airport', 'code' => 'CDG', 'city' => 'Paris', 'country' => 'France', 'latitude' => 49.009690, 'longitude' => 2.547925],
            ['name' => 'London Heathrow Airport', 'code' => 'LHR', 'city' => 'London', 'country' => 'United Kingdom', 'latitude' => 51.470600, 'longitude' => -0.461941],
            ['name' => 'Rome Fiumicino Airport', 'code' => 'FCO', 'city' => 'Rome', 'country' => 'Italy', 'latitude' => 41.800278, 'longitude' => 12.238889],
            ['name' => 'Barcelona El Prat Airport', 'code' => 'BCN', 'city' => 'Barcelona', 'country' => 'Spain', 'latitude' => 41.297078, 'longitude' => 2.078464],
            ['name' => 'Frankfurt Airport', 'code' => 'FRA', 'city' => 'Frankfurt', 'country' => 'Germany', 'latitude' => 50.026421, 'longitude' => 8.543125],
            ['name' => 'Milan Malpensa Airport', 'code' => 'MXP', 'city' => 'Milan', 'country' => 'Italy', 'latitude' => 45.630600, 'longitude' => 8.728111],
        ];

        foreach ($airports as $index => $airport) {
            $this->seedDestination(array_merge($airport, [
                'type' => DestinationType::Airport->value,
                'sort_order' => $index + 1,
            ]));
        }
    }
}
