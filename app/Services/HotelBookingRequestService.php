<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\HotelBookingGuest;
use App\Models\HotelBookingRequest;
use App\Models\HotelBookingRequestStatusHistory;
use App\Models\HotelRoom;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HotelBookingRequestService
{
    /**
     * @return list<array{key: string, type: string, label: string}>
     */
    public function guestSlots(int $adults, int $children, int $infants): array
    {
        $slots = [];
        for ($i = 1; $i <= max(0, $adults); $i++) {
            $slots[] = ['key' => 'adult_'.$i, 'type' => 'adult', 'label' => 'Adult '.$i];
        }
        for ($i = 1; $i <= max(0, $children); $i++) {
            $slots[] = ['key' => 'child_'.$i, 'type' => 'child', 'label' => 'Child '.$i];
        }
        for ($i = 1; $i <= max(0, $infants); $i++) {
            $slots[] = ['key' => 'infant_'.$i, 'type' => 'infant', 'label' => 'Infant '.$i];
        }

        if ($slots === []) {
            $slots[] = ['key' => 'adult_1', 'type' => 'adult', 'label' => 'Adult 1'];
        }

        return $slots;
    }

    /**
     * @return array<string, mixed>
     */
    public function buildBookingContext(Hotel $hotel, ?HotelRoom $room, array $searchParams): array
    {
        $checkIn = $this->parseDate($searchParams['check_in'] ?? $searchParams['journey-date'] ?? null);
        $checkOut = $this->parseDate($searchParams['check_out'] ?? $searchParams['return-date'] ?? null);
        $rooms = max(1, (int) ($searchParams['rooms'] ?? $searchParams['room'] ?? 1));
        $adults = max(1, (int) ($searchParams['adults'] ?? $searchParams['adult'] ?? 2));
        $children = max(0, (int) ($searchParams['children'] ?? 0));
        $infants = max(0, (int) ($searchParams['infants'] ?? $searchParams['infant'] ?? 0));

        if (! $checkIn) {
            $checkIn = now()->addDays(7)->startOfDay();
        }
        if (! $checkOut || $checkOut->lte($checkIn)) {
            $checkOut = $checkIn->copy()->addDay();
        }

        $nights = max(1, (int) $checkIn->diffInDays($checkOut));
        $unitPrice = $room ? (float) $room->price : (float) $hotel->price;
        $estimated = round($unitPrice * $nights * $rooms, 2);

        return [
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'rooms' => $rooms,
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
            'nights' => $nights,
            'estimated_amount' => $estimated,
            'currency' => $room?->currency ?? 'USD',
            'guest_slots' => $this->guestSlots($adults, $children, $infants),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(Hotel $hotel, ?HotelRoom $room, array $data): HotelBookingRequest
    {
        $options = [];
        foreach (array_keys(HotelBookingRequest::SPECIAL_REQUEST_OPTIONS) as $key) {
            if (! empty($data['special_request_options'][$key])) {
                $options[$key] = true;
            }
        }

        if (! empty($data['booking_for'])) {
            $options['booking_for'] = $data['booking_for'];
        }

        $leadName = trim(
            ($data['lead_guest_title'] ?? '').' '
            .$data['lead_guest_first_name'].' '
            .$data['lead_guest_last_name']
        );

        return DB::transaction(function () use ($hotel, $room, $data, $options, $leadName) {
            $booking = HotelBookingRequest::query()->create([
                'reference_number' => HotelBookingRequest::generateReference(),
                'customer_id' => auth()->id(),
                'hotel_id' => $hotel->id,
                'room_id' => $room?->id,
                'status' => 'new',
                'check_in_date' => $data['check_in_date'],
                'check_out_date' => $data['check_out_date'],
                'rooms' => $data['rooms'],
                'adults' => $data['adults'],
                'children' => $data['children'],
                'infants' => $data['infants'],
                'lead_guest_title' => $data['lead_guest_title'] ?? null,
                'lead_guest_name' => $leadName,
                'lead_guest_email' => $data['lead_guest_email'],
                'lead_guest_phone' => $data['lead_guest_phone'],
                'lead_guest_whatsapp' => $data['lead_guest_whatsapp'] ?? null,
                'country' => $data['country'] ?? null,
                'bed_preference' => $data['bed_preference'] ?? 'no_preference',
                'smoking_preference' => $data['smoking_preference'] ?? 'no_preference',
                'arrival_time' => $data['arrival_time'] ?? null,
                'special_request_options' => $options !== [] ? $options : null,
                'special_requests' => $data['special_requests'] ?? null,
                'selected_hotel' => [
                    'id' => $hotel->id,
                    'name' => $hotel->name,
                    'location' => $hotel->location,
                    'slug' => $hotel->slug,
                    'stars' => $hotel->starCount(),
                ],
                'selected_room' => $room ? [
                    'id' => $room->id,
                    'name' => $room->name,
                    'room_type' => $room->room_type,
                    'bed_type' => $room->bed_type,
                    'meal_plan' => $room->meal_plan,
                    'price' => $room->price,
                ] : null,
                'estimated_amount' => $data['estimated_amount'],
                'currency' => $data['currency'] ?? 'USD',
                'created_by' => auth()->id(),
            ]);

            HotelBookingGuest::query()->create([
                'hotel_booking_request_id' => $booking->id,
                'title' => $data['lead_guest_title'] ?? null,
                'first_name' => $data['lead_guest_first_name'],
                'last_name' => $data['lead_guest_last_name'],
                'guest_type' => 'adult',
            ]);

            HotelBookingRequestStatusHistory::query()->create([
                'hotel_booking_request_id' => $booking->id,
                'old_status' => null,
                'new_status' => 'new',
                'notes' => 'Hotel booking request submitted by customer.',
                'changed_by' => auth()->id(),
            ]);

            return $booking->load(['guests', 'hotel', 'room']);
        });
    }

    public function updateStatus(HotelBookingRequest $booking, string $status, ?string $notes = null): void
    {
        $previous = $booking->status;
        $booking->update([
            'status' => $status,
            'updated_by' => auth()->id(),
        ]);

        if ($previous !== $status) {
            HotelBookingRequestStatusHistory::query()->create([
                'hotel_booking_request_id' => $booking->id,
                'old_status' => $previous,
                'new_status' => $status,
                'notes' => $notes ?? 'Status updated.',
                'changed_by' => auth()->id(),
            ]);
        }
    }

    private function parseDate(mixed $value): ?Carbon
    {
        if (! $value) {
            return null;
        }

        $value = trim((string) $value);

        foreach (['d/m/Y', 'd-m-Y', 'n/j/Y', 'm/d/Y', 'Y-m-d', 'Y/m/d'] as $format) {
            try {
                $parsed = Carbon::createFromFormat($format, $value);
                if ($parsed !== false) {
                    return $parsed->startOfDay();
                }
            } catch (\Throwable) {
                continue;
            }
        }

        try {
            return Carbon::parse($value)->startOfDay();
        } catch (\Throwable) {
            return null;
        }
    }
}
