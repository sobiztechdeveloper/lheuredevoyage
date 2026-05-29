@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<!-- breadcrumb -->
<div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/07.jpg)">
    <div class="container">
        <h2 class="breadcrumb-title">2,350 Results Found</h2>
        <ul class="breadcrumb-menu">
            <li><a href="index.html">Home</a></li>
            <li class="active">Car Search</li>
        </ul>
    </div>
</div>
<!-- breadcrumb end -->


<!-- search area -->
<div class="search-area search-common">
    <div class="container">
        <div class="search-wrapper">
            <!-- car search -->
            <div class="search-box car-search">
                <div class="search-form">
                    <form action="#">
                        <div class="car-search-wrapper">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Picking Up</label>
                                        <div class="form-group-icon">
                                            <input type="text" name="picking-up" class="form-control"
                                                value="New York, United States">
                                            <i class="fal fa-location-dot"></i>
                                        </div>
                                        <p>City, Airport Or Address</p>
                                    </div>
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
                                                class="form-control time-picker" value="11:00 PM">
                                            <i class="fal fa-clock"></i>
                                        </div>
                                        <p>Car Arrival Time</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mt-lg-4">
                                        <label>Drop Off</label>
                                        <div class="form-group-icon">
                                            <input type="text" name="picking-up" class="form-control"
                                                value="New York, United States">
                                            <i class="fal fa-location-dot"></i>
                                        </div>
                                        <p>City, Airport Or Address</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mt-lg-4">
                                        <div class="search-form-date">
                                            <div class="search-form-journey">
                                                <label>Drop Off date</label>
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
                                    <div class="form-group mt-lg-4">
                                        <label>Drop Off Time</label>
                                        <div class="form-group-icon">
                                            <input type="text" name="pick-up-time"
                                                class="form-control time-picker" value="11:00 PM">
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
            <!-- car search end -->
        </div>
    </div>
</div>
<!-- search area end -->


