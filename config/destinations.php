<?php

use App\Enums\DestinationType;

return [
    'search_min_chars' => 2,
    'search_limit' => 20,
    'suggest_limit' => 15,

    'search_contexts' => [
        'flight_from' => [DestinationType::Airport->value],
        'flight_to' => [DestinationType::Airport->value],
        'car_pickup' => [DestinationType::Airport->value, DestinationType::City->value],
        'car_dropoff' => [DestinationType::Airport->value, DestinationType::City->value],
        'cruise' => [DestinationType::CruiseRegion->value, DestinationType::CruisePort->value],
        'hotel' => [DestinationType::HotelDestination->value, DestinationType::City->value],
        'insurance' => [DestinationType::Country->value, DestinationType::InsuranceRegion->value],
        'holiday' => [DestinationType::HolidayDestination->value, DestinationType::Country->value, DestinationType::City->value],
        'holiday_country' => [DestinationType::Country->value],
    ],

    'import_mappings' => [
        'airports' => [
            'type' => DestinationType::Airport->value,
            'columns' => [
                'name' => ['name', 'airport_name', 'airport name'],
                'code' => ['iata', 'iata_code', 'iata code', 'code'],
                'icao' => ['icao', 'icao_code', 'icao code', 'gps_code'],
                'city' => ['city', 'municipality', 'city_name'],
                'country' => ['country', 'iso_country', 'country_name'],
                'latitude' => ['latitude', 'latitude_deg', 'lat'],
                'longitude' => ['longitude', 'longitude_deg', 'lng', 'lon'],
            ],
        ],
        'cities' => [
            'type' => DestinationType::City->value,
            'columns' => [
                'name' => ['name', 'city', 'city_name'],
                'code' => ['code', 'city_code'],
                'country' => ['country', 'country_name'],
                'region' => ['region', 'state', 'province'],
                'latitude' => ['latitude', 'lat'],
                'longitude' => ['longitude', 'lng', 'lon'],
            ],
        ],
        'cruise_ports' => [
            'type' => DestinationType::CruisePort->value,
            'columns' => [
                'name' => ['name', 'port', 'port_name'],
                'code' => ['code', 'port_code'],
                'city' => ['city', 'city_name'],
                'country' => ['country', 'country_name'],
                'latitude' => ['latitude', 'lat'],
                'longitude' => ['longitude', 'lng', 'lon'],
            ],
        ],
    ],
];
