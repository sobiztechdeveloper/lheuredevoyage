@php
    $items = old('items', $quote->relationLoaded('items') ? $quote->items->map(fn ($i) => [
        'item_name' => $i->item_name,
        'description' => $i->description,
        'quantity' => $i->quantity,
        'unit_price' => $i->unit_price,
        'sort_order' => $i->sort_order,
    ])->values()->all() : [['item_name' => '', 'description' => '', 'quantity' => 1, 'unit_price' => 0]]);
    if ($items === []) {
        $items = [['item_name' => '', 'description' => '', 'quantity' => 1, 'unit_price' => 0]];
    }
@endphp

<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Quote Information</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Quote Type</label>
                    <select name="quote_type" class="form-select" required>
                        @foreach($types as $t)
                            <option value="{{ $t }}" @selected(old('quote_type', $quote->quote_type) === $t)>{{ ucfirst($t === 'package' ? 'Tour Package' : ($t === 'car' ? 'Rental Car' : $t)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Currency</label>
                    <input type="text" name="currency" class="form-control" value="{{ old('currency', $quote->currency ?? 'USD') }}" required maxlength="10">
                </div>
                <div class="col-12">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $quote->title) }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $quote->description) }}</textarea>
                </div>
                @if($flightRequest)
                    <input type="hidden" name="flight_booking_request_id" value="{{ $flightRequest->id }}">
                    <div class="col-12">
                        <div class="alert alert-info small mb-0">
                            Linked flight request: <strong>{{ $flightRequest->booking_reference }}</strong> — {{ $flightRequest->routeLabel() }}
                        </div>
                    </div>
                @elseif($quote->flight_booking_request_id)
                    <input type="hidden" name="flight_booking_request_id" value="{{ $quote->flight_booking_request_id }}">
                @endif
            </div>
        </div>

        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Customer Information</h5>
            <div class="mb-3">
                <label class="form-label">Customer (registered user)</label>
                <select name="customer_id" class="form-select">
                    <option value="">— Guest / assign later —</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" @selected((string) old('customer_id', $quote->customer_id) === (string) $customer->id)>
                            {{ $customer->name }} ({{ $customer->email }})
                        </option>
                    @endforeach
                </select>
                @if($flightRequest && $flightRequest->user_id)
                    <div class="form-text">Flight request customer pre-selected when available.</div>
                @endif
            </div>
        </div>

        <div class="admin-panel-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0" style="color:var(--admin-primary)">Line Items</h5>
                <button type="button" class="btn btn-sm btn-admin-outline" id="quote-add-item"><i class="far fa-plus"></i> Add row</button>
            </div>
            <div class="table-responsive quote-items-table">
                <table class="table align-middle" id="quote-items-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Description</th>
                            <th style="width:90px">Qty</th>
                            <th style="width:120px">Unit price</th>
                            <th style="width:120px">Line total</th>
                            <th style="width:40px"></th>
                        </tr>
                    </thead>
                    <tbody id="quote-items-body">
                        @foreach($items as $idx => $item)
                        <tr class="quote-item-row">
                            <td><input type="text" name="items[{{ $idx }}][item_name]" class="form-control form-control-sm" value="{{ $item['item_name'] ?? '' }}" required></td>
                            <td><input type="text" name="items[{{ $idx }}][description]" class="form-control form-control-sm" value="{{ $item['description'] ?? '' }}"></td>
                            <td><input type="number" name="items[{{ $idx }}][quantity]" class="form-control form-control-sm quote-qty" min="1" value="{{ $item['quantity'] ?? 1 }}" required></td>
                            <td><input type="number" name="items[{{ $idx }}][unit_price]" class="form-control form-control-sm quote-unit" min="0" step="0.01" value="{{ $item['unit_price'] ?? 0 }}" required></td>
                            <td class="quote-line-total fw-semibold">0.00</td>
                            <td><button type="button" class="btn btn-sm btn-link text-danger quote-remove-item" title="Remove">&times;</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="admin-panel-card">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Notes & Validity</h5>
            <div class="mb-3">
                <label class="form-label">Internal / customer notes</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $quote->notes) }}</textarea>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Valid until</label>
                    <input type="date" name="valid_until" class="form-control" value="{{ old('valid_until', $quote->valid_until?->format('Y-m-d') ?? now()->addDays(7)->format('Y-m-d')) }}" required>
                </div>
                @if($isEdit)
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        @foreach($statuses as $s)
                            <option value="{{ $s }}" @selected(old('status', $quote->status) === $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                    <input type="hidden" name="status" value="draft">
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="admin-panel-card sticky-top" style="top:1rem">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Pricing Summary</h5>
            <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><strong id="quote-subtotal">0.00</strong></div>
            <div class="mb-2">
                <label class="form-label small">Tax</label>
                <input type="number" name="tax_amount" id="quote-tax" class="form-control form-control-sm" min="0" step="0.01" value="{{ old('tax_amount', $quote->tax_amount ?? 0) }}">
            </div>
            <div class="mb-3">
                <label class="form-label small">Service fee</label>
                <input type="number" name="service_fee" id="quote-fee" class="form-control form-control-sm" min="0" step="0.01" value="{{ old('service_fee', $quote->service_fee ?? 0) }}">
            </div>
            <div class="d-flex justify-content-between border-top pt-3">
                <span class="fw-bold">Grand total</span>
                <strong id="quote-grand-total" style="color:var(--admin-primary)">0.00</strong>
            </div>
            <hr>
            <div class="d-grid gap-2">
                <button type="submit" name="action" value="save" class="btn btn-admin-primary">Save {{ $isEdit ? 'Changes' : 'Quote' }}</button>
                <button type="submit" name="action" value="send" class="btn btn-admin-secondary">Save &amp; Send to Customer</button>
                <a href="{{ $isEdit ? route('admin.quotes.show', $quote) : route('admin.quotes.index') }}" class="btn btn-admin-outline">Cancel</a>
            </div>
        </div>
    </div>
</div>

<template id="quote-item-row-tpl">
    <tr class="quote-item-row">
        <td><input type="text" data-name="item_name" class="form-control form-control-sm" required></td>
        <td><input type="text" data-name="description" class="form-control form-control-sm"></td>
        <td><input type="number" data-name="quantity" class="form-control form-control-sm quote-qty" min="1" value="1" required></td>
        <td><input type="number" data-name="unit_price" class="form-control form-control-sm quote-unit" min="0" step="0.01" value="0" required></td>
        <td class="quote-line-total fw-semibold">0.00</td>
        <td><button type="button" class="btn btn-sm btn-link text-danger quote-remove-item">&times;</button></td>
    </tr>
</template>
