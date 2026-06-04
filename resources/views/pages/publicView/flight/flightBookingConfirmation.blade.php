@extends('layouts.app')

@section('body-class', 'home-3 fbw-page')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/flight-booking-wizard.css') }}">
@endpush

@section('content')
<div class="site-breadcrumb" style="background: url({{ asset('assets/img/breadcrumb/01.jpg') }})">
    <div class="container">
        <h2 class="breadcrumb-title">Booking Request Received</h2>
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
                    <div class="mb-3">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white" style="width:72px;height:72px;font-size:2rem;background:#3361AC;">
                            <i class="fas fa-check"></i>
                        </span>
                    </div>
                    <h3 class="mb-2" style="color:#162F65;">Thank You</h3>
                    <p class="text-muted mb-4">Your flight booking request has been received.</p>
                    <div class="p-4 mb-3 rounded" style="background:#f4f7fc;">
                        <small class="text-muted d-block mb-1">Booking Reference</small>
                        <h2 class="mb-0" style="color:#162F65; letter-spacing:1px;">{{ $booking->booking_reference }}</h2>
                    </div>
                    <div class="row g-3 mb-4 text-start">
                        <div class="col-md-6">
                            <div class="p-3 rounded h-100" style="background:#f8fafd;">
                                <small class="text-muted d-block">Route</small>
                                <strong>{{ $booking->routeLabel() }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded h-100" style="background:#f8fafd;">
                                <small class="text-muted d-block">Expected Response Time</small>
                                <strong>24 Hours</strong>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted small mb-4">Our travel consultant will contact you at <strong>{{ $booking->email }}</strong> to confirm availability and final fare.</p>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        @auth
                            <a href="{{ route('my-flight-bookings.index') }}" class="fbw-btn-primary">My Flight Bookings</a>
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
