<?php

/**
 * Non-option settings for holiday package requests.
 * Business option lists are loaded from Admin Master Data via HolidayPackageRequestConfigService.
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

    'hotel_recommendations' => ['70', '80', '85', '90', '95', '100'],

];
