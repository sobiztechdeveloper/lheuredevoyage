@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')
<div class="col-lg-9">
    <div class="user-profile-card mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
            <div>
                <h4 class="user-profile-card-title mb-1">{{ $booking->reference_number }}</h4>
                <p class="text-muted mb-0">{{ $booking->rentalCar?->name }} · <x-car-booking-status :status="$booking->status" /></p>
            </div>
            <a href="{{ route('my-car-bookings.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6"><small class="text-muted">Pickup</small><div>{{ $booking->pickup_location }}</div></div>
            <div class="col-md-6"><small class="text-muted">Drop-off</small><div>{{ $booking->dropoff_location ?: $booking->pickup_location }}</div></div>
            <div class="col-md-6"><small class="text-muted">Pickup Date</small><div>{{ $booking->pickup_date->format(config('date.display')) }}</div></div>
            <div class="col-md-6"><small class="text-muted">Return Date</small><div>{{ $booking->return_date->format(config('date.display')) }}</div></div>
        </div>

        @if($booking->voucher_path || $booking->invoice_path || $booking->rental_agreement_path)
            <h5>Documents</h5>
            <ul class="list-unstyled mb-4">
                @if($booking->voucher_path)<li><a href="{{ route('booking-files.car.document', [$booking, 'voucher']) }}" target="_blank">Voucher (PDF)</a></li>@endif
                @if($booking->invoice_path)<li><a href="{{ route('booking-files.car.document', [$booking, 'invoice']) }}" target="_blank">Invoice (PDF)</a></li>@endif
                @if($booking->rental_agreement_path)<li><a href="{{ route('booking-files.car.document', [$booking, 'rental_agreement']) }}" target="_blank">Rental Agreement (PDF)</a></li>@endif
            </ul>
        @endif

        @if($booking->agent_notes)
            <h5>Agent Notes</h5>
            <p>{{ $booking->agent_notes }}</p>
        @endif

        <h5>Drivers</h5>
        <ul class="mb-0">
            @forelse($booking->drivers as $driver)
                <li>{{ $driver->fullName() }}</li>
            @empty
                <li class="text-muted">No driver records available.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
