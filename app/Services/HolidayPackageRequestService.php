<?php

namespace App\Services;

use App\Models\HolidayPackageRequest;

class HolidayPackageRequestService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): HolidayPackageRequest
    {
        $childAges = array_values(array_filter(
            array_map('intval', (array) ($data['child_ages'] ?? [])),
            fn ($age) => $age >= 0
        ));

        return HolidayPackageRequest::query()->create([
            'reference_number' => HolidayPackageRequest::generateReference(),
            'status' => 'new',
            'locale' => $data['locale'] ?? app()->getLocale(),
            'departure_airport' => $data['departure_airport'] ?? null,
            'destination' => $data['destination'],
            'earliest_departure_date' => normalize_user_date($data['earliest_departure_date'] ?? null),
            'latest_return_date' => normalize_user_date($data['latest_return_date'] ?? null),
            'duration' => $data['duration'] ?? null,
            'adults' => (int) ($data['adults'] ?? 2),
            'children' => (int) ($data['children'] ?? 0),
            'child_ages' => $childAges,
            'holiday_types' => array_values((array) ($data['holiday_types'] ?? [])),
            'priority' => $data['priority'] ?? null,
            'preferred_contact_method' => $data['preferred_contact_method'] ?? null,
            'gdpr_consent_at' => ! empty($data['gdpr_consent']) ? now() : null,
            'budget_amount' => $data['budget_amount'] ?? null,
            'budget_currency' => $data['budget_currency'] ?? null,
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'country' => $data['country'] ?? null,
            'room_types' => array_values((array) ($data['room_types'] ?? [])),
            'board_types' => array_values((array) ($data['board_types'] ?? [])),
            'preferred_airline' => $data['preferred_airline'] ?? null,
            'travel_class' => $data['travel_class'] ?? null,
            'outbound_time_preference' => $data['outbound_time_preference'] ?? null,
            'return_time_preference' => $data['return_time_preference'] ?? null,
            'direct_flight_only' => (bool) ($data['direct_flight_only'] ?? false),
            'transfer_allowed' => (bool) ($data['transfer_allowed'] ?? false),
            'rail_and_fly' => (bool) ($data['rail_and_fly'] ?? false),
            'hotel_category' => $data['hotel_category'] ?? null,
            'hotel_recommendation' => $data['hotel_recommendation'] ?? null,
            'sea_view' => $data['sea_view'] ?? null,
            'hotel_features' => array_values((array) ($data['hotel_features'] ?? [])),
            'beach_preferences' => array_values((array) ($data['beach_preferences'] ?? [])),
            'sports' => array_values((array) ($data['sports'] ?? [])),
            'wellness' => array_values((array) ($data['wellness'] ?? [])),
            'kids_club' => array_key_exists('kids_club', $data) ? (bool) $data['kids_club'] : null,
            'babysitting' => array_key_exists('babysitting', $data) ? (bool) $data['babysitting'] : null,
            'room_amenities' => array_values((array) ($data['room_amenities'] ?? [])),
            'additional_notes' => $data['additional_notes'] ?? null,
        ]);
    }

    public function updateStatus(HolidayPackageRequest $request, string $status, ?string $agentNotes = null): HolidayPackageRequest
    {
        $request->update([
            'status' => $status,
            'agent_notes' => $agentNotes ?? $request->agent_notes,
        ]);

        return $request->fresh();
    }
}
