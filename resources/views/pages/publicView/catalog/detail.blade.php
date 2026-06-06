@extends('layouts.app')

@section('body-class', 'home-3')

@section('content')

<x-catalog-detail-bar :item="$item" :routePrefix="$routePrefix" :label="$catalogLabel" />

<div class="hotel-single py-120">
    <div class="container">
        <div class="listing-wrapper">
            <div class="row">
                <div class="col-lg-8">
                    <x-catalog-detail-body :item="$item" :bookable-type="$bookableType" :route-prefix="$routePrefix" />
                </div>
                <div class="col-lg-4">
                    <div class="booking-sidebar listing-side-content">
                        <h4 class="booking-title">Booking Summary</h4>
                        <img src="{{ $item->image_url }}" class="img-fluid rounded mb-3" alt="{{ $item->title }}">
                        <h5>{{ $item->title }}</h5>
                        <p class="mb-2"><strong>{{ $item->formatted_price }}</strong> / {{ $item->price_unit }}</p>
                        @php
                            $requestBookingRoute = match ($routePrefix) {
                                'hotel' => route('hotel.booking.create', ['hotel' => $item]),
                                'cruise' => route('cruise.booking.create', ['cruise' => $item]),
                                'rentalcar' => route('rentalcar.booking.create', ['rentalCar' => $item]),
                                'travelinsurance' => route('travelinsurance.booking.create', ['travelInsurance' => $item]),
                                default => null,
                            };
                        @endphp
                        @if(! $requestBookingRoute)
                            @php $requestBookingRoute = \App\Support\CatalogUrls::bookUrl($routePrefix, $item); @endphp
                        @endif
                        <a href="{{ $requestBookingRoute }}" class="theme-btn d-block text-center">Proceed to Book</a>
                    </div>
                    @if(isset($siteContact))
                    <div class="booking-sidebar listing-side-content mt-4">
                        <h4 class="booking-title">Need Help?</h4>
                        <ul class="listing-side-list">
                            @if($siteContact->phone ?? null)
                                <li><i class="far fa-phone"></i><a href="tel:{{ $siteContact->phone }}">{{ $siteContact->phone }}</a></li>
                            @endif
                            @if($siteContact->email ?? null)
                                <li><i class="far fa-envelope"></i><a href="mailto:{{ $siteContact->email }}">{{ $siteContact->email }}</a></li>
                            @endif
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
