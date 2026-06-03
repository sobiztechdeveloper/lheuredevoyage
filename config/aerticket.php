<?php

return [

    'enabled' => env('AERTICKET_ENABLED', false),

    'api_url' => rtrim(env('AERTICKET_API_URL', ''), '/'),

    'access_key' => env('AERTICKET_ACCESS_KEY'),

    'username' => env('AERTICKET_USERNAME'),

    'password' => env('AERTICKET_PASSWORD'),

    'agency_code' => env('AERTICKET_AGENCY_CODE'),

    'timeout' => (int) env('AERTICKET_TIMEOUT', 60),

    'connect_timeout' => (int) env('AERTICKET_CONNECT_TIMEOUT', 15),

    'token_cache_ttl' => (int) env('AERTICKET_TOKEN_CACHE_TTL', 3300),

    'log_requests' => env('AERTICKET_LOG_REQUESTS', true),

    'log_channel' => env('AERTICKET_LOG_CHANNEL'),

    /*
    |--------------------------------------------------------------------------
    | Cockpit API endpoints (relative to api_url)
    | Adjust after AERTiCKET delivers your integration specification.
    |--------------------------------------------------------------------------
    */
    'endpoints' => [
        'authenticate' => env('AERTICKET_ENDPOINT_AUTH', '/api/v1/authenticate'),
        'search' => env('AERTICKET_ENDPOINT_SEARCH', '/api/v1/offers/search'),
        'offer_detail' => env('AERTICKET_ENDPOINT_OFFER_DETAIL', '/api/v1/offers/{offerId}'),
        'fare_rules' => env('AERTICKET_ENDPOINT_FARE_RULES', '/api/v1/offers/{offerId}/fare-rules'),
        'availability' => env('AERTICKET_ENDPOINT_AVAILABILITY', '/api/v1/offers/{offerId}/availability'),
        'book' => env('AERTICKET_ENDPOINT_BOOK', '/api/v1/bookings'),
        'ticket' => env('AERTICKET_ENDPOINT_TICKET', '/api/v1/bookings/{bookingId}/ticket'),
    ],

];
