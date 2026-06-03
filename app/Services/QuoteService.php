<?php

namespace App\Services;

use App\Models\FlightBookingRequest;
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

            return $quote->load(['items', 'customer', 'flightBookingRequest']);
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

            return $quote->fresh(['items', 'customer', 'flightBookingRequest']);
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
            ."Departure: {$request->departure_date->format('M d, Y')}\n"
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

    public function send(Quote $quote): Quote
    {
        $previous = $quote->status;

        if ($quote->isExpired()) {
            $this->transition($quote, 'expired', 'Quote validity period has passed.');

            return $quote->fresh();
        }

        $this->transition($quote, 'sent', 'Quote sent to customer.');
        $this->syncFlightRequestQuoted($quote);

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

        return $quote->fresh();
    }

    public function reject(Quote $quote, ?string $comment = null): Quote
    {
        $this->ensureCustomerActionAllowed($quote);
        $note = $comment ? "Customer rejected: {$comment}" : 'Customer rejected the quote.';
        $this->transition($quote, 'rejected', $note);

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

    protected function ensureCustomerActionAllowed(Quote $quote): void
    {
        $quote = $this->expireIfNeeded($quote);

        if (! $quote->canBeAcceptedOrRejected()) {
            abort(422, 'This quote cannot be accepted or rejected in its current state.');
        }
    }
}
