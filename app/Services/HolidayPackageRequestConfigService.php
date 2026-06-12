<?php

namespace App\Services;

use App\Models\Airline;
use App\Models\Master\ContactMethod;
use App\Models\Master\HotelBeachType;
use App\Models\Master\HotelCategory;
use App\Models\Master\HotelFacility;
use App\Models\Master\HotelSport;
use App\Models\Master\HotelWellness;
use App\Models\Master\MealPlan;
use App\Models\Master\PackageCategory;
use App\Models\Master\PackageTheme;
use App\Models\Master\RequestPriority;
use App\Models\Master\RoomFacility;
use App\Models\Master\RoomType;
use App\Models\Master\SeaView;
use App\Models\Master\TimePreference;
use App\Models\Master\TravelClass;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

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
        $travelClasses = $this->masterSlugList(TravelClass::class, 'travel classes');
        $roomTypes = $this->masterSlugList(RoomType::class, 'room types');
        $boardTypes = $this->masterSlugList(MealPlan::class, 'meal plans');
        $roomAmenities = $this->masterSlugList(RoomFacility::class, 'room facilities');
        $hotelFeatures = $this->masterSlugList(HotelFacility::class, 'hotel facilities');
        $sports = $this->masterSlugList(HotelSport::class, 'hotel sports');
        $beachPreferences = $this->masterSlugList(HotelBeachType::class, 'hotel beach types');
        $wellness = $this->masterSlugList(HotelWellness::class, 'hotel wellness options');
        $hotelCategories = $this->masterSlugList(HotelCategory::class, 'hotel categories');
        $seaViews = $this->masterSlugList(SeaView::class, 'sea views');
        $timePreferences = $this->masterSlugList(TimePreference::class, 'time preferences');
        $priorities = $this->masterSlugList(RequestPriority::class, 'request priorities');
        $contactMethods = $this->masterSlugList(ContactMethod::class, 'contact methods');
        $airlines = $this->airlineOptions();

        if ($airlines->isEmpty()) {
            Log::warning('Holiday package request: no active airlines available.');
        }

        $this->warnIfConfigurationIncomplete();

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
            'hotel_categories' => $hotelCategories,
            'sea_views' => $seaViews,
            'time_preferences' => $timePreferences,
            'priorities' => $priorities,
            'contact_methods' => $contactMethods,
            'preferred_airlines' => $airlines->pluck('slug')->values()->all(),
            'option_labels' => [
                'holiday_types' => $holidayTypes->pluck('name', 'slug')->all(),
                'travel_classes' => $this->masterLabelMap(TravelClass::class, 'travel classes'),
                'room_types' => $this->masterLabelMap(RoomType::class, 'room types'),
                'board_types' => $this->masterLabelMap(MealPlan::class, 'meal plans'),
                'room_amenities' => $this->masterLabelMap(RoomFacility::class, 'room facilities'),
                'hotel_features' => $this->masterLabelMap(HotelFacility::class, 'hotel facilities'),
                'sports' => $this->masterLabelMap(HotelSport::class, 'hotel sports'),
                'beach_preferences' => $this->masterLabelMap(HotelBeachType::class, 'hotel beach types'),
                'wellness' => $this->masterLabelMap(HotelWellness::class, 'hotel wellness options'),
                'hotel_categories' => $this->masterLabelMap(HotelCategory::class, 'hotel categories'),
                'sea_views' => $this->masterLabelMap(SeaView::class, 'sea views'),
                'time_preferences' => $this->masterLabelMap(TimePreference::class, 'time preferences'),
                'priorities' => $this->masterLabelMap(RequestPriority::class, 'request priorities'),
                'contact_methods' => $this->masterLabelMap(ContactMethod::class, 'contact methods'),
                'preferred_airlines' => $airlines->pluck('name', 'slug')->all(),
            ],
            'airline_options' => $airlines->map(fn ($airline) => [
                'slug' => $airline->slug,
                'name' => $airline->name,
                'code' => $airline->code,
            ])->values()->all(),
            'has_active_priorities' => $this->hasActivePriorities(),
            'has_active_contact_methods' => $this->hasActiveContactMethods(),
            'has_active_airlines' => $this->hasActiveAirlines(),
        ]);
    }

    public function hasActivePriorities(): bool
    {
        return $this->activeSlugs('priorities') !== [];
    }

    public function hasActiveContactMethods(): bool
    {
        return $this->activeSlugs('contact_methods') !== [];
    }

    public function hasActiveAirlines(): bool
    {
        return $this->activeSlugs('preferred_airlines') !== [];
    }

    public function warnIfConfigurationIncomplete(): void
    {
        $missing = [];

        if (! $this->hasActivePriorities()) {
            $missing[] = 'Request Priorities';
        }

        if (! $this->hasActiveContactMethods()) {
            $missing[] = 'Contact Methods';
        }

        if (! $this->hasActiveAirlines()) {
            $missing[] = 'Airlines';
        }

        if ($missing !== []) {
            Log::warning('Holiday Package Request configuration incomplete. Missing: '.implode(', ', $missing));
        }
    }

    /**
     * @return array<int, string>
     */
    public function activeSlugs(string $field): array
    {
        return match ($field) {
            'holiday_types' => $this->holidayTypeOptions()->pluck('slug')->values()->all(),
            'travel_classes' => $this->masterSlugList(TravelClass::class, 'travel classes'),
            'room_types' => $this->masterSlugList(RoomType::class, 'room types'),
            'board_types' => $this->masterSlugList(MealPlan::class, 'meal plans'),
            'room_amenities' => $this->masterSlugList(RoomFacility::class, 'room facilities'),
            'hotel_features' => $this->masterSlugList(HotelFacility::class, 'hotel facilities'),
            'sports' => $this->masterSlugList(HotelSport::class, 'hotel sports'),
            'beach_preferences' => $this->masterSlugList(HotelBeachType::class, 'hotel beach types'),
            'wellness' => $this->masterSlugList(HotelWellness::class, 'hotel wellness options'),
            'hotel_categories' => $this->masterSlugList(HotelCategory::class, 'hotel categories'),
            'sea_views' => $this->masterSlugList(SeaView::class, 'sea views'),
            'time_preferences' => $this->masterSlugList(TimePreference::class, 'time preferences'),
            'priorities' => $this->masterSlugList(RequestPriority::class, 'request priorities'),
            'contact_methods' => $this->masterSlugList(ContactMethod::class, 'contact methods'),
            'preferred_airlines' => $this->airlineOptions()->pluck('slug')->values()->all(),
            default => [],
        };
    }

    /**
     * @param  class-string  $modelClass
     * @return array<int, string>
     */
    private function masterSlugList(string $modelClass, string $fieldLabel): array
    {
        $slugs = $this->masterData->activeOptions($modelClass)->pluck('slug')->values()->all();

        if ($slugs === []) {
            Log::warning("Holiday package request: no active {$fieldLabel} in master data.");
        }

        return $slugs;
    }

    /**
     * @param  class-string  $modelClass
     * @return array<string, string>
     */
    private function masterLabelMap(string $modelClass, string $fieldLabel): array
    {
        $items = $this->masterData->activeOptions($modelClass);

        if ($items->isEmpty()) {
            Log::warning("Holiday package request: no active {$fieldLabel} in master data.");

            return [];
        }

        return $items->pluck('name', 'slug')->all();
    }

    /**
     * @return Collection<int, object{slug: string, name: string, sort_order: int}>
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

        if ($merged->isEmpty()) {
            Log::warning('Holiday package request: no active package categories or themes in master data.');
        }

        return $merged;
    }

    /**
     * @return Collection<int, Airline>
     */
    private function airlineOptions(): Collection
    {
        return Airline::query()->active()->ordered()->get(['slug', 'name', 'code']);
    }
}
