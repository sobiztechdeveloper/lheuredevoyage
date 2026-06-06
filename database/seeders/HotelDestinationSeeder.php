<?php

namespace Database\Seeders;

use App\Enums\DestinationType;
use Database\Seeders\Concerns\SeedsTravelDestinations;
use Illuminate\Database\Seeder;

class HotelDestinationSeeder extends Seeder
{
    use SeedsTravelDestinations;

    public function run(): void
    {
        $destinations = [
            ['name' => 'Zurich', 'country' => 'Switzerland'],
            ['name' => 'Interlaken', 'country' => 'Switzerland'],
            ['name' => 'Geneva', 'country' => 'Switzerland'],
            ['name' => 'Paris', 'country' => 'France'],
            ['name' => 'Dubai', 'country' => 'United Arab Emirates'],
            ['name' => 'Maldives', 'country' => 'Maldives'],
        ];

        foreach ($destinations as $index => $destination) {
            $this->seedDestination(array_merge($destination, [
                'type' => DestinationType::HotelDestination->value,
                'sort_order' => $index + 1,
            ]));
        }
    }
}
