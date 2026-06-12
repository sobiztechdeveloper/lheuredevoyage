<?php

use App\Models\Master\ContactMethod;
use App\Models\Master\CruiseCategory;
use App\Models\Master\CruiseFacility;
use App\Models\Master\HotelBeachType;
use App\Models\Master\HotelCategory;
use App\Models\Master\HotelFacility;
use App\Models\Master\HotelSport;
use App\Models\Master\HotelWellness;
use App\Models\Master\InsuranceCoverageType;
use App\Models\Master\InsuranceType;
use App\Models\Master\MealPlan;
use App\Models\Master\PackageCategory;
use App\Models\Master\PackageTheme;
use App\Models\Master\RequestPriority;
use App\Models\Master\RoomFacility;
use App\Models\Master\RoomType;
use App\Models\Master\SeaView;
use App\Models\Master\TimePreference;
use App\Models\Master\TravelClass;
use App\Models\Master\VehicleFeature;
use App\Models\Master\VehicleType;

return [
    'types' => [
        'hotel_facilities' => [
            'route' => 'hotel-facilities',
            'label' => 'Hotel Facilities',
            'model' => HotelFacility::class,
        ],
        'hotel_sports' => [
            'route' => 'hotel-sports',
            'label' => 'Hotel Sports',
            'model' => HotelSport::class,
        ],
        'hotel_wellness' => [
            'route' => 'hotel-wellness',
            'label' => 'Hotel Wellness',
            'model' => HotelWellness::class,
        ],
        'hotel_beach_types' => [
            'route' => 'hotel-beach-types',
            'label' => 'Hotel Beach Types',
            'model' => HotelBeachType::class,
        ],
        'room_types' => [
            'route' => 'room-types',
            'label' => 'Room Types',
            'model' => RoomType::class,
        ],
        'room_facilities' => [
            'route' => 'room-facilities',
            'label' => 'Room Facilities',
            'model' => RoomFacility::class,
        ],
        'meal_plans' => [
            'route' => 'meal-plans',
            'label' => 'Meal Plans',
            'model' => MealPlan::class,
        ],
        'package_categories' => [
            'route' => 'package-categories',
            'label' => 'Package Categories',
            'model' => PackageCategory::class,
        ],
        'package_themes' => [
            'route' => 'package-themes',
            'label' => 'Package Themes',
            'model' => PackageTheme::class,
        ],
        'cruise_categories' => [
            'route' => 'cruise-categories',
            'label' => 'Cruise Categories',
            'model' => CruiseCategory::class,
        ],
        'cruise_facilities' => [
            'route' => 'cruise-facilities',
            'label' => 'Cruise Facilities',
            'model' => CruiseFacility::class,
        ],
        'vehicle_types' => [
            'route' => 'vehicle-types',
            'label' => 'Vehicle Types',
            'model' => VehicleType::class,
        ],
        'vehicle_features' => [
            'route' => 'vehicle-features',
            'label' => 'Vehicle Features',
            'model' => VehicleFeature::class,
        ],
        'insurance_types' => [
            'route' => 'insurance-types',
            'label' => 'Insurance Types',
            'model' => InsuranceType::class,
        ],
        'insurance_coverage_types' => [
            'route' => 'insurance-coverage-types',
            'label' => 'Insurance Coverage Types',
            'model' => InsuranceCoverageType::class,
        ],
        'travel_classes' => [
            'route' => 'travel-classes',
            'label' => 'Travel Classes',
            'model' => TravelClass::class,
        ],
        'hotel_categories' => [
            'route' => 'hotel-categories',
            'label' => 'Hotel Categories',
            'model' => HotelCategory::class,
        ],
        'sea_views' => [
            'route' => 'sea-views',
            'label' => 'Sea Views',
            'model' => SeaView::class,
        ],
        'time_preferences' => [
            'route' => 'time-preferences',
            'label' => 'Time Preferences',
            'model' => TimePreference::class,
        ],
        'request_priorities' => [
            'route' => 'request-priorities',
            'label' => 'Request Priorities',
            'model' => RequestPriority::class,
        ],
        'contact_methods' => [
            'route' => 'contact-methods',
            'label' => 'Contact Methods',
            'model' => ContactMethod::class,
        ],
    ],

    'holiday_request' => [
        'holiday_types' => [
            PackageCategory::class,
            PackageTheme::class,
        ],
        'travel_classes' => TravelClass::class,
        'room_types' => RoomType::class,
        'board_types' => MealPlan::class,
        'room_amenities' => RoomFacility::class,
        'hotel_features' => HotelFacility::class,
        'sports' => HotelSport::class,
        'beach_preferences' => HotelBeachType::class,
        'wellness' => HotelWellness::class,
        'hotel_categories' => HotelCategory::class,
        'sea_views' => SeaView::class,
        'time_preferences' => TimePreference::class,
        'priorities' => RequestPriority::class,
        'contact_methods' => ContactMethod::class,
        'preferred_airlines' => \App\Models\Airline::class,
    ],

    'catalog' => [
        'hotel' => [
            'facilities' => ['model' => HotelFacility::class, 'param' => 'facilities', 'label' => 'Facilities'],
            'sports' => ['model' => HotelSport::class, 'param' => 'sports', 'label' => 'Sports'],
            'wellnesses' => ['model' => HotelWellness::class, 'param' => 'wellnesses', 'label' => 'Wellness'],
            'beachTypes' => ['model' => HotelBeachType::class, 'param' => 'beach_types', 'label' => 'Beach Types'],
            'roomTypes' => ['model' => RoomType::class, 'param' => 'room_types', 'label' => 'Room Types'],
            'roomFacilities' => ['model' => RoomFacility::class, 'param' => 'room_facilities', 'label' => 'Room Facilities'],
            'mealPlans' => ['model' => MealPlan::class, 'param' => 'meal_plans', 'label' => 'Meal Plans'],
        ],
        'tourpackage' => [
            'categories' => ['model' => PackageCategory::class, 'param' => 'categories', 'label' => 'Categories'],
            'themes' => ['model' => PackageTheme::class, 'param' => 'themes', 'label' => 'Themes'],
        ],
        'cruise' => [
            'categories' => ['model' => CruiseCategory::class, 'param' => 'categories', 'label' => 'Categories'],
            'facilities' => ['model' => CruiseFacility::class, 'param' => 'facilities', 'label' => 'Facilities'],
        ],
        'rentalcar' => [
            'vehicleTypes' => ['model' => VehicleType::class, 'param' => 'vehicle_types', 'label' => 'Vehicle Types'],
            'vehicleFeatures' => ['model' => VehicleFeature::class, 'param' => 'vehicle_features', 'label' => 'Vehicle Features'],
        ],
        'travelinsurance' => [
            'insuranceTypes' => ['model' => InsuranceType::class, 'param' => 'insurance_types', 'label' => 'Insurance Types'],
            'coverageTypes' => ['model' => InsuranceCoverageType::class, 'param' => 'coverage_types', 'label' => 'Coverage Types'],
        ],
    ],
];
