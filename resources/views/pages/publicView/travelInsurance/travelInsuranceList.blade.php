@extends('layouts.app')

@section('body-class', 'home-3')

@section('styles')

@endsection

@section('content')

<!-- breadcrumb -->
<div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/01.jpg)">
    <div class="container">
        <h2 class="breadcrumb-title">2,350 Results Found</h2>
        <ul class="breadcrumb-menu">
            <li><a href="index.html">Home</a></li>
            <li class="active">Flight Search</li>
        </ul>
    </div>
</div>
<!-- breadcrumb end -->


<!-- search area -->
<div class="search-area flight-search">
    <div class="container">
        <div class="search-wrapper">
            <!-- flight search -->
            <div class="search-box">
                <div class="search-form">
                    <form action="#">
                        <!-- flight type -->
                        <div class="flight-type">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" checked value="one-way"
                                    name="flight-type" id="flight-type1">
                                <label class="form-check-label" for="flight-type1">
                                    One Way
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" value="round-way"
                                    name="flight-type" id="flight-type2">
                                <label class="form-check-label" for="flight-type2">
                                    Round Way
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" value="multi-city"
                                    name="flight-type" id="flight-type3">
                                <label class="form-check-label" for="flight-type3">
                                    Multi City
                                </label>
                            </div>
                        </div>
                        <!-- flight type end -->

                        <!-- flight search wrapper -->
                        <div class="flight-search-wrapper">
                            <div class="flight-search-content">
                                <!-- flight search content -->
                                <div class="flight-search-item">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label>From</label>
                                                <div class="form-group-icon">
                                                    <input type="text" name="from-destination"
                                                        class="form-control swap-from" value="New York">
                                                    <i class="fal fa-plane-departure"></i>
                                                </div>
                                                <p>JFK - John F. Kennedy International Airport
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <div class="search-form-swap"><i class="far fa-repeat"></i>
                                                </div>
                                                <label>To</label>
                                                <div class="form-group-icon">
                                                    <input type="text" name="to-destination"
                                                        class="form-control swap-to" value="Los Angeles">
                                                    <i class="fal fa-plane-arrival"></i>
                                                </div>
                                                <p>LAX - Los Angeles International Airport</p>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
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
                                        <div class="col-lg-3">
                                            <div class="form-group dropdown static-dropdown passenger-box">
                                                <div class="passenger-class" role="menu" data-bs-toggle="dropdown"
                                                    data-bs-display="static" aria-expanded="false">
                                                    <label>Passenger, Class</label>
                                                    <div class="form-group-icon">
                                                        <div class="passenger-total"><span
                                                                class="passenger-total-amount">2</span>
                                                            Passenger
                                                        </div>
                                                        <i class="fal fa-user-tie-hair"></i>
                                                    </div>
                                                    <p class="passenger-class-name">Business</p>
                                                </div>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <div class="dropdown-item">
                                                        <div class="passenger-item">
                                                            <div class="passenger-info">
                                                                <h6>Adults</h6>
                                                                <p>12+ Years</p>
                                                            </div>
                                                            <div class="passenger-qty">
                                                                <button type="button" class="minus-btn"><i
                                                                        class="far fa-minus"></i></button>
                                                                <input type="text" name="adult"
                                                                    class="qty-amount passenger-adult" value="2"
                                                                    readonly>
                                                                <button type="button" class="plus-btn"><i
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
                                                                <button type="button" class="minus-btn"><i
                                                                        class="far fa-minus"></i></button>
                                                                <input type="text" name="children"
                                                                    class="qty-amount passenger-children"
                                                                    value="0" readonly>
                                                                <button type="button" class="plus-btn"><i
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
                                                                <button type="button" class="minus-btn"><i
                                                                        class="far fa-minus"></i></button>
                                                                <input type="text" name="infant"
                                                                    class="qty-amount passenger-infant"
                                                                    value="0" readonly>
                                                                <button type="button" class="plus-btn"><i
                                                                        class="far fa-plus"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown-item">
                                                        <h6 class="mb-3 mt-2">Cabin Class</h6>
                                                        <div class="passenger-class-info">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    value="Economy" name="cabin-class"
                                                                    id="cabin-class1">
                                                                <label class="form-check-label"
                                                                    for="cabin-class1">
                                                                    Economy
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" checked
                                                                    type="radio" value="Business"
                                                                    name="cabin-class" id="cabin-class2">
                                                                <label class="form-check-label"
                                                                    for="cabin-class2">
                                                                    Business
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    value="First Class" name="cabin-class"
                                                                    id="cabin-class3">
                                                                <label class="form-check-label"
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
                                <div class="flight-search-item flight-multicity-item have-to-clone">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label>From</label>
                                                <div class="form-group-icon">
                                                    <input type="text" name="from-destination"
                                                        class="form-control swap-from" value="New York">
                                                    <i class="fal fa-plane-departure"></i>
                                                </div>
                                                <p>JFK - John F. Kennedy International Airport
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <div class="search-form-swap"><i class="far fa-repeat"></i>
                                                </div>
                                                <label>To</label>
                                                <div class="form-group-icon">
                                                    <input type="text" name="to-destination"
                                                        class="form-control swap-to" value="Los Angeles">
                                                    <i class="fal fa-plane-arrival"></i>
                                                </div>
                                                <p>LAX - Los Angeles International Airport</p>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
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
                                        class="far fa-search"></span>Update Search</button>
                            </div>
                        </div>
                        <!-- flight search wrapper end -->
                    </form>
                </div>
            </div>
            <!-- flight search end -->
        </div>
    </div>
