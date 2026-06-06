<?php

namespace App\Enums;

enum DestinationType: string
{
    case Airport = 'airport';
    case City = 'city';
    case Country = 'country';
    case CruisePort = 'cruise_port';
    case CruiseRegion = 'cruise_region';
    case HotelDestination = 'hotel_destination';
    case HolidayDestination = 'holiday_destination';
    case InsuranceRegion = 'insurance_region';

    public function label(): string
    {
        return match ($this) {
            self::Airport => 'Airport',
            self::City => 'City',
            self::Country => 'Country',
            self::CruisePort => 'Cruise Port',
            self::CruiseRegion => 'Cruise Region',
            self::HotelDestination => 'Hotel Destination',
            self::HolidayDestination => 'Holiday Destination',
            self::InsuranceRegion => 'Insurance Region',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }

    /**
     * @return list<string>
     */
    public static function forContext(string $context): array
    {
        return config("destinations.search_contexts.{$context}", []);
    }
}
