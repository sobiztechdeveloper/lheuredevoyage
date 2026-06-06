<?php

namespace App\Support;

use App\Models\Cruise;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\RentalCar;
use App\Models\TourPackage;
use App\Models\TravelInsurance;
use Carbon\Carbon;

class BookingWizardSummary
{
    /**
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
    public static function forCar(RentalCar $car, array $context): array
    {
        $pickup = $context['pickup_date'] ?? null;
        $return = $context['return_date'] ?? null;

        return [
            'title' => 'Vehicle Summary',
            'image' => $car->image_url,
            'headline' => $car->name ?: $car->title,
            'subtitle' => $car->location,
            'meta' => array_filter([
                'Pickup' => $pickup instanceof Carbon ? $pickup->format('D, M d, Y') : $pickup,
                'Return' => $return instanceof Carbon ? $return->format('D, M d, Y') : $return,
            ]),
            'fare' => strtoupper($context['currency'] ?? 'CHF').' '.number_format((float) ($context['estimated_amount'] ?? 0), 0),
            'footnote' => 'Final rate confirmed by our consultant. No payment required now.',
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
    public static function forCruise(?Cruise $cruise, array $context = []): array
    {
        if (! $cruise?->exists) {
            return [
                'title' => 'Cruise Summary',
                'headline' => 'Select your cruise',
                'footnote' => 'Personalised quote — no payment taken online.',
            ];
        }

        $departure = $context['departure_date'] ?? null;
        $return = $context['return_date'] ?? null;
        $meta = array_filter([
            'Cruise line' => $cruise->cruise_line,
            'Ship' => $cruise->ship_name,
            'Route' => ($cruise->departure_port && $cruise->arrival_port)
                ? $cruise->departure_port.' → '.$cruise->arrival_port
                : null,
            'Duration' => ($cruise->duration_nights ?? $cruise->duration_days)
                ? ($cruise->duration_nights ?? $cruise->duration_days).' nights'
                : null,
            'Sailing from' => $departure instanceof Carbon ? $departure->format('D, M d, Y') : null,
            'Sailing to' => $return instanceof Carbon ? $return->format('D, M d, Y') : null,
            'Guests' => isset($context['adults'])
                ? ($context['adults'].' adult(s), '.($context['children'] ?? 0).' child(ren), '.($context['infants'] ?? 0).' infant(s)')
                : null,
        ]);

        return [
            'title' => 'Cruise Summary',
            'image' => str_contains($cruise->image_url, 'logo.png') ? null : $cruise->image_url,
            'headline' => $cruise->displayName(),
            'subtitle' => $cruise->regionLabel(),
            'meta' => $meta,
            'fare' => $cruise->startingPriceDisplay(),
            'fare_label' => 'From',
            'footnote' => 'Personalised quote — no payment taken online.',
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
    public static function forHotel(Hotel $hotel, ?HotelRoom $room, array $context): array
    {
        $occupancy = ($context['adults'] ?? 1).' adult(s)';
        if (! empty($context['children'])) {
            $occupancy .= ', '.$context['children'].' child(ren)';
        }
        if (! empty($context['infants'])) {
            $occupancy .= ', '.$context['infants'].' infant(s)';
        }

        return [
            'title' => 'Stay Summary',
            'image' => $hotel->image_url,
            'headline' => $hotel->name,
            'subtitle' => $hotel->location,
            'meta' => array_filter([
                'Room' => $room?->name,
                'Check-in' => ($context['check_in'] ?? null) instanceof Carbon
                    ? $context['check_in']->format('D, M d, Y') : null,
                'Check-out' => ($context['check_out'] ?? null) instanceof Carbon
                    ? $context['check_out']->format('D, M d, Y') : null,
                'Nights' => isset($context['nights']) ? $context['nights'].' night(s)' : null,
                'Rooms' => isset($context['rooms']) ? $context['rooms'].' room(s)' : null,
                'Guests' => $occupancy,
            ]),
            'fare' => strtoupper($context['currency'] ?? 'CHF').' '.number_format((float) ($context['estimated_amount'] ?? 0), 0),
            'footnote' => 'Final price confirmed by our consultant. No payment required now.',
        ];
    }

    /**
     * @param  array{adult: int, children: int, infant: int}  $travelers
     * @return array<string, mixed>
     */
    public static function forTourPackage(TourPackage $package, array $travelers): array
    {
        $occupancy = $travelers['adult'].' adult(s)';
        if ($travelers['children']) {
            $occupancy .= ', '.$travelers['children'].' child(ren)';
        }
        if ($travelers['infant']) {
            $occupancy .= ', '.$travelers['infant'].' infant(s)';
        }

        return [
            'title' => 'Package Summary',
            'image' => $package->image_url,
            'headline' => $package->title,
            'subtitle' => $package->displayCountry(),
            'meta' => array_filter([
                'Duration' => $package->displayDuration(),
                'Holiday type' => $package->holidayTypeLabel(),
                'Travelers' => $occupancy,
            ]),
            'fare' => $package->formatted_price,
            'fare_label' => 'From',
            'footnote' => 'Final price confirmed by our consultant. No payment required now.',
        ];
    }

    /**
     * @param  array<string, mixed>  $searchDefaults
     * @return array<string, mixed>
     */
    public static function forInsurance(?TravelInsurance $plan, array $searchDefaults = []): array
    {
        if (! $plan) {
            return [
                'title' => 'Insurance Quote',
                'headline' => 'Select a plan',
                'footnote' => 'Compare plans and request a personalised quote.',
            ];
        }

        return [
            'title' => 'Plan Summary',
            'image' => method_exists($plan, 'featuredImageUrl') ? $plan->featuredImageUrl() : $plan->image_url,
            'headline' => $plan->displayPlanName(),
            'subtitle' => $plan->displayCompany(),
            'meta' => array_filter([
                'Plan type' => method_exists($plan, 'planTypeLabel') ? $plan->planTypeLabel() : null,
                'Destination' => $searchDefaults['destination_country'] ?? null,
                'Departure' => ! empty($searchDefaults['travel_start'])
                    ? Carbon::parse($searchDefaults['travel_start'])->format('D, M d, Y') : null,
                'Return' => ! empty($searchDefaults['travel_end'])
                    ? Carbon::parse($searchDefaults['travel_end'])->format('D, M d, Y') : null,
                'Travelers' => isset($searchDefaults['travelers_count'])
                    ? $searchDefaults['travelers_count'].' traveler(s)' : null,
            ]),
            'fare' => method_exists($plan, 'displayPremium') ? $plan->displayPremium() : $plan->formatted_price,
            'fare_label' => 'From',
            'footnote' => 'Quote request only — no payment online.',
        ];
    }
}
