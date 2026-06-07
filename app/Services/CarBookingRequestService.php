<?php

namespace App\Services;

use App\Models\CarBookingDriver;
use App\Models\CarBookingRequest;
use App\Models\CarBookingRequestStatusHistory;
use App\Models\RentalCar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CarBookingRequestService
{
    /**
     * @return list<array{key: string, label: string}>
     */
    public function driverSlots(int $drivers): array
    {
        $slots = [];
        for ($i = 1; $i <= max(0, $drivers); $i++) {
            $slots[] = ['key' => 'driver_'.$i, 'label' => 'Driver '.$i];
        }

        if ($slots === []) {
            $slots[] = ['key' => 'driver_1', 'label' => 'Driver 1'];
        }

        return $slots;
    }

    /**
     * @return array<string, mixed>
     */
    public function buildContext(RentalCar $rentalCar, array $searchParams): array
    {
        $pickupDate = $this->parseDate($searchParams['pickup_date'] ?? $searchParams['journey_date'] ?? null);
        $returnDate = $this->parseDate($searchParams['return_date'] ?? null);
        $drivers = max(1, (int) ($searchParams['drivers'] ?? 1));

        if (! $pickupDate) {
            $pickupDate = now()->addDays(7)->startOfDay();
        }
        if (! $returnDate || $returnDate->lte($pickupDate)) {
            $returnDate = $pickupDate->copy()->addDay();
        }

        $days = max(1, (int) $pickupDate->diffInDays($returnDate));
        $dailyPrice = (float) ($rentalCar->price_per_day ?: $rentalCar->price);
        $estimated = round($dailyPrice * $days, 2);

        return [
            'pickup_date' => $pickupDate,
            'return_date' => $returnDate,
            'days' => $days,
            'drivers' => $drivers,
            'estimated_amount' => $estimated,
            'currency' => 'USD',
            'driver_slots' => $this->driverSlots($drivers),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(RentalCar $rentalCar, array $data): CarBookingRequest
    {
        return DB::transaction(function () use ($rentalCar, $data) {
            $booking = CarBookingRequest::query()->create([
                'reference_number' => CarBookingRequest::generateReference(),
                'customer_id' => auth()->id(),
                'rental_car_id' => $rentalCar->id,
                'status' => 'new',
                'pickup_location' => $data['pickup_location'],
                'dropoff_location' => $data['dropoff_location'] ?? null,
                'pickup_date' => $data['pickup_date'],
                'pickup_time' => $data['pickup_time'] ?? null,
                'return_date' => $data['return_date'],
                'return_time' => $data['return_time'] ?? null,
                'contact_email' => $data['contact_email'],
                'contact_phone' => $data['contact_phone'],
                'contact_whatsapp' => $data['contact_whatsapp'] ?? null,
                'address' => $data['address'] ?? null,
                'country' => $data['country'] ?? null,
                'extra_gps' => (bool) ($data['extra_gps'] ?? false),
                'extra_child_seat' => (bool) ($data['extra_child_seat'] ?? false),
                'extra_additional_driver' => (bool) ($data['extra_additional_driver'] ?? false),
                'insurance_option' => $data['insurance_option'] ?? null,
                'notes' => $data['notes'] ?? null,
                'selected_vehicle' => [
                    'id' => $rentalCar->id,
                    'name' => $rentalCar->name,
                    'location' => $rentalCar->location,
                    'slug' => $rentalCar->slug,
                    'price_per_day' => $rentalCar->price_per_day,
                ],
                'estimated_amount' => $data['estimated_amount'] ?? null,
                'currency' => $data['currency'] ?? 'USD',
                'created_by' => auth()->id(),
            ]);

            $drivers = is_array($data['drivers'] ?? null) ? $data['drivers'] : [];
            foreach ($drivers as $driver) {
                CarBookingDriver::query()->create([
                    'car_booking_request_id' => $booking->id,
                    'title' => $driver['title'] ?? null,
                    'first_name' => $driver['first_name'] ?? '',
                    'last_name' => $driver['last_name'] ?? '',
                    'date_of_birth' => $driver['date_of_birth'] ?? null,
                    'nationality' => $driver['nationality'] ?? null,
                    'license_number' => $driver['license_number'] ?? null,
                    'license_expiry' => $driver['license_expiry'] ?? null,
                    'passport_number' => $driver['passport_number'] ?? null,
                    'license_file' => $driver['license_file'] ?? null,
                    'passport_file' => $driver['passport_file'] ?? null,
                ]);
            }

            CarBookingRequestStatusHistory::query()->create([
                'car_booking_request_id' => $booking->id,
                'old_status' => null,
                'new_status' => 'new',
                'notes' => 'Car booking request submitted by customer.',
                'changed_by' => auth()->id(),
            ]);

            return $booking->load(['drivers', 'rentalCar']);
        });
    }

    public function updateStatus(CarBookingRequest $booking, string $status, ?string $notes = null): void
    {
        $previous = $booking->status;
        $booking->update([
            'status' => $status,
            'updated_by' => auth()->id(),
        ]);

        if ($previous !== $status) {
            CarBookingRequestStatusHistory::query()->create([
                'car_booking_request_id' => $booking->id,
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
