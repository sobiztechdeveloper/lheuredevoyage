@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')
<div class="col-lg-9">
    <div class="user-profile-card mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
            <div>
                <h4 class="user-profile-card-title mb-1">{{ $booking->booking_reference }}</h4>
                <p class="text-muted mb-0">{{ $booking->routeLabel() }} · <span class="badge bg-primary">{{ $booking->statusLabel() }}</span></p>
            </div>
            <a href="{{ route('my-flight-bookings.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>
        </div>

        <h5 class="mt-4">Flight Information</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-6"><small class="text-muted">Departure</small><div>{{ $booking->departure_date->format('M d, Y') }}</div></div>
            <div class="col-md-6"><small class="text-muted">Return</small><div>{{ $booking->return_date?->format('M d, Y') ?? '—' }}</div></div>
            <div class="col-md-6"><small class="text-muted">Cabin</small><div>{{ ucfirst(str_replace('_', ' ', $booking->cabin_class)) }}</div></div>
            <div class="col-md-6"><small class="text-muted">Estimated Fare</small><div>{{ strtoupper($booking->currency) }} {{ number_format($booking->estimated_price, 0) }}</div></div>
        </div>

        <h5>Passenger Information</h5>
        @foreach($booking->passengers as $passenger)
            <div class="border rounded p-3 mb-3">
                <strong>{{ $passenger->fullName() }}</strong> <span class="text-muted">({{ ucfirst($passenger->passenger_type) }})</span>
                <div class="small text-muted mt-1">Passport: {{ $passenger->passport_number }} · {{ $passenger->nationality }}</div>
                @if($passenger->passport_file)
                    <div class="mt-2">
                        @if($passenger->isPassportImage())
                            <img src="{{ route('booking-files.flight.passport', [$booking, $passenger]) }}" alt="Passport" class="img-thumbnail" style="max-height:120px;">
                        @else
                            <a href="{{ route('booking-files.flight.passport', [$booking, $passenger]) }}" target="_blank" class="btn btn-sm btn-outline-primary">View Passport Copy (PDF)</a>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach

        @if($booking->agent_notes)
            <h5 class="mt-4">Agent Notes</h5>
            <p>{{ $booking->agent_notes }}</p>
        @endif

        @if($booking->ticket_path || $booking->invoice_path)
            <h5 class="mt-4">Documents</h5>
            <div class="d-flex flex-wrap gap-2">
                @if($booking->ticket_path)
                    <a href="{{ route('booking-files.flight.document', [$booking, 'ticket']) }}" target="_blank" class="btn btn-sm btn-primary">Ticket PDF</a>
                @endif
                @if($booking->invoice_path)
                    <a href="{{ route('booking-files.flight.document', [$booking, 'invoice']) }}" target="_blank" class="btn btn-sm btn-outline-primary">Invoice PDF</a>
                @endif
            </div>
        @endif

        <h5 class="mt-4">Status History</h5>
        <ul class="list-group list-group-flush">
            @foreach($booking->statusHistories as $history)
                <li class="list-group-item px-0">
                    <strong>{{ ucfirst(str_replace('_', ' ', $history->status)) }}</strong>
                    <span class="text-muted small">— {{ $history->created_at->format('M d, Y H:i') }}</span>
                    @if($history->notes)<div class="small text-muted">{{ $history->notes }}</div>@endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
