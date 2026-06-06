<?php

namespace Database\Seeders;

use App\Enums\DestinationType;
use Database\Seeders\Concerns\SeedsTravelDestinations;
use Illuminate\Database\Seeder;

class HolidayDestinationSeeder extends Seeder
{
    use SeedsTravelDestinations;

    public function run(): void
    {
        $destinations = [
            ['name' => 'Bali', 'country' => 'Indonesia'],
            ['name' => 'Maldives', 'country' => 'Maldives'],
            ['name' => 'Paris', 'country' => 'France'],
            ['name' => 'Rome', 'country' => 'Italy'],
            ['name' => 'Swiss Alps', 'country' => 'Switzerland', 'region' => 'Alpine'],
            ['name' => 'Dubai', 'country' => 'United Arab Emirates'],
        ];

        foreach ($destinations as $index => $destination) {
            $this->seedDestination(array_merge($destination, [
                'type' => DestinationType::HolidayDestination->value,
                'sort_order' => $index + 1,
            ]));
        }
    }
}
