<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TravelDestinationSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CountrySeeder::class,
            CityDestinationSeeder::class,
            AirportDestinationSeeder::class,
            CruiseRegionSeeder::class,
            CruisePortSeeder::class,
            HotelDestinationSeeder::class,
            HolidayDestinationSeeder::class,
            InsuranceRegionSeeder::class,
            CruiseLineSeeder::class,
        ]);
    }
}
