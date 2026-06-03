@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')
<div class="col-lg-9">
    <div class="user-profile-wrapper">
        <div class="user-profile-card">
            <div class="d-flex justify-content-between mb-3">
                <h4 class="user-profile-card-title mb-0">Invoice {{ $booking->reference }}</h4>
                <a href="{{ route('my-bookings.invoice.pdf', $booking) }}" class="theme-btn btn-sm">Download PDF</a>
            </div>
            @include('partials.booking-invoice-body', ['booking' => $booking, 'company' => $company, 'issuedAt' => $issuedAt])
            <a href="{{ route('my-bookings.show', $booking) }}" class="btn btn-link mt-3">← Back to booking</a>
        </div>
    </div>
</div>
@endsection
