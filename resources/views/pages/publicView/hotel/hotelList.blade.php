@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<!-- breadcrumb -->
<div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/05.jpg)">
    <div class="container">
        <h2 class="breadcrumb-title">2,350 Results Found</h2>
        <ul class="breadcrumb-menu">
            <li><a href="index.html">Home</a></li>
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
                <div class="booking-sidebar">
                    <div class="booking-item">
                        <h4 class="booking-title">Facilities</h4>
                        <div class="facility">
                            <div class="form-check">
                                <input class="form-check-input" name="facility" type="checkbox" value="1"
                                    id="facility1">
                                <label class="form-check-label" for="facility1">
                                    Parking <span>(20)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="facility" type="checkbox" value="2"
                                    id="facility2">
                                <label class="form-check-label" for="facility2">
                                    Restaurant <span>(15)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="facility" type="checkbox" value="3"
                                    id="facility3">
                                <label class="form-check-label" for="facility3">
                                    Pet Friendly <span>(18)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="facility" type="checkbox" value="4"
                                    id="facility4">
                                <label class="form-check-label" for="facility4">
                                    Air Conditioning <span>(35)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="facility" type="checkbox" value="5"
                                    id="facility5">
                                <label class="form-check-label" for="facility5">
                                    Airport Transport <span>(12)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="facility" type="checkbox" value="6"
                                    id="facility6">
                                <label class="form-check-label" for="facility6">
                                    Fitness Center <span>(18)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="facility" type="checkbox" value="7"
                                    id="facility7">
                                <label class="form-check-label" for="facility7">
                                    Internet – Wifi <span>(29)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="facility" type="checkbox" value="8"
                                    id="facility8">
                                <label class="form-check-label" for="facility8">
                                    Flat Tv <span>(40)</span>
                                </label>
                            </div>
                        </div>
                    </div>
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
                        <h5>2,350 Results Found</h5>
                        <div class="booking-sort-list-grid">
                            <a class="booking-sort-grid active" href="hotel-grid.html"><i
                                    class="far fa-grid-2"></i></a>
                            <a class="booking-sort-list" href="hotel-list.html"><i
                                    class="far fa-list-ul"></i></a>
                        </div>
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
                <div class="row">
                    <div class="col-md-6 col-lg-4">
                        <div class="hotel-item">
                            <div class="hotel-img">
                                <span class="badge">Featured</span>
                                <img src="assets/img/hotel/01.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="hotel-content">
                                <h4 class="hotel-title"><a href="#">Western Grant Park Hotel</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="hotel-bottom">
                                    <div class="hotel-price">
                                        <span class="hotel-price-amount">$300 <span class="hotel-price-type">/Per
                                                Night</span></span>
                                    </div>
                                    <div class="hotel-text-btn">
                                        <a href="#">See Details <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="hotel-item">
                            <div class="hotel-img">
                                <img src="assets/img/hotel/02.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="hotel-content">
                                <h4 class="hotel-title"><a href="#">Western Grant Park Hotel</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="hotel-bottom">
                                    <div class="hotel-price">
                                        <span class="hotel-price-amount">$300 <span class="hotel-price-type">/Per
                                                Night</span></span>
                                    </div>
                                    <div class="hotel-text-btn">
                                        <a href="#">See Details <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="hotel-item">
                            <div class="hotel-img">
                                <span class="badge">Popular</span>
                                <img src="assets/img/hotel/03.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="hotel-content">
                                <h4 class="hotel-title"><a href="#">Western Grant Park Hotel</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="hotel-bottom">
                                    <div class="hotel-price">
                                        <span class="hotel-price-amount">$300 <span class="hotel-price-type">/Per
                                                Night</span></span>
                                    </div>
                                    <div class="hotel-text-btn">
                                        <a href="#">See Details <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="hotel-item">
                            <div class="hotel-img">
                                <span class="badge badge-discount">25% Save</span>
                                <img src="assets/img/hotel/04.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="hotel-content">
                                <h4 class="hotel-title"><a href="#">Western Grant Park Hotel</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="hotel-bottom">
                                    <div class="hotel-price">
                                        <span class="hotel-price-amount">$300 <span class="hotel-price-type">/Per
                                                Night</span></span>
                                    </div>
                                    <div class="hotel-text-btn">
                                        <a href="#">See Details <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="hotel-item">
                            <div class="hotel-img">
                                <img src="assets/img/hotel/05.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="hotel-content">
                                <h4 class="hotel-title"><a href="#">Western Grant Park Hotel</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="hotel-bottom">
                                    <div class="hotel-price">
                                        <span class="hotel-price-amount">$300 <span class="hotel-price-type">/Per
                                                Night</span></span>
                                    </div>
                                    <div class="hotel-text-btn">
                                        <a href="#">See Details <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="hotel-item">
                            <div class="hotel-img">
                                <img src="assets/img/hotel/06.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="hotel-content">
                                <h4 class="hotel-title"><a href="#">Western Grant Park Hotel</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="hotel-bottom">
                                    <div class="hotel-price">
                                        <span class="hotel-price-amount">$300 <span class="hotel-price-type">/Per
                                                Night</span></span>
                                    </div>
                                    <div class="hotel-text-btn">
                                        <a href="#">See Details <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="hotel-item">
                            <div class="hotel-img">
                                <img src="assets/img/hotel/07.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="hotel-content">
                                <h4 class="hotel-title"><a href="#">Western Grant Park Hotel</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="hotel-bottom">
                                    <div class="hotel-price">
                                        <span class="hotel-price-amount">$300 <span class="hotel-price-type">/Per
                                                Night</span></span>
                                    </div>
                                    <div class="hotel-text-btn">
                                        <a href="#">See Details <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="hotel-item">
                            <div class="hotel-img">
                                <img src="assets/img/hotel/08.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="hotel-content">
                                <h4 class="hotel-title"><a href="#">Western Grant Park Hotel</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="hotel-bottom">
                                    <div class="hotel-price">
                                        <span class="hotel-price-amount">$300 <span class="hotel-price-type">/Per
                                                Night</span></span>
                                    </div>
                                    <div class="hotel-text-btn">
                                        <a href="#">See Details <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="hotel-item">
                            <div class="hotel-img">
                                <img src="assets/img/hotel/02.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="hotel-content">
                                <h4 class="hotel-title"><a href="#">Western Grant Park Hotel</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="hotel-bottom">
                                    <div class="hotel-price">
                                        <span class="hotel-price-amount">$300 <span class="hotel-price-type">/Per
                                                Night</span></span>
                                    </div>
                                    <div class="hotel-text-btn">
                                        <a href="#">See Details <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- pagination -->
                    <div class="pagination-area">
                        <div aria-label="Page navigation example">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true"><i class="far fa-angle-double-left"></i></span>
                                    </a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true"><i
                                                class="far fa-angle-double-right"></i></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="pagination-showing">
                            <p>Showing 1 - 6 of 24 Hotels</p>
                        </div>
                    </div>
                    <!-- pagination end -->

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