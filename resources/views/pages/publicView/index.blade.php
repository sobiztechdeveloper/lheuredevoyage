@extends('layouts.app')

@section('body-class', 'home-3')

@section('styles')

@endsection

@section('content')

<!-- hero area -->
<div class="hero-section">
    @php $primaryHero = ($heroSections ?? collect())->first(); @endphp
    <div class="hero-single" style="background: url({{ $primaryHero?->image_url ?? asset('assets/img/hero/hero-2x.jpg') }})">
        <div class="container">
            @if($primaryHero)
            @include('partials.cms.hero-content', ['hero' => $primaryHero])
            @endif
            <!-- search area -->
            <div class="search-area">
                <div class="container">
                    <div class="search-wrapper">
                        <!-- search header -->
                        <div class="search-header">
                            <div class="search-nav">
                                <ul class="nav nav-pills" role="tablist">
                                    <!-- Holiday Package -->
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="pills-tab-4" data-bs-toggle="pill"
                                            data-bs-target="#pills-4" type="button" role="tab"
                                            aria-controls="pills-4" aria-selected="false"><i
                                                class="far fa-car-building"></i>Holiday
                                            Package</button>
                                    </li>
                                    <!-- Flights -->
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-tab-1" data-bs-toggle="pill"
                                            data-bs-target="#pills-1" type="button" role="tab"
                                            aria-controls="pills-1" aria-selected="true"><i
                                                class="far fa-plane-departure"></i>Flights</button>
                                    </li>
                                    <!-- Hotels -->
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-tab-2" data-bs-toggle="pill"
                                            data-bs-target="#pills-2" type="button" role="tab"
                                            aria-controls="pills-2" aria-selected="false"><i
                                                class="far fa-hotel"></i>Hotels</button>
                                    </li>
                                    <!-- Cars -->
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-tab-5" data-bs-toggle="pill"
                                            data-bs-target="#pills-5" type="button" role="tab"
                                            aria-controls="pills-5" aria-selected="false"><i
                                                class="far fa-car"></i>Cars</button>
                                    </li>
                                    <!-- Cruises -->
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-tab-6" data-bs-toggle="pill"
                                            data-bs-target="#pills-6" type="button" role="tab"
                                            aria-controls="pills-6" aria-selected="false"><i
                                                class="far fa-ship"></i>Cruises</button>
                                    </li>
                                    <!-- Travel Insurances -->
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-tab-7" data-bs-toggle="pill"
                                            data-bs-target="#pills-7" type="button" role="tab"
                                            aria-controls="pills-7" aria-selected="false"><i
                                                class="far fa-earth-americas"></i>Insurances</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- search header end -->

                        <!-- tab content -->
                        <div class="tab-content" id="pills-tabContent">

                            <!-- holiday tab 4 -->
                            <div class="tab-pane fade show active" id="pills-4" role="tabpanel"
                                aria-labelledby="pills-tab-4" tabindex="0">
                                <div class="holiday-search">
                                    <div class="search-form">
                                        @include('partials.catalog.holiday-package-search-form')
                                    </div>
                                </div>
                            </div>

                            <!-- flight tab 1 -->
                            <div class="tab-pane fade" id="pills-1" role="tabpanel"
                                aria-labelledby="pills-tab-1" tabindex="0">
                                <div class="flight-search ft-group">
                                    <div class="search-form">
                                        <form action="{{ route('flight.search.submit') }}" method="POST">
                                            @csrf
                                            <!-- flight type -->
                                            <div class="flight-type">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" checked
                                                        value="one-way" name="flight-type" id="home-flight-type1">
                                                    <label class="form-check-label" for="home-flight-type1">
                                                        One Way
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                        value="round-way" name="flight-type" id="home-flight-type2">
                                                    <label class="form-check-label" for="home-flight-type2">
                                                        Round Trip
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                        value="multi-city" name="flight-type" id="home-flight-type3">
                                                    <label class="form-check-label" for="home-flight-type3">
                                                        Multi City
                                                    </label>
                                                </div>
                                            </div>
                                            <!-- flight type end -->

                                            <!-- flight search -->
                                            <div class="flight-search-wrapper">
                                                <div class="flight-search-content">
                                                    <!-- flight search content -->
                                                    <div class="flight-search-item">
                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                                <x-destination-autocomplete
                                                                    name="from-destination"
                                                                    context="flight_from"
                                                                    format="airport"
                                                                    :value="old('from-destination', old('from_destination'))"
                                                                    label="From"
                                                                    icon="fal fa-plane-departure"
                                                                    input-class="form-control swap-from"
                                                                    placeholder="City or airport" />
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <x-destination-autocomplete
                                                                    name="to-destination"
                                                                    context="flight_to"
                                                                    format="airport"
                                                                    :value="old('to-destination', old('to_destination'))"
                                                                    label="To"
                                                                    icon="fal fa-plane-arrival"
                                                                    input-class="form-control swap-to"
                                                                    placeholder="City or airport"
                                                                    :swap="true" />
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="form-group">
                                                                    <div class="search-form-date">
                                                                        <div class="search-form-journey">
                                                                            <label>Journey Date</label>
                                                                            <div class="form-group-icon">
                                                                                <input type="text"
                                                                                    name="journey-date"
                                                                                    class="form-control date-picker journey-date"
                                                                                    value="{{ old('journey-date', old('journey_date')) }}">
                                                                                <i
                                                                                    class="fal fa-calendar-days"></i>
                                                                            </div>
                                                                            <p class="journey-day-name"></p>
                                                                        </div>
                                                                        <div class="search-form-return">
                                                                            <label>Return Date</label>
                                                                            <div class="form-group-icon">
                                                                                <input type="text"
                                                                                    name="return-date"
                                                                                    class="form-control date-picker return-date"
                                                                                    value="{{ old('return-date', old('return_date')) }}">
                                                                                <i class="fal fa-calendar-days"></i>
                                                                            </div>
                                                                            <p class="return-day-name"></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="form-group dropdown passenger-box">
                                                                    <div class="passenger-class" role="menu"
                                                                        data-bs-toggle="dropdown"
                                                                        aria-expanded="false">
                                                                        <label>Passenger, Class</label>
                                                                        <div class="form-group-icon">
                                                                            <div class="passenger-total"><span
                                                                                    class="passenger-total-amount">2</span>
                                                                                Passenger
                                                                            </div>
                                                                            <i class="fal fa-user-tie-hair"></i>
                                                                        </div>
                                                                        <p class="passenger-class-name">Business
                                                                        </p>
                                                                    </div>
                                                                    <div
                                                                        class="dropdown-menu dropdown-menu-end">
                                                                        <div class="dropdown-item">
                                                                            <div class="passenger-item">
                                                                                <div class="passenger-info">
                                                                                    <h6>Adults</h6>
                                                                                    <p>12+ Years</p>
                                                                                </div>
                                                                                <div class="passenger-qty">
                                                                                    <button type="button"
                                                                                        class="minus-btn"><i
                                                                                            class="far fa-minus"></i></button>
                                                                                    <input type="text"
                                                                                        name="adult"
                                                                                        class="qty-amount passenger-adult"
                                                                                        value="2" readonly>
                                                                                    <button type="button"
                                                                                        class="plus-btn"><i
                                                                                            class="far fa-plus"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="dropdown-item">
                                                                            <div class="passenger-item">
                                                                                <div class="passenger-info">
                                                                                    <h6>Children</h6>
                                                                                    <p>2-12 Years</p>
                                                                                </div>
                                                                                <div class="passenger-qty">
                                                                                    <button type="button"
                                                                                        class="minus-btn"><i
                                                                                            class="far fa-minus"></i></button>
                                                                                    <input type="text"
                                                                                        name="children"
                                                                                        class="qty-amount passenger-children"
                                                                                        value="0" readonly>
                                                                                    <button type="button"
                                                                                        class="plus-btn"><i
                                                                                            class="far fa-plus"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="dropdown-item">
                                                                            <div class="passenger-item">
                                                                                <div class="passenger-info">
                                                                                    <h6>Infant</h6>
                                                                                    <p>Below 2 Years</p>
                                                                                </div>
                                                                                <div class="passenger-qty">
                                                                                    <button type="button"
                                                                                        class="minus-btn"><i
                                                                                            class="far fa-minus"></i></button>
                                                                                    <input type="text"
                                                                                        name="infant"
                                                                                        class="qty-amount passenger-infant"
                                                                                        value="0" readonly>
                                                                                    <button type="button"
                                                                                        class="plus-btn"><i
                                                                                            class="far fa-plus"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="dropdown-item">
                                                                            <h6 class="mb-3 mt-2">Cabin Class
                                                                            </h6>
                                                                            <div class="passenger-class-info">
                                                                                <div class="form-check">
                                                                                    <input
                                                                                        class="form-check-input"
                                                                                        type="radio"
                                                                                        value="economy"
                                                                                        name="cabin_class"
                                                                                        id="cabin-class1">
                                                                                    <label
                                                                                        class="form-check-label"
                                                                                        for="cabin-class1">
                                                                                        Economy
                                                                                    </label>
                                                                                </div>
                                                                                <div class="form-check">
                                                                                    <input
                                                                                        class="form-check-input"
                                                                                        checked type="radio"
                                                                                        value="business"
                                                                                        name="cabin_class"
                                                                                        id="cabin-class2">
                                                                                    <label
                                                                                        class="form-check-label"
                                                                                        for="cabin-class2">
                                                                                        Business
                                                                                    </label>
                                                                                </div>
                                                                                <div class="form-check">
                                                                                    <input
                                                                                        class="form-check-input"
                                                                                        type="radio"
                                                                                        value="first"
                                                                                        name="cabin_class"
                                                                                        id="cabin-class3">
                                                                                    <label
                                                                                        class="form-check-label"
                                                                                        for="cabin-class3">
                                                                                        First Class
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- flight search content end -->

                                                    <!-- flight-multicity-item -->
                                                    <div
                                                        class="flight-search-item flight-multicity-item have-to-clone">
                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                                <x-destination-autocomplete
                                                                    name="from-destination"
                                                                    context="flight_from"
                                                                    format="airport"
                                                                    label="From"
                                                                    icon="fal fa-plane-departure"
                                                                    input-class="form-control swap-from"
                                                                    placeholder="City or airport"
                                                                    :disabled="true" />
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <x-destination-autocomplete
                                                                    name="to-destination"
                                                                    context="flight_to"
                                                                    format="airport"
                                                                    label="To"
                                                                    icon="fal fa-plane-arrival"
                                                                    input-class="form-control swap-to"
                                                                    placeholder="City or airport"
                                                                    :swap="true"
                                                                    :disabled="true" />
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="form-group">
                                                                    <div class="search-form-date">
                                                                        <div class="search-form-journey">
                                                                            <label>Journey Date</label>
                                                                            <div class="form-group-icon">
                                                                                <input type="text"
                                                                                    name="journey-date"
                                                                                    class="form-control date-picker journey-date"
                                                                                    disabled>
                                                                                <i
                                                                                    class="fal fa-calendar-days"></i>
                                                                            </div>
                                                                            <p class="journey-day-name"></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <div class="form-group">
                                                                    <div class="multicity-btn">
                                                                        <i class="fal fa-plus-circle"></i> Add
                                                                        Another Flight
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- flight multicity end -->
                                                </div>
                                                <div class="search-btn">
                                                    <button type="submit" class="theme-btn"><span
                                                            class="far fa-search"></span>Search Now</button>
                                                </div>
                                            </div>
                                            <!-- flight search end -->
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Car tab 5 -->
                            <div class="tab-pane fade" id="pills-5" role="tabpanel"
                                aria-labelledby="pills-tab-5" tabindex="0">
                                <div class="car-search">
                                    <div class="search-form">
                                        <form method="GET" action="{{ route('rentalcar') }}">
                                            <div class="car-search-wrapper">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <x-destination-autocomplete
                                                            name="destination"
                                                            context="car_pickup"
                                                            label="Picking Up"
                                                            icon="fal fa-location-dot"
                                                            placeholder="City, airport or address" />
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <div class="search-form-date">
                                                                <div class="search-form-journey">
                                                                    <label>Pick Up date</label>
                                                                    <div class="form-group-icon">
                                                                        <input type="text" name="pickup-date"
                                                                            class="form-control date-picker journey-date">
                                                                        <i class="fal fa-calendar-days"></i>
                                                                    </div>
                                                                    <p class="journey-day-name"></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label>Pick Up Time</label>
                                                            <div class="form-group-icon">
                                                                <input type="text" name="pick-up-time"
                                                                    class="form-control time-picker"
                                                                    value="11:00 PM">
                                                                <i class="fal fa-clock"></i>
                                                            </div>
                                                            <p>Car Arrival Time</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <x-destination-autocomplete
                                                            class="mt-lg-4"
                                                            name="dropoff"
                                                            context="car_dropoff"
                                                            label="Drop Off"
                                                            icon="fal fa-location-dot"
                                                            placeholder="Drop-off location" />
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group mt-lg-4">
                                                            <div class="search-form-date">
                                                                <div class="search-form-journey">
                                                                    <label>Drop Off date</label>
                                                                    <div class="form-group-icon">
                                                                        <input type="text" name="return-date"
                                                                            class="form-control date-picker return-date">
                                                                        <i class="fal fa-calendar-days"></i>
                                                                    </div>
                                                                    <p class="return-day-name"></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group mt-lg-4">
                                                            <label>Drop Off Time</label>
                                                            <div class="form-group-icon">
                                                                <input type="text" name="drop-off-time"
                                                                    class="form-control time-picker"
                                                                    value="11:00 PM">
                                                                <i class="fal fa-clock"></i>
                                                            </div>
                                                            <p>Car Drop Off Time</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="search-btn">
                                                    <button type="submit" class="theme-btn"><span
                                                            class="far fa-search"></span>Search Now</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- cruise tab 6 OK-->
                            <div class="tab-pane fade" id="pills-6" role="tabpanel"
                                aria-labelledby="pills-tab-6" tabindex="0">
                                <div class="cruise-search">
                                    <div class="search-form">
                                        <form method="GET" action="{{ route('cruise') }}">
                                            <div class="cruise-search-wrapper">
                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <x-destination-autocomplete
                                                            name="destination"
                                                            context="cruise"
                                                            label="Destination"
                                                            icon="fal fa-earth-americas"
                                                            placeholder="Region or port" />
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <x-cruise-search-month />
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <x-cruise-search-line />
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group dropdown passenger-box">
                                                            <div class="passenger-class" role="menu"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <label>Travelers</label>
                                                                <div class="form-group-icon">
                                                                    <div class="passenger-total">
                                                                        <span
                                                                            class="passenger-total-amount">2</span>
                                                                        Travelers
                                                                    </div>
                                                                    <i class="fal fa-user-tie-hair"></i>
                                                                </div>
                                                                <p class="passenger-class-name">In Cabin</p>
                                                            </div>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <div class="dropdown-item">
                                                                    <div class="passenger-item">
                                                                        <div class="passenger-info">
                                                                            <h6>Adults</h6>
                                                                            <p>12+ Years</p>
                                                                        </div>
                                                                        <div class="passenger-qty">
                                                                            <button type="button"
                                                                                class="minus-btn"><i
                                                                                    class="far fa-minus"></i></button>
                                                                            <input type="text" name="adult"
                                                                                class="qty-amount passenger-adult"
                                                                                value="2" readonly>
                                                                            <button type="button"
                                                                                class="plus-btn"><i
                                                                                    class="far fa-plus"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dropdown-item">
                                                                    <div class="passenger-item">
                                                                        <div class="passenger-info">
                                                                            <h6>Children</h6>
                                                                            <p>2-12 Years</p>
                                                                        </div>
                                                                        <div class="passenger-qty">
                                                                            <button type="button"
                                                                                class="minus-btn"><i
                                                                                    class="far fa-minus"></i></button>
                                                                            <input type="text" name="children"
                                                                                class="qty-amount passenger-children"
                                                                                value="0" readonly>
                                                                            <button type="button"
                                                                                class="plus-btn"><i
                                                                                    class="far fa-plus"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dropdown-item">
                                                                    <div class="passenger-item">
                                                                        <div class="passenger-info">
                                                                            <h6>Infant</h6>
                                                                            <p>Below 2 Years</p>
                                                                        </div>
                                                                        <div class="passenger-qty">
                                                                            <button type="button"
                                                                                class="minus-btn"><i
                                                                                    class="far fa-minus"></i></button>
                                                                            <input type="text" name="infant"
                                                                                class="qty-amount passenger-infant"
                                                                                value="0" readonly>
                                                                            <button type="button"
                                                                                class="plus-btn"><i
                                                                                    class="far fa-plus"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dropdown-item">
                                                                    <h6 class="mb-3 mt-2">Cruise Class</h6>
                                                                    <div class="passenger-class-info">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="radio" value="In Cabin"
                                                                                name="cruise-class"
                                                                                id="cruise-class1">
                                                                            <label class="form-check-label"
                                                                                for="cruise-class1">
                                                                                In Cabin
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                checked type="radio"
                                                                                value="In Chair"
                                                                                name="cruise-class"
                                                                                id="cruise-class2">
                                                                            <label class="form-check-label"
                                                                                for="cruise-class2">
                                                                                In Chair
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="radio"
                                                                                value="In First Class"
                                                                                name="cruise-class"
                                                                                id="cruise-class3">
                                                                            <label class="form-check-label"
                                                                                for="cruise-class3">
                                                                                In First Class
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="search-btn">
                                                    <button type="submit" class="theme-btn"><span
                                                            class="far fa-search"></span>Search Now</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Travel Insurence tab 7 -->
                            <div class="tab-pane fade" id="pills-7" role="tabpanel"
                                aria-labelledby="pills-tab-7" tabindex="0">
                                <div class="tour-search">
                                    <div class="search-form">
                                        <form method="GET" action="{{ route('travelinsurance') }}">
                                            <div class="tour-search-wrapper">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <x-destination-autocomplete
                                                            name="destination"
                                                            context="insurance"
                                                            label="Destination"
                                                            icon="fal fa-earth-americas"
                                                            placeholder="Country or region" />
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <div class="search-form-date">
                                                                <div class="search-form-journey">
                                                                    <label>Journey Date</label>
                                                                    <div class="form-group-icon">
                                                                        <input type="text" name="journey-date"
                                                                            class="form-control date-picker journey-date">
                                                                        <i class="fal fa-calendar-days"></i>
                                                                    </div>
                                                                    <p class="journey-day-name"></p>
                                                                </div>
                                                                <div class="search-form-return">
                                                                    <label>Return Date</label>
                                                                    <div class="form-group-icon">
                                                                        <input type="text" name="return-date"
                                                                            class="form-control date-picker return-date">
                                                                    </div>
                                                                    <p class="return-day-name"></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <x-insurance-traveler-picker />
                                                    </div>
                                                </div>
                                                <div class="search-btn">
                                                    <button type="submit" class="theme-btn"><span
                                                            class="far fa-search"></span>Search Now</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- hotel tab 2 -->
                            <div class="tab-pane fade" id="pills-2" role="tabpanel"
                                aria-labelledby="pills-tab-2" tabindex="0">
                                <div class="hotel-search">
                                    <div class="search-form">
                                        <form method="POST" action="{{ route('hotel.search.submit') }}">
                                            @csrf
                                            <div class="hotel-search-wrapper">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <x-destination-autocomplete
                                                            name="destination"
                                                            context="hotel"
                                                            label="Destination"
                                                            icon="fal fa-earth-americas"
                                                            placeholder="City, hotel or area" />
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <div class="search-form-date">
                                                                <div class="search-form-journey">
                                                                    <label>Check In</label>
                                                                    <div class="form-group-icon">
                                                                        <input type="text" name="journey-date"
                                                                            class="form-control date-picker journey-date">
                                                                        <i class="fal fa-calendar-days"></i>
                                                                    </div>
                                                                    <p class="journey-day-name"></p>
                                                                </div>
                                                                <div class="search-form-return">
                                                                    <label>Check Out</label>
                                                                    <div class="form-group-icon">
                                                                        <input type="text" name="return-date"
                                                                            class="form-control date-picker return-date">
                                                                    </div>
                                                                    <p class="return-day-name"></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group dropdown passenger-box">
                                                            <div class="passenger-class" role="menu"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <label>Rooms, Guests</label>
                                                                <div class="form-group-icon">
                                                                    <div class="passenger-total">
                                                                        <span
                                                                            class="passenger-total-room">2</span>
                                                                        Rooms,
                                                                        <span
                                                                            class="passenger-total-amount">2</span>
                                                                        Guests
                                                                    </div>
                                                                    <i class="fal fa-user-tie-hair"></i>
                                                                </div>
                                                                <p class="passenger-class-name">Double Room</p>
                                                            </div>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <div class="dropdown-item">
                                                                    <div class="passenger-item">
                                                                        <div class="passenger-info">
                                                                            <h6>Adults</h6>
                                                                            <p>12+ Years</p>
                                                                        </div>
                                                                        <div class="passenger-qty">
                                                                            <button type="button"
                                                                                class="minus-btn"><i
                                                                                    class="far fa-minus"></i></button>
                                                                            <input type="text" name="adult"
                                                                                class="qty-amount passenger-adult"
                                                                                value="2" readonly>
                                                                            <button type="button"
                                                                                class="plus-btn"><i
                                                                                    class="far fa-plus"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dropdown-item">
                                                                    <div class="passenger-item">
                                                                        <div class="passenger-info">
                                                                            <h6>Children</h6>
                                                                            <p>2-12 Years</p>
                                                                        </div>
                                                                        <div class="passenger-qty">
                                                                            <button type="button"
                                                                                class="minus-btn"><i
                                                                                    class="far fa-minus"></i></button>
                                                                            <input type="text" name="children"
                                                                                class="qty-amount passenger-children"
                                                                                value="0" readonly>
                                                                            <button type="button"
                                                                                class="plus-btn"><i
                                                                                    class="far fa-plus"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dropdown-item">
                                                                    <div class="passenger-item">
                                                                        <div class="passenger-info">
                                                                            <h6>Infant</h6>
                                                                            <p>Below 2 Years</p>
                                                                        </div>
                                                                        <div class="passenger-qty">
                                                                            <button type="button"
                                                                                class="minus-btn"><i
                                                                                    class="far fa-minus"></i></button>
                                                                            <input type="text" name="infant"
                                                                                class="qty-amount passenger-infant"
                                                                                value="0" readonly>
                                                                            <button type="button"
                                                                                class="plus-btn"><i
                                                                                    class="far fa-plus"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dropdown-item">
                                                                    <div class="passenger-item">
                                                                        <div class="passenger-info">
                                                                            <h6>Rooms</h6>
                                                                        </div>
                                                                        <div class="passenger-qty">
                                                                            <button type="button"
                                                                                class="minus-btn"><i
                                                                                    class="far fa-minus"></i></button>
                                                                            <input type="text" name="room"
                                                                                class="qty-amount passenger-room"
                                                                                value="2" readonly>
                                                                            <button type="button"
                                                                                class="plus-btn"><i
                                                                                    class="far fa-plus"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dropdown-item">
                                                                    <h6 class="mb-3 mt-2">Room Type</h6>
                                                                    <div class="passenger-class-info">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="radio" value="Single Room"
                                                                                name="room-type"
                                                                                id="room-type1">
                                                                            <label class="form-check-label"
                                                                                for="room-type1">
                                                                                Single Room
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                checked type="radio"
                                                                                value="Double Room"
                                                                                name="room-type"
                                                                                id="room-type2">
                                                                            <label class="form-check-label"
                                                                                for="room-type2">
                                                                                Double Room
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="radio" value="Deluxe Room"
                                                                                name="room-type"
                                                                                id="room-type3">
                                                                            <label class="form-check-label"
                                                                                for="room-type3">
                                                                                Deluxe Room
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="search-btn">
                                                    <button type="submit" class="theme-btn"><span
                                                            class="far fa-search"></span>Search Now</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- tab 3 -->
                            <!-- <div class="tab-pane fade" id="pills-3" role="tabpanel"
                                aria-labelledby="pills-tab-3" tabindex="0">
                                <div class="activity-search">
                                    <div class="search-form">
                                        <form action="{{ route('rentalcar.search') }}">
                                            <div class="activity-search-wrapper">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label>Location</label>
                                                            <div class="form-group-icon">
                                                                <input type="text" name="location"
                                                                    class="form-control"
                                                                    value="New York, United States">
                                                                <i class="fal fa-earth-americas"></i>
                                                            </div>
                                                            <p>Where Are You Going?</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <div class="search-form-date">
                                                                <div class="search-form-journey">
                                                                    <label>Check In</label>
                                                                    <div class="form-group-icon">
                                                                        <input type="text" name="journey-date"
                                                                            class="form-control date-picker journey-date">
                                                                        <i class="fal fa-calendar-days"></i>
                                                                    </div>
                                                                    <p class="journey-day-name"></p>
                                                                </div>
                                                                <div class="search-form-return">
                                                                    <label>Check Out</label>
                                                                    <div class="form-group-icon">
                                                                        <input type="text" name="return-date"
                                                                            class="form-control date-picker return-date">
                                                                    </div>
                                                                    <p class="return-day-name"></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group dropdown passenger-box">
                                                            <div class="passenger-class" role="menu"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <label>Rooms, Guests</label>
                                                                <div class="form-group-icon">
                                                                    <div class="passenger-total">
                                                                        <span
                                                                            class="passenger-total-room">2</span>
                                                                        Rooms,
                                                                        <span
                                                                            class="passenger-total-amount">2</span>
                                                                        Guests
                                                                    </div>
                                                                    <i class="fal fa-user-tie-hair"></i>
                                                                </div>
                                                                <p class="passenger-class-name">Double Room</p>
                                                            </div>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <div class="dropdown-item">
                                                                    <div class="passenger-item">
                                                                        <div class="passenger-info">
                                                                            <h6>Adults</h6>
                                                                            <p>12+ Years</p>
                                                                        </div>
                                                                        <div class="passenger-qty">
                                                                            <button type="button"
                                                                                class="minus-btn"><i
                                                                                    class="far fa-minus"></i></button>
                                                                            <input type="text" name="adult"
                                                                                class="qty-amount passenger-adult"
                                                                                value="2" readonly>
                                                                            <button type="button"
                                                                                class="plus-btn"><i
                                                                                    class="far fa-plus"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dropdown-item">
                                                                    <div class="passenger-item">
                                                                        <div class="passenger-info">
                                                                            <h6>Children</h6>
                                                                            <p>2-12 Years</p>
                                                                        </div>
                                                                        <div class="passenger-qty">
                                                                            <button type="button"
                                                                                class="minus-btn"><i
                                                                                    class="far fa-minus"></i></button>
                                                                            <input type="text" name="children"
                                                                                class="qty-amount passenger-children"
                                                                                value="0" readonly>
                                                                            <button type="button"
                                                                                class="plus-btn"><i
                                                                                    class="far fa-plus"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dropdown-item">
                                                                    <div class="passenger-item">
                                                                        <div class="passenger-info">
                                                                            <h6>Infant</h6>
                                                                            <p>Below 2 Years</p>
                                                                        </div>
                                                                        <div class="passenger-qty">
                                                                            <button type="button"
                                                                                class="minus-btn"><i
                                                                                    class="far fa-minus"></i></button>
                                                                            <input type="text" name="infant"
                                                                                class="qty-amount passenger-infant"
                                                                                value="0" readonly>
                                                                            <button type="button"
                                                                                class="plus-btn"><i
                                                                                    class="far fa-plus"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dropdown-item">
                                                                    <div class="passenger-item">
                                                                        <div class="passenger-info">
                                                                            <h6>Rooms</h6>
                                                                        </div>
                                                                        <div class="passenger-qty">
                                                                            <button type="button"
                                                                                class="minus-btn"><i
                                                                                    class="far fa-minus"></i></button>
                                                                            <input type="text" name="room"
                                                                                class="qty-amount passenger-room"
                                                                                value="2" readonly>
                                                                            <button type="button"
                                                                                class="plus-btn"><i
                                                                                    class="far fa-plus"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dropdown-item">
                                                                    <h6 class="mb-3 mt-2">Room Type</h6>
                                                                    <div class="passenger-class-info">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="radio" value="Single Room"
                                                                                name="room-type"
                                                                                id="room-type4">
                                                                            <label class="form-check-label"
                                                                                for="room-type4">
                                                                                Single Room
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                checked type="radio"
                                                                                value="Double Room"
                                                                                name="room-type"
                                                                                id="room-type5">
                                                                            <label class="form-check-label"
                                                                                for="room-type5">
                                                                                Double Room
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="radio" value="Deluxe Room"
                                                                                name="room-type"
                                                                                id="room-type6">
                                                                            <label class="form-check-label"
                                                                                for="room-type6">
                                                                                Deluxe Room
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="search-btn">
                                                    <button type="submit" class="theme-btn"><span
                                                            class="far fa-search"></span>Search Now</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div> -->

                        </div>
                        <!-- tab content end -->
                    </div>
                </div>
            </div>
            <!-- search area end -->
        </div>
    </div>
</div>
<!-- hero area end -->

@include('partials.home.sections')

{{--@include('partials.cms.testimonials', ['testimonials' => $testimonials ?? collect()])--}}

@include('partials.cms.faqs', ['faqs' => $faqs ?? collect()])


@endsection

@section('modal')

@endsection

@section('scripts')

@endsection