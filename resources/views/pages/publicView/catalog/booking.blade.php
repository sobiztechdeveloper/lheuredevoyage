@extends('layouts.app')

@section('body-class', 'home-3')

@section('content')

<div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/05.jpg)">
    <div class="container">
        <h2 class="breadcrumb-title">Book {{ $catalogLabel }}</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route($routePrefix) }}">{{ $catalogLabel }}</a></li>
            <li class="active">Booking</li>
        </ul>
    </div>
</div>

<div class="hotel-booking py-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="user-profile-card">
                    <h4 class="mb-4">Booking Details</h4>
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <x-booking-form :item="$item" :bookable-type="$bookableType" />
                </div>
            </div>
            <div class="col-lg-4">
                <div class="booking-sidebar listing-side-content">
                    <h4 class="mb-30">Booking Summary</h4>
                    <img src="{{ $item->image_url }}" class="img-fluid rounded mb-3" alt="">
                    <h5>{{ $item->title }}</h5>
                    @if($item->location ?? $item->destination ?? null)
                        <p><i class="far fa-location-dot"></i> {{ $item->location ?? $item->destination }}</p>
                    @endif
                    <p><strong>{{ $item->formatted_price }}</strong> / {{ $item->price_unit }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