</div>
<!-- search area end -->


<!-- flight booking -->
<div class="flight-booking flight-list pt-80 pb-120">
    <div class="container">
        <div class="row">
            <!-- booking sidebar -->
            <div class="col-lg-4 col-xl-3 mb-4">
                @include('partials.catalog.filters-sidebar', ['filterGroups' => $filterGroups ?? []])
                <div class="booking-sidebar">
                    <div class="booking-item">
                        <h4 class="booking-title">Flight Class</h4>
                        <div class="flight-class">
                            <div class="form-check">
                                <input class="form-check-input" name="flight-class" type="checkbox" value="1"
                                    id="flight-class1">
                                <label class="form-check-label" for="flight-class1">
                                    Business <span>(20)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="flight-class" type="checkbox" value="2"
                                    id="flight-class2">
                                <label class="form-check-label" for="flight-class2">
                                    First Class <span>(15)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="flight-class" type="checkbox" value="3"
                                    id="flight-class3">
                                <label class="form-check-label" for="flight-class3">
                                    Economy <span>(18)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="booking-item">
                        <h4 class="booking-title">Flight Price</h4>
                        <div class="flight-price">
                            <div class="price-range-slider">
                                <div class="price-range-info">
                                    <label for="priceRange1">Price:</label>
                                    <input type="text" class="priceRange" id="priceRange1" readonly>
                                </div>
                                <div id="price-range1" class="price-range slider"></div>
                            </div>
                        </div>
                    </div>
                    <div class="booking-item">
                        <h4 class="booking-title">Flight Time</h4>
                        <div class="flight-time">
                            <div class="form-check">
                                <input class="form-check-input" name="flight-time" type="checkbox" value="1"
                                    id="flight-time1">
                                <label class="form-check-label" for="flight-time1">
                                    <i class="far fa-sunrise"></i> 00:00 - 05:59 <span>(20)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="flight-time" type="checkbox" value="2"
                                    id="flight-time2">
                                <label class="form-check-label" for="flight-time2">
                                    <i class="far fa-sun-bright"></i> 06:00 - 11:59 <span>(15)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="flight-time" type="checkbox" value="3"
                                    id="flight-time3">
                                <label class="form-check-label" for="flight-time3">
                                    <i class="far fa-sunset"></i> 12:00 - 17:59 <span>(18)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="flight-time" type="checkbox" value="3"
                                    id="flight-time4">
                                <label class="form-check-label" for="flight-time4">
                                    <i class="far fa-moon-stars"></i> 18:00 - 23:59 <span>(21)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="booking-item">
                        <h4 class="booking-title">Flight Stops</h4>
                        <div class="flight-stop">
                            <div class="form-check">
                                <input class="form-check-input" name="flight-stop" type="checkbox" value="1"
                                    id="flight-stop1">
                                <label class="form-check-label" for="flight-stop1">
                                    Non Stop <span>(20)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="flight-stop" type="checkbox" value="2"
                                    id="flight-stop2">
                                <label class="form-check-label" for="flight-stop2">
                                    1 Stop <span>(15)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="flight-stop" type="checkbox" value="3"
                                    id="flight-stop3">
                                <label class="form-check-label" for="flight-stop3">
                                    2 Stop <span>(18)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="flight-stop" type="checkbox" value="4"
                                    id="flight-stop4">
                                <label class="form-check-label" for="flight-stop4">
                                    3 Stop <span>(25)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="booking-item">
                        <h4 class="booking-title">Airlines</h4>
                        <div class="flight-airline">
                            <div class="form-check">
                                <input class="form-check-input" name="flight-airline" type="checkbox" value="1"
                                    id="flight-airline1">
                                <label class="form-check-label" for="flight-airline1">
                                    American Airlines <span>(20)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="flight-airline" type="checkbox" value="2"
                                    id="flight-airline2">
                                <label class="form-check-label" for="flight-airline2">
                                    Delta Airlines <span>(15)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="flight-airline" type="checkbox" value="3"
                                    id="flight-airline3">
                                <label class="form-check-label" for="flight-airline3">
                                    Qatar Airways <span>(18)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="flight-airline" type="checkbox" value="4"
                                    id="flight-airline4">
                                <label class="form-check-label" for="flight-airline4">
                                    Fly Amirates <span>(25)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="flight-airline" type="checkbox" value="5"
                                    id="flight-airline5">
                                <label class="form-check-label" for="flight-airline5">
                                    Singapore Airlines <span>(35)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="booking-item">
                        <h4 class="booking-title">Weights</h4>
                        <div class="flight-weight">
                            <div class="form-check">
                                <input class="form-check-input" name="flight-weight" type="checkbox" value="1"
                                    id="flight-weight1">
                                <label class="form-check-label" for="flight-weight1">
                                    25 Kg <span>(20)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="booking-item">
                        <h4 class="booking-title">Refundable</h4>
                        <div class="flight-refundable">
                            <div class="form-check">
                                <input class="form-check-input" name="flight-refundable" type="checkbox"
                                    value="1" id="flight-refundable1">
                                <label class="form-check-label" for="flight-refundable1">
                                    Non Refundable <span>(20)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="flight-refundable" type="checkbox"
                                    value="2" id="flight-refundable2">
                                <label class="form-check-label" for="flight-refundable2">
                                    Refundable <span>(15)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="flight-refundable" type="checkbox"
                                    value="3" id="flight-refundable3">
                                <label class="form-check-label" for="flight-refundable3">
                                    As Per Rules <span>(18)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- booking list -->
            <div class="col-lg-8 col-xl-9">
                <div class="col-md-12">
                    <div class="booking-sort">
                        <h5>2,350 Results Found</h5>
                        <div class="col-md-3 booking-sort-box">
                            <select class="select">
                                <option value="1">Sort By Default</option>
                                <option value="2">Sort By Popular</option>
                                <option value="3">Sort By Low Price</option>
                                <option value="4">Sort By High Price</option>
                            </select>
                        </div>
                    </div>
                </div>
                @include('components.catalog-list-results', ['items' => $items, 'routePrefix' => $routePrefix ?? 'travelinsurance', 'label' => 'Plans'])
</div>
            </div>
        </div>
    </div>
</div>
<!-- flight booking end -->

@endsection

@section('modal')

@endsection

@section('scripts')

@endsection