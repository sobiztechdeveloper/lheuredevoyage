<?php

namespace App\Services;

use App\Models\Airline;
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
use Illuminate\Support\Collection;

class HolidayPackageRequestConfigService
{
    public function __construct(
        protected MasterDataService $masterData,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        $static = config('holiday_package_request');
        $static = is_array($static) ? $static : [];

        $holidayTypes = $this->holidayTypeOptions();
        $travelClasses = $this->masterSlugList(TravelClass::class, $static['travel_classes'] ?? []);
        $roomTypes = $this->masterSlugList(RoomType::class, $static['room_types'] ?? []);
        $boardTypes = $this->masterSlugList(MealPlan::class, $static['board_types'] ?? []);
        $roomAmenities = $this->masterSlugList(RoomFacility::class, $static['room_amenities'] ?? []);
        $hotelFeatures = $this->masterSlugList(HotelFacility::class, $static['hotel_features'] ?? []);
        $sports = $this->masterSlugList(HotelSport::class, $static['sports'] ?? []);
        $beachPreferences = $this->masterSlugList(HotelBeachType::class, $static['beach_preferences'] ?? []);
        $wellness = $this->masterSlugList(HotelWellness::class, $static['wellness'] ?? []);
        $airlines = $this->airlineOptions();

        return array_merge($static, [
            'holiday_types' => $holidayTypes->pluck('slug')->values()->all(),
            'travel_classes' => $travelClasses,
            'room_types' => $roomTypes,
            'board_types' => $boardTypes,
            'room_amenities' => $roomAmenities,
            'hotel_features' => $hotelFeatures,
            'sports' => $sports,
            'beach_preferences' => $beachPreferences,
            'wellness' => $wellness,
            'preferred_airlines' => $airlines->pluck('slug')->values()->all(),
            'option_labels' => [
                'holiday_types' => $holidayTypes->pluck('name', 'slug')->all(),
                'travel_classes' => $this->masterLabelMap(TravelClass::class, $static['travel_classes'] ?? []),
                'room_types' => $this->masterLabelMap(RoomType::class, $static['room_types'] ?? []),
                'board_types' => $this->masterLabelMap(MealPlan::class, $static['board_types'] ?? []),
                'room_amenities' => $this->masterLabelMap(RoomFacility::class, $static['room_amenities'] ?? []),
                'hotel_features' => $this->masterLabelMap(HotelFacility::class, $static['hotel_features'] ?? []),
                'sports' => $this->masterLabelMap(HotelSport::class, $static['sports'] ?? []),
                'beach_preferences' => $this->masterLabelMap(HotelBeachType::class, $static['beach_preferences'] ?? []),
                'wellness' => $this->masterLabelMap(HotelWellness::class, $static['wellness'] ?? []),
                'preferred_airlines' => $airlines->pluck('name', 'slug')->all(),
            ],
            'airline_options' => $airlines->map(fn ($airline) => [
                'slug' => $airline->slug,
                'name' => $airline->name,
                'code' => $airline->code,
            ])->values()->all(),
        ]);
    }

    /**
     * @param  class-string  $modelClass
     * @param  array<int, string>  $fallback
     * @return array<int, string>
     */
    private function masterSlugList(string $modelClass, array $fallback): array
    {
        $slugs = $this->masterData->activeOptions($modelClass)->pluck('slug')->values()->all();

        return $slugs !== [] ? $slugs : $fallback;
    }

    /**
     * @param  class-string  $modelClass
     * @param  array<int, string>  $fallbackSlugs
     * @return array<string, string>
     */
    private function masterLabelMap(string $modelClass, array $fallbackSlugs): array
    {
        $items = $this->masterData->activeOptions($modelClass);

        if ($items->isNotEmpty()) {
            return $items->pluck('name', 'slug')->all();
        }

        return collect($fallbackSlugs)
            ->mapWithKeys(fn (string $slug) => [$slug => str_replace('_', ' ', ucwords($slug, '_'))])
            ->all();
    }

    /**
     * @return Collection<int, object{slug: string, name: string}>
     */
    private function holidayTypeOptions(): Collection
    {
        $categories = $this->masterData->activeOptions(PackageCategory::class);
        $themes = $this->masterData->activeOptions(PackageTheme::class);

        $merged = $categories
            ->concat($themes)
            ->unique('slug')
            ->sortBy('sort_order')
            ->values();

        if ($merged->isNotEmpty()) {
            return $merged;
        }

        $fallback = config('holiday_package_request.fallback_holiday_types', []);

        return collect($fallback)->map(fn (string $name) => (object) [
            'slug' => \Illuminate\Support\Str::slug($name),
            'name' => $name,
        ]);
    }

    /**
     * @return Collection<int, Airline>
     */
    private function airlineOptions(): Collection
    {
        return Airline::query()->active()->ordered()->get(['slug', 'name', 'code']);
    }
}
