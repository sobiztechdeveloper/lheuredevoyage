<?php

namespace Database\Seeders;

use App\Models\Master\CruiseCategory;
use App\Models\Master\CruiseFacility;
use App\Models\Master\HotelFacility;
use App\Models\Master\HotelSport;
use App\Models\Master\HotelWellness;
use App\Models\Master\MealPlan;
use App\Models\Master\PackageCategory;
use App\Models\Master\PackageTheme;
use App\Models\Master\RoomType;
use App\Models\Master\VehicleType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TravelMasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedType(HotelFacility::class, ['Parking', 'Restaurant', 'Pet Friendly', 'Air Conditioning', 'Pool', 'Free WiFi']);
        $this->seedType(HotelSport::class, ['Tennis', 'Golf', 'Water Sports', 'Fitness Classes']);
        $this->seedType(HotelWellness::class, ['Spa', 'Sauna', 'Massage', 'Yoga']);
        $this->seedType(RoomType::class, ['Single', 'Double', 'Deluxe', 'Suite']);
        $this->seedType(MealPlan::class, ['Room Only', 'Breakfast', 'Half Board', 'Full Board', 'All Inclusive']);
        $this->seedType(PackageCategory::class, ['Adventure', 'Family', 'Luxury', 'Honeymoon']);
        $this->seedType(PackageTheme::class, ['Beach', 'Cultural', 'Wildlife', 'City Break']);
        $this->seedType(CruiseCategory::class, ['Family', 'Luxury', 'River', 'Expedition']);
        $this->seedType(CruiseFacility::class, ['Casino', 'Theatre', 'Kids Club', 'Fine Dining']);
        $this->seedType(VehicleType::class, ['SUV', 'Sedan', 'Convertible', 'Van']);
    }

    /**
     * @param  class-string  $modelClass
     * @param  array<int, string>  $names
     */
    private function seedType(string $modelClass, array $names): void
    {
        foreach ($names as $index => $name) {
            $modelClass::query()->firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'sort_order' => $index + 1, 'is_active' => true],
            );
        }
    }
}
