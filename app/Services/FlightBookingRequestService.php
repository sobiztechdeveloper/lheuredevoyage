<?php

namespace App\Services;

use App\Models\FlightBookingPassenger;
use App\Models\FlightBookingRequest;
use App\Models\FlightBookingRequestStatusHistory;
use App\Models\FlightSearchResult;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class FlightBookingRequestService
{
    /**
     * @return list<array{key: string, type: string, label: string}>
     */
    public function passengerSlots(?int $adults, ?int $children, ?int $infants): array
    {
        $slots = [];
        $adults = max(0, (int) $adults);
        $children = max(0, (int) $children);
        $infants = max(0, (int) $infants);

        for ($i = 1; $i <= $adults; $i++) {
            $slots[] = ['key' => 'adult_'.$i, 'type' => 'adult', 'label' => 'Adult '.$i];
        }
        for ($i = 1; $i <= $children; $i++) {
            $slots[] = ['key' => 'child_'.$i, 'type' => 'child', 'label' => 'Child '.$i];
        }
        for ($i = 1; $i <= $infants; $i++) {
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
    public function buildFlightSummary(FlightSearchResult $result): array
    {
        $search = $result->flightSearch;

        return [
            'airline' => $result->airline,
            'flight_number' => $result->flight_number,
            'from' => $result->from_destination,
            'to' => $result->to_destination,
            'from_code' => $result->destinationCode($result->from_destination),
            'to_code' => $result->destinationCode($result->to_destination),
            'departure_date' => $search?->journey_date ?? $result->departure_at,
            'return_date' => $search?->return_date,
            'departure_time' => $result->departure_at,
            'arrival_time' => $result->arrival_at,
            'duration' => $result->duration,
            'stops' => $result->stopLabel(),
            'cabin_class' => ucfirst(str_replace('_', ' ', $result->cabin_class)),
            'trip_type' => ucfirst(str_replace('_', ' ', $search?->trip_type ?? 'one_way')),
            'adults' => $search?->adult ?? 1,
            'children' => $search?->children ?? 0,
            'infants' => $search?->infant ?? 0,
            'estimated_fare' => $result->price,
            'currency' => $result->currency,
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createFromResult(FlightSearchResult $result, array $data, Request $request): FlightBookingRequest
    {
        $search = $result->flightSearch;
        $typeCounts = ['adult' => 0, 'child' => 0, 'infant' => 0];
        foreach ($data['passengers'] as $passenger) {
            $type = $passenger['passenger_type'] ?? 'adult';
            if (isset($typeCounts[$type])) {
                $typeCounts[$type]++;
            }
        }

        $contactIndex = (int) ($data['contact_passenger_index'] ?? 0);
        $contactName = $this->resolveContactName($data['passengers'], $contactIndex, $data['contact_name'] ?? null);

        $assistance = [];
        foreach (array_keys(FlightBookingRequest::SPECIAL_ASSISTANCE_OPTIONS) as $key) {
            if (! empty($data['special_assistance'][$key])) {
                $assistance[$key] = true;
            }
        }

        return DB::transaction(function () use ($result, $search, $data, $request, $typeCounts, $contactIndex, $contactName, $assistance) {
            $booking = FlightBookingRequest::query()->create([
                'booking_reference' => FlightBookingRequest::generateReference(),
                'user_id' => auth()->id(),
                'flight_search_id' => $search?->id,
                'flight_search_result_id' => $result->id,
                'trip_type' => $search?->trip_type ?? 'one_way',
                'origin' => $result->from_destination,
                'destination' => $result->to_destination,
                'departure_date' => $search?->journey_date ?? $result->departure_at->toDateString(),
                'return_date' => $search?->return_date,
                'adults' => $typeCounts['adult'],
                'children' => $typeCounts['child'],
                'infants' => $typeCounts['infant'],
                'cabin_class' => $result->cabin_class,
                'selected_flight' => [
                    'airline' => $result->airline,
                    'flight_number' => $result->flight_number,
                    'from_destination' => $result->from_destination,
                    'to_destination' => $result->to_destination,
                    'departure_at' => $result->departure_at?->toIso8601String(),
                    'arrival_at' => $result->arrival_at?->toIso8601String(),
                    'duration' => $result->duration,
                    'stops' => $result->stops,
                    'price' => $result->price,
                    'currency' => $result->currency,
                ],
                'contact_name' => $contactName,
                'contact_passenger_index' => $contactIndex,
                'email' => $data['email'],
                'phone' => $data['phone'],
                'whatsapp' => $data['whatsapp'] ?? null,
                'country' => $data['country'] ?? null,
                'preferred_airline' => $data['preferred_airline'] ?? null,
                'seat_preference' => $data['seat_preference'] ?? 'no_preference',
                'meal_preference' => $data['meal_preference'] ?? 'no_preference',
                'special_assistance' => $assistance !== [] ? $assistance : null,
                'special_requests' => $data['special_requests'] ?? null,
                'estimated_price' => $result->price,
                'currency' => $result->currency,
                'status' => 'new',
            ]);

            foreach ($data['passengers'] as $index => $passenger) {
                FlightBookingPassenger::query()->create([
                    'flight_booking_request_id' => $booking->id,
                    'passenger_type' => $passenger['passenger_type'],
                    'title' => $passenger['title'],
                    'first_name' => $passenger['first_name'],
                    'last_name' => $passenger['last_name'],
                    'gender' => $passenger['gender'],
                    'date_of_birth' => $passenger['date_of_birth'],
                    'nationality' => $passenger['nationality'],
                    'passport_number' => $passenger['passport_number'],
                    'passport_expiry' => $passenger['passport_expiry'],
                    'passport_country' => $passenger['passport_country'] ?? null,
                    'passport_file' => $this->storePassportFile($request, (int) $index),
                ]);
            }

            FlightBookingRequestStatusHistory::query()->create([
                'flight_booking_request_id' => $booking->id,
                'status' => 'new',
                'notes' => 'Booking request submitted by customer.',
                'changed_by' => auth()->id(),
            ]);

            return $booking->load('passengers');
        });
    }

    /**
     * @param  array<int, array<string, mixed>>  $passengers
     */
    public function resolveContactName(array $passengers, int $index, ?string $fallback = null): string
    {
        $passenger = $passengers[$index] ?? null;
        if ($passenger) {
            return trim(($passenger['title'] ?? '').' '.($passenger['first_name'] ?? '').' '.($passenger['last_name'] ?? ''));
        }

        return trim((string) $fallback);
    }

    private function storePassportFile(Request $request, int $index): ?string
    {
        /** @var UploadedFile|null $file */
        $file = $request->file("passengers.{$index}.passport_file");

        if (! $file) {
            return null;
        }

        return $file->store('passports', 'public');
    }

    public function updateStatus(FlightBookingRequest $booking, string $status, ?string $notes = null): void
    {
        $previous = $booking->status;
        $booking->update(['status' => $status]);

        if ($previous !== $status) {
            FlightBookingRequestStatusHistory::query()->create([
                'flight_booking_request_id' => $booking->id,
                'status' => $status,
                'notes' => $notes ?? 'Status updated by admin.',
                'changed_by' => auth()->id(),
            ]);
        }
    }
}
