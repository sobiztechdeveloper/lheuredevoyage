@extends('layouts.app')

@section('body-class', 'home-3')

@section('styles')

@endsection

@section('content')

<!-- breadcrumb -->
<div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/05.jpg)">
    <div class="container">
        <h2 class="breadcrumb-title">Hotels</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="active">Hotel Search</li>
        </ul>
    </div>
</div>
<!-- breadcrumb end -->

<!-- search area -->
<div class="search-area search-common">
    <div class="container">
        <div class="search-wrapper">
            <!-- hotel search -->
            <div class="search-box hotel-search">
                <div class="search-form">
                    <form action="#">
                        <div class="hotel-search-wrapper">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Destination</label>
                                        <div class="form-group-icon">
                                            <input type="text" name="destination" class="form-control"
                                                value="Reserva Ecologica">
                                            <i class="fal fa-earth-americas"></i>
                                        </div>
                                        <p>Comuna 1, Argentina</p>
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
                                        <div class="passenger-class" role="menu" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <label>Rooms, Guests</label>
                                            <div class="form-group-icon">
                                                <div class="passenger-total">
                                                    <span class="passenger-total-room">2</span> Rooms,
                                                    <span class="passenger-total-amount">2</span> Guests
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
                                                <div class="passenger-item">
                                                    <div class="passenger-info">
                                                        <h6>Rooms</h6>
                                                    </div>
                                                    <div class="passenger-qty">
                                                        <button type="button" class="minus-btn"><i
                                                                class="far fa-minus"></i></button>
                                                        <input type="text" name="room"
                                                            class="qty-amount passenger-room" value="2"
                                                            readonly>
                                                        <button type="button" class="plus-btn"><i
                                                                class="far fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dropdown-item">
                                                <h6 class="mb-3 mt-2">Room Type</h6>
                                                <div class="passenger-class-info">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            value="Single Room" name="room-type"
                                                            id="room-type1">
                                                        <label class="form-check-label"
                                                            for="room-type1">
                                                            Single Room
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" checked
                                                            type="radio" value="Double Room"
                                                            name="room-type" id="room-type2">
                                                        <label class="form-check-label"
                                                            for="room-type2">
                                                            Double Room
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            value="Deluxe Room" name="room-type"
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
            <!-- hotel search end -->
        </div>
    </div>
</div>
<!-- search area end -->

<!-- hotel grid -->
<div class="hotel-grid py-120">
    <div class="container">
        <div class="row">
            <!-- hotel booking sidebar -->
            <div class="col-lg-4 col-xl-3 mb-4">
                @include('partials.catalog.filters-sidebar', ['filterGroups' => $filterGroups ?? []])
                <div class="booking-sidebar">
                    <div class="booking-item">
                        <h4 class="booking-title">Hotel Price</h4>
                        <div class="hotel-price">
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
                        <h4 class="booking-title">Hotel Star</h4>
                        <div class="hotel-star">
                            <div class="form-check">
                                <input class="form-check-input" name="hotel-star" type="checkbox" value="1"
                                    id="hotel-star1">
                                <label class="form-check-label" for="hotel-star1">
                                    5 Star <span>(20)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="hotel-star" type="checkbox" value="2"
                                    id="hotel-star2">
                                <label class="form-check-label" for="hotel-star2">
                                    4 Star <span>(15)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="hotel-star" type="checkbox" value="3"
                                    id="hotel-star3">
                                <label class="form-check-label" for="hotel-star3">
                                    3 Star <span>(18)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="hotel-star" type="checkbox" value="4"
                                    id="hotel-star4">
                                <label class="form-check-label" for="hotel-star4">
                                    2 Star <span>(25)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="hotel-star" type="checkbox" value="5"
                                    id="hotel-star5">
                                <label class="form-check-label" for="hotel-star5">
                                    1 Star <span>(27)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="booking-item">
                        <h4 class="booking-title">Languages</h4>
                        <div class="language">
                            <div class="form-check">
                                <input class="form-check-input" name="language" type="checkbox" value="1"
                                    id="language1">
                                <label class="form-check-label" for="language1">
                                    English <span>(20)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="language" type="checkbox" value="2"
                                    id="language2">
                                <label class="form-check-label" for="language2">
                                    Spanish <span>(15)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="language" type="checkbox" value="3"
                                    id="language3">
                                <label class="form-check-label" for="language3">
                                    French <span>(18)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="language" type="checkbox" value="4"
                                    id="language4">
                                <label class="form-check-label" for="language4">
                                    Turkish <span>(25)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="booking-item">
                        <h4 class="booking-title">Review Score</h4>
                        <div class="review-score">
                            <div class="form-check">
                                <input class="form-check-input" name="review-score" type="checkbox"
                                    value="1" id="review-score1">
                                <label class="form-check-label" for="review-score1">
                                    Excellent <span>(20)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="review-score" type="checkbox"
                                    value="2" id="review-score2">
                                <label class="form-check-label" for="review-score2">
                                    Very GoodK <span>(15)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="review-score" type="checkbox"
                                    value="3" id="review-score3">
                                <label class="form-check-label" for="review-score3">
                                    Average <span>(18)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="review-score" type="checkbox"
                                    value="4" id="review-score4">
                                <label class="form-check-label" for="review-score4">
                                    Poor <span>(18)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- hotel booking grid -->
            <div class="col-lg-8 col-xl-9">
                <div class="col-md-12">
                    <div class="booking-sort">
                        <h5>{{ $items->total() ?? 0 }} Hotels Found</h5>
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
                @include('components.catalog-list-results', ['items' => $items, 'routePrefix' => $routePrefix ?? 'hotel', 'label' => 'Hotels'])

                </div>
            </div>
        </div>
    </div>
</div>
<!-- hotel grid end -->

@endsection

@section('modal')

@endsection

@section('scripts')

@endsection