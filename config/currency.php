<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Display currency (site header selector)
    |--------------------------------------------------------------------------
    */
    'default' => env('DISPLAY_CURRENCY', 'CHF'),

    'supported' => ['CHF', 'USD'],

    /*
    |--------------------------------------------------------------------------
    | Source currencies for stored/API data
    |--------------------------------------------------------------------------
    */
    'sources' => [
        'catalog' => env('CATALOG_CURRENCY', 'CHF'),
        'serpapi' => env('SERPAPI_CURRENCY', 'USD'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Exchange rates (multiply amount in "from" to get "to")
    |--------------------------------------------------------------------------
    */
    'rates' => [
        'USD' => [
            'CHF' => (float) env('CURRENCY_USD_TO_CHF', 0.90),
        ],
        'CHF' => [
            'USD' => (float) env('CURRENCY_CHF_TO_USD', 1.11),
        ],
    ],

    'cookie' => 'display_currency',
    'session_key' => 'display_currency',

];
