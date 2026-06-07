@extends('layouts.app')
@section('content')
<div class="user-profile py-120">
    <div class="container">
        <div class="row">
            @include('layouts.publicUserAdmin.sidebar')
            <div class="col-lg-9">
                <div class="user-profile-wrapper">
                    <h4 class="user-profile-title">{{ $booking->reference_number }} <x-hotel-booking-status :status="$booking->status" /></h4>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6"><strong>Hotel</strong><br>{{ $booking->hotel?->name }}</div>
                        <div class="col-md-6"><strong>Room</strong><br>{{ $booking->room?->name ?? '—' }}</div>
                        <div class="col-md-6"><strong>Check-in</strong><br>{{ $booking->check_in_date->format(config('date.display')) }}</div>
                        <div class="col-md-6"><strong>Check-out</strong><br>{{ $booking->check_out_date->format(config('date.display')) }}</div>
                    </div>
                    @if($booking->voucher_path || $booking->invoice_path || $booking->transfer_voucher_path)
                    <h5>Documents</h5>
                    <ul class="list-unstyled mb-4">
                        @if($booking->voucher_path)<li><a href="{{ route('booking-files.hotel.document', [$booking, 'voucher']) }}" target="_blank">Hotel Voucher (PDF)</a></li>@endif
                        @if($booking->invoice_path)<li><a href="{{ route('booking-files.hotel.document', [$booking, 'invoice']) }}" target="_blank">Invoice (PDF)</a></li>@endif
                        @if($booking->transfer_voucher_path)<li><a href="{{ $booking->transferVoucherFileUrl() }}" target="_blank">Transfer Voucher (PDF)</a></li>@endif
                    </ul>
                    @endif
                    @if($booking->agent_notes)<div class="alert alert-light"><strong>Agent notes:</strong> {{ $booking->agent_notes }}</div>@endif
                    <h5>Guests</h5>
                    <p class="mb-1"><strong>Contact:</strong> {{ $booking->lead_guest_name }}</p>
                    <p class="mb-1 text-muted small">{{ $booking->adults }} adult(s)@if($booking->children), {{ $booking->children }} child(ren)@endif@if($booking->infants), {{ $booking->infants }} infant(s)@endif</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
