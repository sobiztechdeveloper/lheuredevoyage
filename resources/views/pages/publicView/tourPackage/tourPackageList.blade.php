@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<!-- breadcrumb -->
<div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/01.jpg)">
    <div class="container">
        <h2 class="breadcrumb-title">2,350 Results Found</h2>
        <ul class="breadcrumb-menu">
            <li><a href="index.html">Home</a></li>
            <li class="active">Tour Search</li>
        </ul>
    </div>
</div>
<!-- breadcrumb end -->

<!-- search area -->
<div class="search-area search-common">
    <div class="container">
        <div class="search-wrapper">
            <!-- tour search -->
            <div class="search-box tour-search">
                <div class="search-form">
                    <form action="#">
                        <div class="tour-search-wrapper">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Destination</label>
                                        <div class="form-group-icon">
                                            <input type="text" name="destination" class="form-control"
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
                                                            id="room-type10">
                                                        <label class="form-check-label"
                                                            for="room-type10">
                                                            Single Room
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" checked
                                                            type="radio" value="Double Room"
                                                            name="room-type" id="room-type11">
                                                        <label class="form-check-label"
                                                            for="room-type11">
                                                            Double Room
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            value="Deluxe Room" name="room-type"
                                                            id="room-type12">
                                                        <label class="form-check-label"
                                                            for="room-type12">
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
            <!-- tour search end -->
        </div>
    </div>
</div>
<!-- search area end -->

