<?php

namespace Database\Seeders;

use App\Enums\DestinationType;
use Database\Seeders\Concerns\SeedsTravelDestinations;
use Illuminate\Database\Seeder;

class CruisePortSeeder extends Seeder
{
    use SeedsTravelDestinations;

    public function run(): void
    {
        $ports = [
            ['name' => 'Barcelona Port', 'city' => 'Barcelona', 'country' => 'Spain'],
            ['name' => 'Miami Port', 'city' => 'Miami', 'country' => 'United States'],
            ['name' => 'Southampton Port', 'city' => 'Southampton', 'country' => 'United Kingdom'],
            ['name' => 'Venice Port', 'city' => 'Venice', 'country' => 'Italy'],
            ['name' => 'Genoa Port', 'city' => 'Genoa', 'country' => 'Italy'],
        ];

        foreach ($ports as $index => $port) {
            $this->seedDestination(array_merge($port, [
                'type' => DestinationType::CruisePort->value,
                'sort_order' => $index + 1,
            ]));
        }
    }
}
