<?php

namespace Database\Seeders;

use App\Models\Airline;
use Illuminate\Database\Seeder;

class AirlineSeeder extends Seeder
{
    public function run(): void
    {
        $airlines = [
            ['name' => 'Air France', 'code' => 'AF', 'logo' => 'assets/img/flight/airline-1.png', 'aliases' => ['Air France']],
            ['name' => 'Emirates', 'code' => 'EK', 'logo' => 'assets/img/flight/airline-2.png', 'aliases' => ['Emirates Airlines']],
            ['name' => 'Singapore Airlines', 'code' => 'SQ', 'logo' => 'assets/img/flight/airline-3.png', 'aliases' => ['Singapore Air']],
            ['name' => 'Qatar Airways', 'code' => 'QR', 'logo' => 'assets/img/flight/airline-4.png', 'aliases' => ['Qatar']],
            ['name' => 'British Airways', 'code' => 'BA', 'logo' => 'assets/img/flight/airline-5.png', 'aliases' => ['BA']],
            ['name' => 'Lufthansa', 'code' => 'LH', 'logo' => 'assets/img/flight/airline-6.png', 'aliases' => []],
            ['name' => 'Delta Air Lines', 'code' => 'DL', 'logo' => 'assets/img/flight/airline-7.png', 'aliases' => ['Delta', 'Delta Airlines']],
        ];

        foreach ($airlines as $index => $airline) {
            Airline::query()->updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($airline['name'])],
                [
                    'name' => $airline['name'],
                    'code' => $airline['code'],
                    'logo' => $airline['logo'],
                    'aliases' => $airline['aliases'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );
        }
    }
}
