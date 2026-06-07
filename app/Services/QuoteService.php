<?php

namespace App\Services;

use App\Models\FlightBookingRequest;
use App\Models\HotelBookingRequest;
use App\Models\CruiseBookingRequest;
use App\Models\CarBookingRequest;
use App\Models\InsuranceBookingRequest;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\QuoteStatusHistory;
use Illuminate\Support\Facades\DB;

class QuoteService
{
    /**
     * @param  array<int, array{item_name: string, description?: string, quantity: int|float, unit_price: float|int, sort_order?: int}>  $items
     * @param  array<string, mixed>  $data
     */
    public function create(array $data, array $items): Quote
    {
        return DB::transaction(function () use ($data, $items) {
            $totals = $this->calculateTotals($items, $data);

            $quote = Quote::query()->create([
                'quote_number' => Quote::generateQuoteNumber(),
                'customer_id' => $data['customer_id'] ?? null,
                'flight_booking_request_id' => $data['flight_booking_request_id'] ?? null,
                'hotel_booking_request_id' => $data['hotel_booking_request_id'] ?? null,
                'cruise_booking_request_id' => $data['cruise_booking_request_id'] ?? null,
                'car_booking_request_id' => $data['car_booking_request_id'] ?? null,
                'insurance_booking_request_id' => $data['insurance_booking_request_id'] ?? null,
                'quote_type' => $data['quote_type'],
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'currency' => $data['currency'] ?? 'USD',
                'amount' => $totals['amount'],
                'tax_amount' => $totals['tax_amount'],
                'service_fee' => $totals['service_fee'],
                'total_amount' => $totals['total_amount'],
                'valid_until' => $data['valid_until'],
                'status' => $data['status'] ?? 'draft',
                'notes' => $data['notes'] ?? null,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            $this->syncItems($quote, $items);
            $this->recordStatus($quote, null, $quote->status, 'Quote created.');

            if ($quote->flight_booking_request_id && $quote->status === 'sent') {
                $this->syncFlightRequestQuoted($quote);
            }
            if ($quote->hotel_booking_request_id && $quote->status === 'sent') {
                $this->syncHotelRequestQuoted($quote);
            }
            if ($quote->cruise_booking_request_id && $quote->status === 'sent') {
                $this->syncCruiseRequestQuoted($quote);
            }
            if ($quote->car_booking_request_id && $quote->status === 'sent') {
                $this->syncCarRequestQuoted($quote);
            }
            if ($quote->insurance_booking_request_id && $quote->status === 'sent') {
                $this->syncInsuranceRequestQuoted($quote);
            }

            return $quote->load(['items', 'customer', 'flightBookingRequest', 'hotelBookingRequest', 'cruiseBookingRequest', 'carBookingRequest', 'insuranceBookingRequest']);
        });
    }

    /**
     * @param  array<int, array{item_name: string, description?: string, quantity: int|float, unit_price: float|int, sort_order?: int}>  $items
     * @param  array<string, mixed>  $data
     */
    public function update(Quote $quote, array $data, array $items): Quote
    {
        return DB::transaction(function () use ($quote, $data, $items) {
            $previousStatus = $quote->status;
            $totals = $this->calculateTotals($items, $data);

            $quote->update([
                'customer_id' => $data['customer_id'] ?? null,
                'flight_booking_request_id' => $data['flight_booking_request_id'] ?? $quote->flight_booking_request_id,
                'hotel_booking_request_id' => $data['hotel_booking_request_id'] ?? $quote->hotel_booking_request_id,
                'cruise_booking_request_id' => $data['cruise_booking_request_id'] ?? $quote->cruise_booking_request_id,
                'car_booking_request_id' => $data['car_booking_request_id'] ?? $quote->car_booking_request_id,
                'insurance_booking_request_id' => $data['insurance_booking_request_id'] ?? $quote->insurance_booking_request_id,
                'quote_type' => $data['quote_type'],
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'currency' => $data['currency'] ?? 'USD',
                'amount' => $totals['amount'],
                'tax_amount' => $totals['tax_amount'],
                'service_fee' => $totals['service_fee'],
                'total_amount' => $totals['total_amount'],
                'valid_until' => $data['valid_until'],
                'status' => $data['status'] ?? $quote->status,
                'notes' => $data['notes'] ?? null,
                'updated_by' => auth()->id(),
            ]);

            $this->syncItems($quote, $items);

            if ($previousStatus !== $quote->status) {
                $this->recordStatus($quote, $previousStatus, $quote->status, 'Quote updated by admin.');
            }

            return $quote->fresh(['items', 'customer', 'flightBookingRequest', 'hotelBookingRequest', 'cruiseBookingRequest', 'carBookingRequest', 'insuranceBookingRequest']);
        });
    }

    public function createDraftFromFlightRequest(FlightBookingRequest $request): Quote
    {
        $request->load(['passengers', 'user']);

        $passengerSummary = $request->passengers
            ->map(fn ($p) => $p->fullName().' ('.ucfirst($p->passenger_type).')')
            ->implode(', ');

        $description = "Booking reference: {$request->booking_reference}\n"
            ."Route: {$request->routeLabel()}\n"
            ."Departure: {$request->departure_date->format(config('date.display'))}\n"
            ."Passengers: {$request->passengerCount()}\n"
            .($passengerSummary ? "Travelers: {$passengerSummary}" : '');

        $items = [[
            'item_name' => 'Flight — '.$request->routeLabel(),
            'description' => ($request->selected_flight['airline'] ?? '').' '.($request->selected_flight['flight_number'] ?? ''),
            'quantity' => 1,
            'unit_price' => (float) $request->estimated_price,
            'sort_order' => 0,
        ]];

        return $this->create([
            'customer_id' => $request->user_id,
            'flight_booking_request_id' => $request->id,
            'quote_type' => 'flight',
            'title' => 'Flight Quote — '.$request->routeLabel(),
            'description' => $description,
            'currency' => $request->currency ?? 'USD',
            'tax_amount' => 0,
            'service_fee' => 0,
            'valid_until' => now()->addDays(7)->toDateString(),
            'status' => 'draft',
            'notes' => 'Generated from flight request '.$request->booking_reference.'.',
        ], $items);
    }

    public function createDraftFromHotelRequest(HotelBookingRequest $request): Quote
    {
        $request->load(['guests', 'hotel', 'room', 'customer']);

        $hotelName = $request->selected_hotel['name'] ?? $request->hotel?->name ?? 'Hotel';
        $roomName = $request->selected_room['name'] ?? $request->room?->name ?? 'Room';

        $description = "Reference: {$request->reference_number}\n"
            ."Hotel: {$hotelName}\n"
            ."Room: {$roomName}\n"
            ."Check-in: {$request->check_in_date->format(config('date.display'))}\n"
            ."Check-out: {$request->check_out_date->format(config('date.display'))}\n"
            ."Guests: {$request->guestCount()}";

        $items = [[
            'item_name' => 'Hotel — '.$hotelName.' ('.$roomName.')',
            'description' => "{$request->nights()} night(s), {$request->rooms} room(s)",
            'quantity' => 1,
            'unit_price' => (float) $request->estimated_amount,
            'sort_order' => 0,
        ]];

        return $this->create([
            'customer_id' => $request->customer_id,
            'hotel_booking_request_id' => $request->id,
            'quote_type' => 'hotel',
            'title' => 'Hotel Quote — '.$hotelName,
            'description' => $description,
            'currency' => $request->currency ?? 'USD',
            'tax_amount' => 0,
            'service_fee' => 0,
            'valid_until' => now()->addDays(7)->toDateString(),
            'status' => 'draft',
            'notes' => 'Generated from hotel request '.$request->reference_number.'.',
        ], $items);
    }

    public function createDraftFromCruiseRequest(CruiseBookingRequest $request): Quote
    {
        $request->load(['passengers', 'cruise', 'customer']);

        $cruiseName = $request->selected_cruise['name'] ?? $request->cruise?->name ?? 'Cruise';
        $description = "Reference: {$request->reference_number}\n"
            ."Cruise: {$cruiseName}\n"
            ."Departure: {$request->departure_date->format(config('date.display'))}\n"
            .($request->return_date ? "Return: {$request->return_date->format(config('date.display'))}\n" : '')
            ."Passengers: {$request->passengerCount()}";

        $items = [[
            'item_name' => 'Cruise — '.$cruiseName,
            'description' => ($request->cabin_type ? "Cabin: {$request->cabin_type}" : 'Cruise booking request'),
            'quantity' => 1,
            'unit_price' => (float) $request->estimated_amount,
            'sort_order' => 0,
        ]];

        return $this->create([
            'customer_id' => $request->customer_id,
            'cruise_booking_request_id' => $request->id,
            'quote_type' => 'cruise',
            'title' => 'Cruise Quote — '.$cruiseName,
            'description' => trim($description),
            'currency' => $request->currency ?? 'USD',
            'tax_amount' => 0,
            'service_fee' => 0,
            'valid_until' => now()->addDays(7)->toDateString(),
            'status' => 'draft',
            'notes' => 'Generated from cruise request '.$request->reference_number.'.',
        ], $items);
    }

    public function createDraftFromCarRequest(CarBookingRequest $request): Quote
    {
        $request->load(['drivers', 'rentalCar', 'customer']);

        $carName = $request->selected_vehicle['name'] ?? $request->rentalCar?->name ?? 'Rental Car';
        $description = "Reference: {$request->reference_number}\n"
            ."Vehicle: {$carName}\n"
            ."Pick-up: {$request->pickup_location} on {$request->pickup_date->format(config('date.display'))}\n"
            ."Return: ".($request->dropoff_location ?: $request->pickup_location)." on {$request->return_date->format(config('date.display'))}\n"
            ."Drivers: {$request->drivers()->count()}";

        $items = [[
            'item_name' => 'Rental Car — '.$carName,
            'description' => $request->pickup_location.' to '.($request->dropoff_location ?: $request->pickup_location),
            'quantity' => 1,
            'unit_price' => (float) $request->estimated_amount,
            'sort_order' => 0,
        ]];

        return $this->create([
            'customer_id' => $request->customer_id,
            'car_booking_request_id' => $request->id,
            'quote_type' => 'car',
            'title' => 'Car Quote — '.$carName,
            'description' => $description,
            'currency' => $request->currency ?? 'USD',
            'tax_amount' => 0,
            'service_fee' => 0,
            'valid_until' => now()->addDays(7)->toDateString(),
            'status' => 'draft',
            'notes' => 'Generated from car request '.$request->reference_number.'.',
        ], $items);
    }

    public function createDraftFromInsuranceRequest(InsuranceBookingRequest $request): Quote
    {
        $request->load(['travelers', 'travelInsurance', 'customer']);

        $policyName = $request->selected_policy['name'] ?? $request->travelInsurance?->name ?? 'Travel Insurance';
        $description = "Reference: {$request->reference_number}\n"
            ."Policy: {$policyName}\n"
            ."Destination: ".($request->destination ?: 'Not specified')."\n"
            ."Travel: {$request->travel_start->format(config('date.display'))} to {$request->travel_end->format(config('date.display'))}\n"
            ."Travelers: {$request->travelers()->count()}";

        $items = [[
            'item_name' => 'Travel Insurance — '.$policyName,
            'description' => $request->coverage_type ?: 'Travel policy coverage',
            'quantity' => 1,
            'unit_price' => (float) $request->estimated_amount,
            'sort_order' => 0,
        ]];

        return $this->create([
            'customer_id' => $request->customer_id,
            'insurance_booking_request_id' => $request->id,
            'quote_type' => 'insurance',
            'title' => 'Insurance Quote — '.$policyName,
            'description' => $description,
            'currency' => $request->currency ?? 'USD',
            'tax_amount' => 0,
            'service_fee' => 0,
            'valid_until' => now()->addDays(7)->toDateString(),
            'status' => 'draft',
            'notes' => 'Generated from insurance request '.$request->reference_number.'.',
        ], $items);
    }

    public function send(Quote $quote): Quote
    {
        $previous = $quote->status;

        if ($quote->isExpired()) {
            $this->transition($quote, 'expired', 'Quote validity period has passed.');

            return $quote->fresh();
        }

        $this->transition($quote, 'sent', 'Quote sent to customer.');
        $this->syncFlightRequestQuoted($quote);
        $this->syncHotelRequestQuoted($quote);
        $this->syncCruiseRequestQuoted($quote);
        $this->syncCarRequestQuoted($quote);
        $this->syncInsuranceRequestQuoted($quote);

        return $quote->fresh();
    }

    public function markViewed(Quote $quote): Quote
    {
        if ($quote->status === 'sent') {
            $this->transition($quote, 'viewed', 'Customer viewed the quote.');
        }

        return $quote->fresh();
    }

    public function accept(Quote $quote, ?string $comment = null): Quote
    {
        $this->ensureCustomerActionAllowed($quote);
        $note = $comment ? "Customer accepted: {$comment}" : 'Customer accepted the quote.';
        $this->transition($quote, 'accepted', $note);
        app(InsuranceBookingRequestService::class)->syncFromQuoteAcceptance($quote->fresh());
        app(CruiseBookingRequestService::class)->syncFromQuoteAcceptance($quote->fresh());

        return $quote->fresh();
    }

    public function reject(Quote $quote, ?string $comment = null): Quote
    {
        $this->ensureCustomerActionAllowed($quote);
        $note = $comment ? "Customer rejected: {$comment}" : 'Customer rejected the quote.';
        $this->transition($quote, 'rejected', $note);
        app(InsuranceBookingRequestService::class)->syncFromQuoteRejection($quote->fresh());
        app(CruiseBookingRequestService::class)->syncFromQuoteRejection($quote->fresh());

        return $quote->fresh();
    }

    public function expireIfNeeded(Quote $quote, bool $notify = false): Quote
    {
        if ($quote->isExpired() && ! in_array($quote->status, ['accepted', 'rejected', 'expired'], true)) {
            $this->transition($quote, 'expired', 'Quote validity period ended.');
            if ($notify) {
                app(QuoteNotificationService::class)->notifyExpired($quote->fresh());
            }
        }

        return $quote->fresh();
    }

    /**
     * @param  array<int, array{item_name: string, description?: string, quantity: int|float, unit_price: float|int, sort_order?: int}>  $items
     * @return array{amount: float, tax_amount: float, service_fee: float, total_amount: float}
     */
    public function calculateTotals(array $items, array $data): array
    {
        $subtotal = 0.0;
        foreach ($items as $item) {
            $qty = max(1, (int) ($item['quantity'] ?? 1));
            $unit = (float) ($item['unit_price'] ?? 0);
            $subtotal += $qty * $unit;
        }

        $tax = (float) ($data['tax_amount'] ?? 0);
        $fee = (float) ($data['service_fee'] ?? 0);

        return [
            'amount' => round($subtotal, 2),
            'tax_amount' => round($tax, 2),
            'service_fee' => round($fee, 2),
            'total_amount' => round($subtotal + $tax + $fee, 2),
        ];
    }

    /**
     * @param  array<int, array{item_name: string, description?: string, quantity: int|float, unit_price: float|int, sort_order?: int}>  $items
     */
    protected function syncItems(Quote $quote, array $items): void
    {
        $quote->items()->delete();

        foreach (array_values($items) as $index => $item) {
            $qty = max(1, (int) ($item['quantity'] ?? 1));
            $unit = (float) ($item['unit_price'] ?? 0);

            QuoteItem::query()->create([
                'quote_id' => $quote->id,
                'item_name' => $item['item_name'],
                'description' => $item['description'] ?? null,
                'quantity' => $qty,
                'unit_price' => $unit,
                'total_price' => round($qty * $unit, 2),
                'sort_order' => $item['sort_order'] ?? $index,
            ]);
        }
    }

    protected function transition(Quote $quote, string $newStatus, ?string $notes = null): void
    {
        $old = $quote->status;
        if ($old === $newStatus) {
            return;
        }

        $quote->update([
            'status' => $newStatus,
            'updated_by' => auth()->id(),
        ]);

        $this->recordStatus($quote, $old, $newStatus, $notes);
    }

    protected function recordStatus(Quote $quote, ?string $old, string $new, ?string $notes): void
    {
        QuoteStatusHistory::query()->create([
            'quote_id' => $quote->id,
            'old_status' => $old,
            'new_status' => $new,
            'changed_by' => auth()->id(),
            'notes' => $notes,
        ]);
    }

    protected function syncFlightRequestQuoted(Quote $quote): void
    {
        if (! $quote->flight_booking_request_id) {
            return;
        }

        $request = $quote->flightBookingRequest;
        if ($request && $request->status !== 'ticketed' && $request->status !== 'cancelled') {
            app(FlightBookingRequestService::class)->updateStatus(
                $request,
                'quoted',
                'Quote '.$quote->quote_number.' sent to customer.',
            );
        }
    }

    protected function syncHotelRequestQuoted(Quote $quote): void
    {
        if (! $quote->hotel_booking_request_id) {
            return;
        }

        $request = $quote->hotelBookingRequest;
        if ($request && ! in_array($request->status, ['completed', 'cancelled', 'voucher_sent'], true)) {
            app(HotelBookingRequestService::class)->updateStatus(
                $request,
                'quoted',
                'Quote '.$quote->quote_number.' sent to customer.',
            );
        }
    }

    protected function syncCruiseRequestQuoted(Quote $quote): void
    {
        if (! $quote->cruise_booking_request_id) {
            return;
        }

        $request = $quote->cruiseBookingRequest;
        if ($request && ! in_array($request->status, ['completed', 'cancelled', 'voucher_sent', 'accepted', 'rejected'], true)) {
            app(CruiseBookingRequestService::class)->updateStatus(
                $request,
                'quoted',
                'Quote '.$quote->quote_number.' sent to customer.',
            );
        }
    }

    protected function syncCarRequestQuoted(Quote $quote): void
    {
        if (! $quote->car_booking_request_id) {
            return;
        }

        $request = $quote->carBookingRequest;
        if ($request && ! in_array($request->status, ['completed', 'cancelled', 'voucher_sent'], true)) {
            app(CarBookingRequestService::class)->updateStatus(
                $request,
                'quoted',
                'Quote '.$quote->quote_number.' sent to customer.',
            );
        }
    }

    protected function syncInsuranceRequestQuoted(Quote $quote): void
    {
        if (! $quote->insurance_booking_request_id) {
            return;
        }

        $request = $quote->insuranceBookingRequest;
        if ($request && ! in_array($request->status, ['completed', 'cancelled', 'policy_issued', 'accepted', 'rejected'], true)) {
            app(InsuranceBookingRequestService::class)->updateStatus(
                $request,
                'quoted',
                'Quote '.$quote->quote_number.' sent to customer.',
            );
        }
    }

    protected function ensureCustomerActionAllowed(Quote $quote): void
    {
        $quote = $this->expireIfNeeded($quote);

        if (! $quote->canBeAcceptedOrRejected()) {
            abort(422, 'This quote cannot be accepted or rejected in its current state.');
        }
    }
}
