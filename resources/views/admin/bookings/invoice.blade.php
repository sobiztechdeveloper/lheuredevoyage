@extends('layouts.admin.app')

@section('title', 'Invoice '.$booking->reference)

@section('content')
<div class="card p-4">
    <div class="mb-3">
        <a href="{{ route('admin.bookings.invoice.pdf', $booking) }}" class="btn btn-primary btn-sm">Download PDF</a>
        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-link btn-sm">Back to booking</a>
    </div>
    @include('partials.booking-invoice-body', ['booking' => $booking, 'company' => $company, 'issuedAt' => $issuedAt])
</div>
@endsection
