@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')
<div class="col-lg-9">
    <div class="user-profile-card mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
            <div>
                <h4 class="user-profile-card-title mb-1">{{ $booking->reference_number }}</h4>
                <p class="text-muted mb-0">{{ $booking->cruise?->name }} · <x-cruise-booking-status :status="$booking->status" /></p>
            </div>
            <a href="{{ route('my-cruise-bookings.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6"><small class="text-muted">Departure</small><div>{{ $booking->departure_date->format(config('date.display')) }}</div></div>
            <div class="col-md-6"><small class="text-muted">Return</small><div>{{ $booking->return_date?->format(config('date.display')) ?? '—' }}</div></div>
            <div class="col-md-6"><small class="text-muted">Cabin</small><div>{{ $booking->cabin_type ?: '—' }}</div></div>
            <div class="col-md-6"><small class="text-muted">Estimated</small><div>{{ strtoupper($booking->currency) }} {{ number_format($booking->estimated_amount, 2) }}</div></div>
        </div>

        @if($booking->voucher_path || $booking->invoice_path || $booking->ticket_path)
            <h5>Documents</h5>
            <ul class="list-unstyled mb-4">
                @if($booking->voucher_path)<li><a href="{{ route('booking-files.cruise.document', [$booking, 'voucher']) }}" target="_blank">Voucher (PDF)</a></li>@endif
                @if($booking->invoice_path)<li><a href="{{ route('booking-files.cruise.document', [$booking, 'invoice']) }}" target="_blank">Invoice (PDF)</a></li>@endif
                @if($booking->ticket_path)<li><a href="{{ route('booking-files.cruise.document', [$booking, 'ticket']) }}" target="_blank">Cruise Ticket (PDF)</a></li>@endif
            </ul>
        @endif

        @if($booking->agent_notes)
            <h5>Agent Notes</h5>
            <p>{{ $booking->agent_notes }}</p>
        @endif

        <h5>Passengers</h5>
        <ul class="mb-4">
            @forelse($booking->passengers as $passenger)
                <li>{{ $passenger->fullName() }} ({{ ucfirst($passenger->passenger_type) }})</li>
            @empty
                <li class="text-muted">No passenger records.</li>
            @endforelse
        </ul>

        @if($booking->statusHistories->isNotEmpty())
            <h5>Status History</h5>
            <ul class="list-group list-group-flush">
                @foreach($booking->statusHistories as $history)
                    <li class="list-group-item px-0">
                        <strong>{{ ucfirst(str_replace('_', ' ', $history->new_status)) }}</strong>
                        <span class="text-muted small">— {{ $history->created_at->format(config('date.display_datetime')) }}</span>
                        @if($history->notes)<div class="small text-muted">{{ $history->notes }}</div>@endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
