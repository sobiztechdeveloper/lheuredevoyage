@extends('layouts.app')

@section('body-class', 'home-3')

@section('content')
<div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/01.jpg)">
    <div class="container">
        <h2 class="breadcrumb-title">Flight Booking Enquiry</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            @if($search)
                <li><a href="{{ route('flight.search.results', $search) }}">Flight Search</a></li>
            @endif
            <li class="active">Enquiry</li>
        </ul>
    </div>
</div>

<div class="flight-booking py-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="booking-widget">
                    <h4 class="booking-widget-title">Flight Enquiry</h4>
                    <p class="mb-4">
                        {{ $result->airline }} — {{ $result->flight_number }}<br>
                        {{ $result->from_destination }} → {{ $result->to_destination }}<br>
                        {{ $result->departure_at->format(config('date.display_datetime')) }} — {{ $result->formattedDisplayPrice() }}
                    </p>
                    <div class="booking-form">
                        <form action="{{ route('flight.booking.store', $result) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Passenger Count</label>
                                        <div class="form-group-icon">
                                            <input type="number" name="passenger_count" class="form-control" min="1" max="9"
                                                value="{{ old('passenger_count', $search?->adult ?? 1) }}" required>
                                            <i class="far fa-users"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Contact Name</label>
                                        <div class="form-group-icon">
                                            <input type="text" name="contact_name" class="form-control" value="{{ old('contact_name') }}" required>
                                            <i class="far fa-user"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <div class="form-group-icon">
                                            <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()?->email) }}" required>
                                            <i class="far fa-envelopes"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <div class="form-group-icon">
                                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                                            <i class="far fa-phone"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>WhatsApp</label>
                                        <div class="form-group-icon">
                                            <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp') }}">
                                            <i class="fab fa-whatsapp"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Notes</label>
                                        <textarea name="notes" class="form-control" rows="4" placeholder="Special requests or notes">{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <button type="submit" class="theme-btn">Submit Enquiry<i class="fas fa-arrow-circle-right"></i></button>
                                    @if($search)
                                        <a href="{{ route('flight.search.results', $search) }}" class="theme-btn style-outline ms-2">Back to Results</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
