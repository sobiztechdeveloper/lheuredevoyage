@extends('layouts.app')
@section('body-class', 'home-3 fbw-page')
@push('styles')<link rel="stylesheet" href="{{ asset('assets/css/flight-booking-wizard.css') }}">@endpush
@section('content')
<div class="site-breadcrumb" style="background: url({{ asset('assets/img/breadcrumb/01.jpg') }})">
    <div class="container">
        <h2 class="breadcrumb-title">Cruise Quote Request Received</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="active">Confirmation</li>
        </ul>
    </div>
</div>
<div class="py-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="fbw-card text-center">
                    <div class="mb-3"><span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white" style="width:72px;height:72px;font-size:2rem;background:#3361AC;"><i class="fas fa-check"></i></span></div>
                    <h3 class="mb-2" style="color:#162F65;">Thank You</h3>
                    <p class="text-muted mb-4">Your cruise quote request has been received. Our team will prepare a personalised quotation — no payment has been taken.</p>
                    <div class="p-4 mb-3 rounded" style="background:#f4f7fc;">
                        <small class="text-muted d-block mb-1">Request Reference</small>
                        <h2 class="mb-0" style="color:#162F65;">{{ $booking->reference_number }}</h2>
                    </div>
                    <p class="text-muted">Our travel consultant will contact you shortly at <strong>{{ $booking->contact_email }}</strong>.</p>
                    <div class="d-flex flex-wrap gap-2 justify-content-center mt-4">
                        @auth
                            <a href="{{ route('my-cruise-bookings.index') }}" class="fbw-btn-primary">My Cruise Quote Requests</a>
                        @else
                            <a href="{{ route('login') }}" class="fbw-btn-primary">Sign in to track bookings</a>
                        @endauth
                        <a href="{{ route('home') }}" class="fbw-btn-outline">Back To Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
