@extends('layouts.admin.app')
@section('title', 'Hotel Request '.$bookingRequest->reference_number)
@section('content')
@php $hotelSnap = $bookingRequest->selected_hotel ?? []; $roomSnap = $bookingRequest->selected_room ?? []; @endphp
<div class="admin-page-header mb-4">
    <div>
        <h1>{{ $bookingRequest->reference_number }}</h1>
        <p class="text-muted small mb-0">{{ $bookingRequest->lead_guest_name }} · <x-hotel-booking-status :status="$bookingRequest->status" /></p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        @if($bookingRequest->customer_id)
            <form method="POST" action="{{ route('admin.hotel-requests.generate-quote', $bookingRequest) }}" class="d-inline">@csrf
                <button type="submit" class="btn btn-admin-primary btn-sm"><i class="far fa-file-invoice-dollar me-1"></i> Generate Quote</button>
            </form>
        @else
            <span class="btn btn-admin-outline btn-sm disabled" title="Link a registered customer before generating a quote">Generate Quote</span>
        @endif
        <a href="{{ route('admin.hotel-requests.index') }}" class="btn btn-admin-outline btn-sm">Back to list</a>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Booking Summary</h5>
            <div class="row g-3">
                <div class="col-md-6"><span class="text-muted small d-block">Reference</span><strong>{{ $bookingRequest->reference_number }}</strong></div>
                <div class="col-md-6"><span class="text-muted small d-block">Status</span><x-hotel-booking-status :status="$bookingRequest->status" /></div>
                <div class="col-md-6"><span class="text-muted small d-block">Check-in</span>{{ $bookingRequest->check_in_date->format('M d, Y') }}</div>
                <div class="col-md-6"><span class="text-muted small d-block">Check-out</span>{{ $bookingRequest->check_out_date->format('M d, Y') }}</div>
                <div class="col-md-6"><span class="text-muted small d-block">Rooms / Guests</span>{{ $bookingRequest->rooms }} room(s) · {{ $bookingRequest->guestCount() }} guest(s)</div>
                <div class="col-md-6"><span class="text-muted small d-block">Estimated</span>{{ strtoupper($bookingRequest->currency) }} {{ number_format($bookingRequest->estimated_amount, 2) }}</div>
            </div>
        </div>
        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Hotel & Room</h5>
            <div class="row g-3">
                <div class="col-md-6"><span class="text-muted small d-block">Hotel</span>{{ $hotelSnap['name'] ?? $bookingRequest->hotel?->name }}</div>
                <div class="col-md-6"><span class="text-muted small d-block">Location</span>{{ $hotelSnap['location'] ?? $bookingRequest->hotel?->location ?? '—' }}</div>
                <div class="col-md-6"><span class="text-muted small d-block">Room</span>{{ $roomSnap['name'] ?? $bookingRequest->room?->name ?? '—' }}</div>
                <div class="col-md-6"><span class="text-muted small d-block">Bed / Meal</span>{{ $roomSnap['bed_type'] ?? '—' }} / {{ $roomSnap['meal_plan'] ?? '—' }}</div>
            </div>
        </div>
        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Lead Guest & Contact</h5>
            <p class="mb-1"><strong>{{ $bookingRequest->lead_guest_name }}</strong></p>
            <p class="mb-1">{{ $bookingRequest->lead_guest_email }} · {{ $bookingRequest->lead_guest_phone }}</p>
            @if($bookingRequest->lead_guest_whatsapp)<p class="mb-0 text-muted small">WhatsApp: {{ $bookingRequest->lead_guest_whatsapp }}</p>@endif
        </div>
        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Booking contact</h5>
            @php $bookingFor = $bookingRequest->special_request_options['booking_for'] ?? null; @endphp
            @if($bookingFor)
            <p class="mb-2 text-muted small">Booking for: {{ $bookingFor === 'main_guest' ? 'Main guest' : 'Someone else' }}</p>
            @endif
            <p class="mb-1"><strong>{{ $bookingRequest->adults }}</strong> adult(s), <strong>{{ $bookingRequest->children }}</strong> child(ren), <strong>{{ $bookingRequest->infants }}</strong> infant(s) — traveler names can be collected by the consultant.</p>
            @if($bookingRequest->guests->isNotEmpty())
            <ul class="mb-0">
                @foreach($bookingRequest->guests as $guest)
                <li>{{ $guest->fullName() }} ({{ $guest->guest_type }})</li>
                @endforeach
            </ul>
            @endif
        </div>
        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Preferences & Requests</h5>
            <p class="mb-1"><strong>Bed:</strong> {{ $bookingRequest->bedPreferenceLabel() }}</p>
            <p class="mb-1"><strong>Smoking:</strong> {{ $bookingRequest->smokingPreferenceLabel() }}</p>
            <p class="mb-1"><strong>Arrival:</strong> {{ $bookingRequest->arrivalTimeLabel() }}</p>
            @if($bookingRequest->special_request_options)
                <p class="mb-1"><strong>Options:</strong>
                    @foreach($bookingRequest->special_request_options as $key => $on)
                        @if($on){{ $specialOptions[$key] ?? $key }}@if(!$loop->last), @endif
                        @endif
                    @endforeach
                </p>
            @endif
            @if($bookingRequest->special_requests)<p class="mb-0"><strong>Notes:</strong> {{ $bookingRequest->special_requests }}</p>@endif
        </div>
        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Status Timeline</h5>
            <ul class="list-unstyled mb-0">
                @foreach($bookingRequest->statusHistories as $history)
                <li class="mb-3 pb-3 border-bottom">
                    <strong>{{ $history->newStatusLabel() }}</strong>
                    <span class="text-muted small">· {{ $history->created_at->format('M d, Y H:i') }}</span>
                    @if($history->changedBy)<br><span class="small text-muted">By {{ $history->changedBy->name }}</span>@endif
                    @if($history->notes)<br><span class="small">{{ $history->notes }}</span>@endif
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col-lg-4">
        @if($bookingRequest->quotes->isNotEmpty())
        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Related Quotes</h5>
            @foreach($bookingRequest->quotes as $q)
                <p class="mb-1"><a href="{{ route('admin.quotes.show', $q) }}">{{ $q->quote_number }}</a></p>
            @endforeach
        </div>
        @endif
        <div class="admin-panel-card sticky-top" style="top:1rem">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Manage Request</h5>
            <form method="POST" action="{{ route('admin.hotel-requests.update', $bookingRequest) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" @selected($bookingRequest->status === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3"><label class="form-label">Status note</label><input type="text" name="status_note" class="form-control"></div>
                <div class="mb-3"><label class="form-label">Agent notes</label><textarea name="agent_notes" class="form-control" rows="3">{{ old('agent_notes', $bookingRequest->agent_notes) }}</textarea></div>
                <div class="mb-3">
                    <label class="form-label">Hotel Voucher PDF</label>
                    @if($bookingRequest->voucher_path)<p class="small"><a href="{{ route('admin.booking-files.hotel.document', [$bookingRequest, 'voucher']) }}" target="_blank">Current voucher</a></p>@endif
                    <input type="file" name="voucher" class="form-control" accept="application/pdf">
                </div>
                <div class="mb-3">
                    <label class="form-label">Invoice PDF</label>
                    @if($bookingRequest->invoice_path)<p class="small"><a href="{{ route('admin.booking-files.hotel.document', [$bookingRequest, 'invoice']) }}" target="_blank">Current invoice</a></p>@endif
                    <input type="file" name="invoice" class="form-control" accept="application/pdf">
                </div>
                <div class="mb-3">
                    <label class="form-label">Transfer Voucher PDF</label>
                    @if($bookingRequest->transfer_voucher_path)<p class="small"><a href="{{ route('admin.booking-files.hotel.document', [$bookingRequest, 'transfer_voucher']) }}" target="_blank">Current transfer voucher</a></p>@endif
                    <input type="file" name="transfer_voucher" class="form-control" accept="application/pdf">
                </div>
                <button type="submit" class="btn btn-admin-primary w-100">Save changes</button>
            </form>
        </div>
    </div>
</div>
@endsection
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/flight-booking-admin.css') }}">
@endpush