<!-- car grid -->
<div class="car-grid py-120">
    <div class="container">
        <div class="row">
            <!-- car booking sidebar -->
            <div class="col-lg-4 col-xl-3 mb-4">
                <div class="booking-sidebar">
                    <div class="booking-item">
                        <h4 class="booking-title">Car Category</h4>
                        <div class="car-category">
                            <div class="form-check">
                                <input class="form-check-input" name="car-category" type="checkbox" value="1"
                                    id="car-category1">
                                <label class="form-check-label" for="car-category1">
                                    Convertable <span>(20)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="car-category" type="checkbox" value="2"
                                    id="car-category2">
                                <label class="form-check-label" for="car-category2">
                                    Coupe <span>(15)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="car-category" type="checkbox" value="3"
                                    id="car-category3">
                                <label class="form-check-label" for="car-category3">
                                    Crossover <span>(18)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="car-category" type="checkbox" value="4"
                                    id="car-category4">
                                <label class="form-check-label" for="car-category4">
                                    Muv <span>(35)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="car-category" type="checkbox" value="5"
                                    id="car-category5">
                                <label class="form-check-label" for="car-category5">
                                    SUV <span>(12)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="booking-item">
                        <h4 class="booking-title">Car Price</h4>
                        <div class="car-price">
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
                        <h4 class="booking-title">Car Condition</h4>
                        <div class="car-condition">
                            <div class="form-check">
                                <input class="form-check-input" name="car-condition" type="checkbox" value="1"
                                    id="car-condition1">
                                <label class="form-check-label" for="car-condition1">
                                    All <span>(20)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="car-condition" type="checkbox" value="2"
                                    id="car-condition2">
                                <label class="form-check-label" for="car-condition2">
                                    New <span>(15)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="car-condition" type="checkbox" value="3"
                                    id="car-condition3">
                                <label class="form-check-label" for="car-condition3">
                                    Used <span>(18)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="booking-item">
                        <h4 class="booking-title">Listed By</h4>
                        <div class="car-listed">
                            <div class="form-check">
                                <input class="form-check-input" name="car-listed" type="checkbox" value="1"
                                    id="car-listed1">
                                <label class="form-check-label" for="car-listed1">
                                    Dealer <span>(20)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="car-listed" type="checkbox" value="2"
                                    id="car-listed2">
                                <label class="form-check-label" for="car-listed2">
                                    Individual <span>(15)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="car-listed" type="checkbox" value="3"
                                    id="car-listed3">
                                <label class="form-check-label" for="car-listed3">
                                    Reseller <span>(18)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="booking-item">
                        <h4 class="booking-title">Fuel Type</h4>
                        <div class="fuel-type">
                            <div class="form-check">
                                <input class="form-check-input" name="fuel-type" type="checkbox"
                                    value="1" id="fuel-type1">
                                <label class="form-check-label" for="fuel-type1">
                                    Electric <span>(20)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="fuel-type" type="checkbox"
                                    value="2" id="fuel-type2">
                                <label class="form-check-label" for="fuel-type2">
                                    Diesel <span>(15)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="fuel-type" type="checkbox"
                                    value="3" id="fuel-type3">
                                <label class="form-check-label" for="fuel-type3">
                                    Hybrid <span>(18)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="fuel-type" type="checkbox"
                                    value="4" id="fuel-type4">
                                <label class="form-check-label" for="fuel-type4">
                                    Petrol <span>(18)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="booking-item">
                        <h4 class="booking-title">Transmission</h4>
                        <div class="transmission">
                            <div class="form-check">
                                <input class="form-check-input" name="transmission" type="checkbox"
                                    value="1" id="transmission1">
                                <label class="form-check-label" for="transmission1">
                                    Automatic <span>(20)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="transmission" type="checkbox"
                                    value="2" id="transmission2">
                                <label class="form-check-label" for="transmission2">
                                    Manual <span>(15)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- car booking grid -->
            <div class="col-lg-8 col-xl-9">
                <div class="col-md-12">
                    <div class="booking-sort">
                        <h5>2,350 Results Found</h5>
                        <div class="booking-sort-list-grid">
                            <a class="booking-sort-grid active" href="car-grid.html"><i
                                    class="far fa-grid-2"></i></a>
                            <a class="booking-sort-list" href="car-list.html"><i
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
                        <div class="car-item">
                            <div class="car-img">
                                <span class="badge">Featured</span>
                                <img src="assets/img/car/01.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="car-content">
                                <h4 class="car-title"><a href="#">Toyota Corolla Car</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="car-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="car-rate-type">Excellent</span>
                                    <span class="car-rate-review">(2.5k Reviews)</span>
                                </div>
                                <ul class="car-info-list">
                                    <li><i class="far fa-car"></i>Model: 2025</li>
                                    <li><i class="far fa-user-tie"></i>4 People</li>
                                    <li><i class="far fa-gas-pump"></i>Hybrid</li>
                                    <li><i class="far fa-steering-wheel"></i>Automatic</li>
                                </ul>
                                <div class="car-bottom">
                                    <div class="car-price">
                                        <span class="car-price-amount">$62 <span class="car-price-type">/Per
                                                Day</span></span>
                                    </div>
                                    <div class="car-text-btn">
                                        <a href="#">See Details <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="car-item">
                            <div class="car-img">
                                <img src="assets/img/car/02.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="car-content">
                                <h4 class="car-title"><a href="#">Toyota Corolla Car</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="car-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="car-rate-type">Excellent</span>
                                    <span class="car-rate-review">(2.5k Reviews)</span>
                                </div>
                                <ul class="car-info-list">
                                    <li><i class="far fa-car"></i>Model: 2025</li>
                                    <li><i class="far fa-user-tie"></i>4 People</li>
                                    <li><i class="far fa-gas-pump"></i>Hybrid</li>
                                    <li><i class="far fa-steering-wheel"></i>Automatic</li>
                                </ul>
                                <div class="car-bottom">
                                    <div class="car-price">
                                        <span class="car-price-amount">$45 <span class="car-price-type">/Per
                                                Day</span></span>
                                    </div>
                                    <div class="car-text-btn">
                                        <a href="#">See Details <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="car-item">
                            <div class="car-img">
                                <span class="badge">Popular</span>
                                <img src="assets/img/car/03.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="car-content">
                                <h4 class="car-title"><a href="#">Toyota Corolla Car</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="car-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="car-rate-type">Excellent</span>
                                    <span class="car-rate-review">(2.5k Reviews)</span>
                                </div>
                                <ul class="car-info-list">
                                    <li><i class="far fa-car"></i>Model: 2025</li>
                                    <li><i class="far fa-user-tie"></i>4 People</li>
                                    <li><i class="far fa-gas-pump"></i>Hybrid</li>
                                    <li><i class="far fa-steering-wheel"></i>Automatic</li>
                                </ul>
                                <div class="car-bottom">
                                    <div class="car-price">
                                        <span class="car-price-amount">$45 <span class="car-price-type">/Per
                                                Day</span></span>
                                    </div>
                                    <div class="car-text-btn">
                                        <a href="#">See Details <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="car-item">
                            <div class="car-img">
                                <img src="assets/img/car/04.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="car-content">
                                <h4 class="car-title"><a href="#">Toyota Corolla Car</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="car-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="car-rate-type">Excellent</span>
                                    <span class="car-rate-review">(2.5k Reviews)</span>
                                </div>
                                <ul class="car-info-list">
                                    <li><i class="far fa-car"></i>Model: 2025</li>
                                    <li><i class="far fa-user-tie"></i>4 People</li>
                                    <li><i class="far fa-gas-pump"></i>Hybrid</li>
                                    <li><i class="far fa-steering-wheel"></i>Automatic</li>
                                </ul>
                                <div class="car-bottom">
                                    <div class="car-price">
                                        <span class="car-price-amount">$45 <span class="car-price-type">/Per
                                                Day</span></span>
                                    </div>
                                    <div class="car-text-btn">
                                        <a href="#">See Details <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="car-item">
                            <div class="car-img">
                                <img src="assets/img/car/05.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="car-content">
                                <h4 class="car-title"><a href="#">Toyota Corolla Car</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="car-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="car-rate-type">Excellent</span>
                                    <span class="car-rate-review">(2.5k Reviews)</span>
                                </div>
                                <ul class="car-info-list">
                                    <li><i class="far fa-car"></i>Model: 2025</li>
                                    <li><i class="far fa-user-tie"></i>4 People</li>
                                    <li><i class="far fa-gas-pump"></i>Hybrid</li>
                                    <li><i class="far fa-steering-wheel"></i>Automatic</li>
                                </ul>
                                <div class="car-bottom">
                                    <div class="car-price">
                                        <span class="car-price-amount">$45 <span class="car-price-type">/Per
                                                Day</span></span>
                                    </div>
                                    <div class="car-text-btn">
                                        <a href="#">See Details <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="car-item">
                            <div class="car-img">
                                <img src="assets/img/car/06.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="car-content">
                                <h4 class="car-title"><a href="#">Toyota Corolla Car</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="car-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="car-rate-type">Excellent</span>
                                    <span class="car-rate-review">(2.5k Reviews)</span>
                                </div>
                                <ul class="car-info-list">
                                    <li><i class="far fa-car"></i>Model: 2025</li>
                                    <li><i class="far fa-user-tie"></i>4 People</li>
                                    <li><i class="far fa-gas-pump"></i>Hybrid</li>
                                    <li><i class="far fa-steering-wheel"></i>Automatic</li>
                                </ul>
                                <div class="car-bottom">
                                    <div class="car-price">
                                        <span class="car-price-amount">$45 <span class="car-price-type">/Per
                                                Day</span></span>
                                    </div>
                                    <div class="car-text-btn">
                                        <a href="#">See Details <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="car-item">
                            <div class="car-img">
                                <span class="badge badge-discount">25% Save</span>
                                <img src="assets/img/car/07.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="car-content">
                                <h4 class="car-title"><a href="#">Toyota Corolla Car</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="car-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="car-rate-type">Excellent</span>
                                    <span class="car-rate-review">(2.5k Reviews)</span>
                                </div>
                                <ul class="car-info-list">
                                    <li><i class="far fa-car"></i>Model: 2025</li>
                                    <li><i class="far fa-user-tie"></i>4 People</li>
                                    <li><i class="far fa-gas-pump"></i>Hybrid</li>
                                    <li><i class="far fa-steering-wheel"></i>Automatic</li>
                                </ul>
                                <div class="car-bottom">
                                    <div class="car-price">
                                        <span class="car-price-amount">$45 <span class="car-price-type">/Per
                                                Day</span></span>
                                    </div>
                                    <div class="car-text-btn">
                                        <a href="#">See Details <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="car-item">
                            <div class="car-img">
                                <img src="assets/img/car/08.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="car-content">
                                <h4 class="car-title"><a href="#">Toyota Corolla Car</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="car-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="car-rate-type">Excellent</span>
                                    <span class="car-rate-review">(2.5k Reviews)</span>
                                </div>
                                <ul class="car-info-list">
                                    <li><i class="far fa-car"></i>Model: 2025</li>
                                    <li><i class="far fa-user-tie"></i>4 People</li>
                                    <li><i class="far fa-gas-pump"></i>Hybrid</li>
                                    <li><i class="far fa-steering-wheel"></i>Automatic</li>
                                </ul>
                                <div class="car-bottom">
                                    <div class="car-price">
                                        <span class="car-price-amount">$45 <span class="car-price-type">/Per
                                                Day</span></span>
                                    </div>
                                    <div class="car-text-btn">
                                        <a href="#">See Details <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="car-item">
                            <div class="car-img">
                                <img src="assets/img/car/02.jpg" alt="">
                                <a href="#" class="add-wishlist"><i class="far fa-heart"></i></a>
                            </div>
                            <div class="car-content">
                                <h4 class="car-title"><a href="#">Toyota Corolla Car</a></h4>
                                <p><i class="far fa-location-dot"></i> 25/B Milford Road, New York</p>
                                <div class="car-rate">
                                    <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                    <span class="car-rate-type">Excellent</span>
                                    <span class="car-rate-review">(2.5k Reviews)</span>
                                </div>
                                <ul class="car-info-list">
                                    <li><i class="far fa-car"></i>Model: 2025</li>
                                    <li><i class="far fa-user-tie"></i>4 People</li>
                                    <li><i class="far fa-gas-pump"></i>Hybrid</li>
                                    <li><i class="far fa-steering-wheel"></i>Automatic</li>
                                </ul>
                                <div class="car-bottom">
                                    <div class="car-price">
                                        <span class="car-price-amount">$45 <span class="car-price-type">/Per
                                                Day</span></span>
                                    </div>
                                    <div class="car-text-btn">
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
                            <p>Showing 1 - 6 of 24 Cars</p>
                        </div>
                    </div>
                    <!-- pagination end -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- car grid end -->

@endsection

@section('modal')

@endsection

@section('scripts')

@endsection