<?php

namespace Database\Seeders;

use App\Models\Master\CruiseCategory;
use App\Models\Master\CruiseFacility;
use App\Models\Master\HotelBeachType;
use App\Models\Master\HotelFacility;
use App\Models\Master\HotelSport;
use App\Models\Master\HotelWellness;
use App\Models\Master\MealPlan;
use App\Models\Master\PackageCategory;
use App\Models\Master\PackageTheme;
use App\Models\Master\RoomFacility;
use App\Models\Master\RoomType;
use App\Models\Master\TravelClass;
use App\Models\Master\VehicleType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TravelMasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedType(HotelFacility::class, [
            'Adults Only', 'Family Friendly', 'Central Location', 'Water Park',
            'Indoor Pool', 'Parking', 'Internet', 'Spa', 'Fitness Centre',
            'Restaurant', 'Pet Friendly', 'Air Conditioning', 'Pool', 'Free WiFi',
        ]);
        $this->seedType(HotelSport::class, [
            'Golf', 'Diving', 'Tennis', 'Hiking', 'Cycling', 'Surfing',
            'Windsurfing', 'Skiing', 'Snowboarding', 'Water Sports', 'Fitness Classes',
        ]);
        $this->seedType(HotelWellness::class, ['Spa', 'Massage', 'Yoga', 'Wellness Centre', 'Sauna']);
        $this->seedType(HotelBeachType::class, [
            'Direct Beach', 'Beach Within 500m', 'Private Beach', 'Sandy Beach',
        ]);
        $this->seedType(RoomType::class, [
            'Apartment / Studio', 'Family Room', 'Villa', 'Suite', 'Double Room',
            'Single Room', 'Deluxe', 'Economy', 'Triple Room',
        ]);
        $this->seedType(RoomFacility::class, [
            'King Bed', 'Queen Bed', 'Balcony', 'Air Conditioning', 'Private Pool',
            'Minibar', 'Safe', 'Internet', 'Non Smoking', 'WiFi',
        ]);
        $this->seedType(MealPlan::class, ['Room Only', 'Breakfast', 'Half Board', 'Full Board', 'All Inclusive']);
        $this->seedType(PackageCategory::class, [
            'Family', 'Honeymoon', 'Luxury', 'Adventure', 'Group Travel', 'Ski',
        ]);
        $this->seedType(PackageTheme::class, [
            'Beach', 'Wellness', 'City Break', 'Cruise', 'Cultural', 'Wildlife',
        ]);
        $this->seedType(TravelClass::class, ['Economy', 'Premium Economy', 'Business', 'First']);
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