<!-- tour grid -->
<div class="tour-grid py-120">
    <div class="container">
        <div class="row">
            <!-- tour booking sidebar -->
            <div class="col-lg-4 col-xl-3 mb-4">
                <div class="booking-sidebar">
                    <div class="booking-item">
                        <h4 class="booking-title">Tour Category</h4>
                        <div class="tour-category">
                            <div class="form-check">
                                <input class="form-check-input" name="tour-category" type="checkbox" value="1"
                                    id="tour-category1">
                                <label class="form-check-label" for="tour-category1">
                                    Historical <span>(20)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="tour-category" type="checkbox" value="2"
                                    id="tour-category2">
                                <label class="form-check-label" for="tour-category2">
                                    Weekend Trip <span>(15)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="tour-category" type="checkbox" value="3"
                                    id="tour-category3">
                                <label class="form-check-label" for="tour-category3">
                                    Special Tour <span>(18)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="tour-category" type="checkbox" value="4"
                                    id="tour-category4">
                                <label class="form-check-label" for="tour-category4">
                                    Holiday Tour <span>(35)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="booking-item">
                        <h4 class="booking-title">Tour Price</h4>
                        <div class="tour-price">
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
                        <h4 class="booking-title">Tour Duration</h4>
                        <div class="tour-duration">
                            <div class="form-check">
                                <input class="form-check-input" name="tour-duration" type="checkbox" value="1"
                                    id="tour-duration1">
                                <label class="form-check-label" for="tour-duration1">
                                    Up to 1 hour <span>(20)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="tour-duration" type="checkbox" value="2"
                                    id="tour-duration2">
                                <label class="form-check-label" for="tour-duration2">
                                    1 to 4 hours <span>(15)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="tour-duration" type="checkbox" value="3"
                                    id="tour-duration3">
                                <label class="form-check-label" for="tour-duration3">
                                    4 hours to 1 day <span>(18)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="tour-duration" type="checkbox" value="4"
                                    id="tour-duration4">
                                <label class="form-check-label" for="tour-duration4">
                                    1 to 10 days <span>(25)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="booking-item">
                        <h4 class="booking-title">Languages</h4>
                        <div class="tour-language">
                            <div class="form-check">
                                <input class="form-check-input" name="tour-language" type="checkbox" value="1"
                                    id="tour-language1">
                                <label class="form-check-label" for="tour-language1">
                                    English <span>(20)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="tour-language" type="checkbox" value="2"
                                    id="tour-language2">
                                <label class="form-check-label" for="tour-language2">
                                    Spanish <span>(15)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="tour-language" type="checkbox" value="3"
                                    id="tour-language3">
                                <label class="form-check-label" for="tour-language3">
                                    French <span>(18)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="tour-language" type="checkbox" value="4"
                                    id="tour-language4">
                                <label class="form-check-label" for="tour-language4">
                                    Turkish <span>(25)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="booking-item">
                        <h4 class="booking-title">Popular Attractions</h4>
                        <div class="tour-attraction">
                            <div class="form-check">
                                <input class="form-check-input" name="tour-attraction" type="checkbox"
                                    value="1" id="tour-attraction1">
                                <label class="form-check-label" for="tour-attraction1">
                                    Buckingham Palace <span>(20)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="tour-attraction" type="checkbox"
                                    value="2" id="tour-attraction2">
                                <label class="form-check-label" for="tour-attraction2">
                                    Tourope UK <span>(15)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="tour-attraction" type="checkbox"
                                    value="3" id="tour-attraction3">
                                <label class="form-check-label" for="tour-attraction3">
                                    European Tours Limited <span>(18)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="tour-attraction" type="checkbox"
                                    value="4" id="tour-attraction4">
                                <label class="form-check-label" for="tour-attraction4">
                                    Westminster Abbey <span>(18)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- tour booking grid -->
            <div class="col-lg-8 col-xl-9">
                <div class="col-md-12">
                    <div class="booking-sort">
                        <h5>2,350 Results Found</h5>
                        <div class="booking-sort-list-grid">
                            <a class="booking-sort-grid active" href="tour-grid.html"><i
                                    class="far fa-grid-2"></i></a>
                            <a class="booking-sort-list" href="tour-list.html"><i
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
                        <div class="tour-item">
                            <div class="tour-img">
                                <span class="badge badge-discount">25% Save</span>
                                <img src="assets/img/tour/01.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="tour-content">
                                <div class="tour-top">
                                    <a href="#" class="tour-category"><i class="far fa-tag"></i> Historical</a>
                                    <span class="tour-place"><i class="far fa-earth-americas"></i> 10 Places</span>
                                </div>
                                <h4 class="tour-title"><a href="#">Canada Historical Tour</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="tour-duration"><i class="far fa-clock"></i> 5 Days 4 Nights Trip</div>
                                <div class="tour-bottom">
                                    <div class="tour-price">
                                        From <span>$500</span>
                                    </div>
                                    <div class="tour-text-btn">
                                        <a href="#">See More <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="tour-item">
                            <div class="tour-img">
                                <img src="assets/img/tour/02.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="tour-content">
                                <div class="tour-top">
                                    <a href="#" class="tour-category"><i class="far fa-tag"></i> Weekend</a>
                                    <span class="tour-place"><i class="far fa-earth-americas"></i> 10 Places</span>
                                </div>
                                <h4 class="tour-title"><a href="#">Canada Weekend Tour</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="tour-duration"><i class="far fa-clock"></i> 5 Days 4 Nights Trip</div>
                                <div class="tour-bottom">
                                    <div class="tour-price">
                                        From <span>$500</span>
                                    </div>
                                    <div class="tour-text-btn">
                                        <a href="#">See More <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="tour-item">
                            <div class="tour-img">
                                <img src="assets/img/tour/03.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="tour-content">
                                <div class="tour-top">
                                    <a href="#" class="tour-category"><i class="far fa-tag"></i> Special</a>
                                    <span class="tour-place"><i class="far fa-earth-americas"></i> 10 Places</span>
                                </div>
                                <h4 class="tour-title"><a href="#">Canada Special Tour</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="tour-duration"><i class="far fa-clock"></i> 5 Days 4 Nights Trip</div>
                                <div class="tour-bottom">
                                    <div class="tour-price">
                                        From <span>$500</span>
                                    </div>
                                    <div class="tour-text-btn">
                                        <a href="#">See More <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="tour-item">
                            <div class="tour-img">
                                <img src="assets/img/tour/04.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="tour-content">
                                <div class="tour-top">
                                    <a href="#" class="tour-category"><i class="far fa-tag"></i> Holiday</a>
                                    <span class="tour-place"><i class="far fa-earth-americas"></i> 10 Places</span>
                                </div>
                                <h4 class="tour-title"><a href="#">Canada Holiday Tour</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="tour-duration"><i class="far fa-clock"></i> 5 Days 4 Nights Trip</div>
                                <div class="tour-bottom">
                                    <div class="tour-price">
                                        From <span>$500</span>
                                    </div>
                                    <div class="tour-text-btn">
                                        <a href="#">See More <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="tour-item">
                            <div class="tour-img">
                                <span class="badge">Featured</span>
                                <img src="assets/img/tour/05.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="tour-content">
                                <div class="tour-top">
                                    <a href="#" class="tour-category"><i class="far fa-tag"></i> Weekend</a>
                                    <span class="tour-place"><i class="far fa-earth-americas"></i> 10 Places</span>
                                </div>
                                <h4 class="tour-title"><a href="#">Canada Weekend Tour</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="tour-duration"><i class="far fa-clock"></i> 5 Days 4 Nights Trip</div>
                                <div class="tour-bottom">
                                    <div class="tour-price">
                                        From <span>$500</span>
                                    </div>
                                    <div class="tour-text-btn">
                                        <a href="#">See More <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="tour-item">
                            <div class="tour-img">
                                <img src="assets/img/tour/06.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="tour-content">
                                <div class="tour-top">
                                    <a href="#" class="tour-category"><i class="far fa-tag"></i> Holiday</a>
                                    <span class="tour-place"><i class="far fa-earth-americas"></i> 10 Places</span>
                                </div>
                                <h4 class="tour-title"><a href="#">Canada Holiday Tour</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="tour-duration"><i class="far fa-clock"></i> 5 Days 4 Nights Trip</div>
                                <div class="tour-bottom">
                                    <div class="tour-price">
                                        From <span>$500</span>
                                    </div>
                                    <div class="tour-text-btn">
                                        <a href="#">See More <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="tour-item">
                            <div class="tour-img">
                                <img src="assets/img/tour/07.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="tour-content">
                                <div class="tour-top">
                                    <a href="#" class="tour-category"><i class="far fa-tag"></i> Special</a>
                                    <span class="tour-place"><i class="far fa-earth-americas"></i> 10 Places</span>
                                </div>
                                <h4 class="tour-title"><a href="#">Canada Special Tour</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="tour-duration"><i class="far fa-clock"></i> 5 Days 4 Nights Trip</div>
                                <div class="tour-bottom">
                                    <div class="tour-price">
                                        From <span>$500</span>
                                    </div>
                                    <div class="tour-text-btn">
                                        <a href="#">See More <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="tour-item">
                            <div class="tour-img">
                                <span class="badge badge-discount">25% Save</span>
                                <img src="assets/img/tour/08.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="tour-content">
                                <div class="tour-top">
                                    <a href="#" class="tour-category"><i class="far fa-tag"></i> Historical</a>
                                    <span class="tour-place"><i class="far fa-earth-americas"></i> 10 Places</span>
                                </div>
                                <h4 class="tour-title"><a href="#">Canada Historical Tour</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="tour-duration"><i class="far fa-clock"></i> 5 Days 4 Nights Trip</div>
                                <div class="tour-bottom">
                                    <div class="tour-price">
                                        From <span>$500</span>
                                    </div>
                                    <div class="tour-text-btn">
                                        <a href="#">See More <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="tour-item">
                            <div class="tour-img">
                                <span class="badge badge-discount">25% Save</span>
                                <img src="assets/img/tour/03.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="tour-content">
                                <div class="tour-top">
                                    <a href="#" class="tour-category"><i class="far fa-tag"></i> Historical</a>
                                    <span class="tour-place"><i class="far fa-earth-americas"></i> 10 Places</span>
                                </div>
                                <h4 class="tour-title"><a href="#">Canada Historical Tour</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="hotel-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="hotel-rate-type">Excellent</span>
                                    <span class="hotel-rate-review">(2.5k Reviews)</span>
                                </div>
                                <div class="tour-duration"><i class="far fa-clock"></i> 5 Days 4 Nights Trip</div>
                                <div class="tour-bottom">
                                    <div class="tour-price">
                                        From <span>$500</span>
                                    </div>
                                    <div class="tour-text-btn">
                                        <a href="#">See More <i class="fas fa-arrow-right"></i></a>
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
                            <p>Showing 1 - 6 of 24 Tours</p>
                        </div>
                    </div>
                    <!-- pagination end -->

                </div>
            </div>
        </div>
    </div>
</div>
<!-- tour grid end -->

@endsection

@section('modal')

@endsection

@section('scripts')

@endsection