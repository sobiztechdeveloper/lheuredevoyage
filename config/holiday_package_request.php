<?php

/**
 * Static settings and fallbacks for holiday package requests.
 * Option lists for Holiday Type, Travel Class, Airlines, Room Type, Board Type,
 * Room Amenities, Hotel Features, Sports, Beach, and Wellness are loaded from
 * admin Master Data via HolidayPackageRequestConfigService.
 */
return [

    'admin_email' => env('HOLIDAY_REQUEST_EMAIL'),

    'statuses' => [
        'new',
        'contacted',
        'quoted',
        'booked',
        'closed',
    ],

    'budget_currencies' => ['CHF', 'USD', 'EUR'],

    'priorities' => [
        'normal',
        'important',
        'vip',
    ],

    'contact_methods' => [
        'email',
        'phone',
        'whatsapp',
    ],

    'time_preferences' => [
        'morning',
        'afternoon',
        'evening',
        'night',
        'flexible',
    ],

    'hotel_categories' => [
        '3_star',
        '4_star',
        '5_star',
        'luxury',
    ],

    'hotel_recommendations' => ['70', '80', '85', '90', '95', '100'],

    'sea_views' => [
        'direct',
        'side_sea_view',
        'sea_view',
    ],

    'fallback_holiday_types' => [
        'Family',
        'Honeymoon',
        'Luxury',
        'Beach',
        'Adventure',
        'Wellness',
        'Ski',
        'City Break',
        'Cruise',
        'Group Travel',
    ],

    // Fallback slugs when master data tables are empty (seeded on install).
    'travel_classes' => [
        'economy',
        'premium_economy',
        'business',
        'first',
    ],

    'room_types' => [
        'apartment_studio',
        'family_room',
        'villa',
        'suite',
        'double_room',
        'single_room',
        'deluxe',
        'economy',
        'triple_room',
    ],

    'board_types' => [
        'room_only',
        'breakfast',
        'half_board',
        'full_board',
        'all_inclusive',
    ],

    'hotel_features' => [
        'adults_only',
        'family_friendly',
        'central_location',
        'water_park',
        'indoor_pool',
        'parking',
        'internet',
        'spa',
        'fitness_centre',
    ],

    'beach_preferences' => [
        'direct_beach',
        'beach_within_500m',
        'private_beach',
        'sandy_beach',
    ],

    'sports' => [
        'golf',
        'diving',
        'tennis',
        'hiking',
        'cycling',
        'surfing',
        'windsurfing',
        'skiing',
        'snowboarding',
    ],

    'wellness' => [
        'spa',
        'massage',
        'yoga',
        'wellness_centre',
    ],

    'room_amenities' => [
        'king_bed',
        'queen_bed',
        'balcony',
        'air_conditioning',
        'private_pool',
        'minibar',
        'safe',
        'internet',
        'non_smoking',
    ],

];
