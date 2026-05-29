@extends('layouts.app')

@section('styles')

@endsection

@section('content')

 <!-- breadcrumb -->
        <div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/07.jpg)">
            <div class="container">
                <h2 class="breadcrumb-title">Car Single</h2>
                <ul class="breadcrumb-menu">
                    <li><a href="index.html">Home</a></li>
                    <li class="active">Car Single</li>
                </ul>
            </div>
        </div>
        <!-- breadcrumb end -->


        <!-- car-single -->
        <div class="car-single py-120">
            <div class="container">
                <div class="listing-wrapper">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="listing-content">
                                <div class="listing-slider owl-carousel owl-theme">
                                    <img src="assets/img/car/single-1.jpg" alt="">
                                    <img src="assets/img/car/single-2.jpg" alt="">
                                    <img src="assets/img/car/single-3.jpg" alt="">
                                </div>
                                <div class="listing-header">
                                    <div class="listing-header-info">
                                        <h4 class="listing-title">Toyota Corolla Car</h4>
                                        <p class="listing-location"><i class="far fa-location-dot"></i> 25/B Milford
                                            Road, New York</p>
                                    </div>
                                    <div class="listing-rate">
                                        <span class="badge"><i class="far fa-star"></i> 5.0</span>
                                        <span class="listing-rate-type">Excellent</span>
                                        <span class="listing-rate-review">(2.5k Reviews)</span>
                                    </div>
                                </div>
                                <div class="listing-item">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-3">
                                            <div class="listing-feature">
                                                <div class="listing-feature-icon">
                                                    <i class="far fa-car"></i>
                                                </div>
                                                <div class="listing-feature-content">
                                                    <h6>Model</h6>
                                                    <span>2025</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="listing-feature">
                                                <div class="listing-feature-icon">
                                                    <i class="far fa-user-tie"></i>
                                                </div>
                                                <div class="listing-feature-content">
                                                    <h6>People</h6>
                                                    <span>4</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="listing-feature">
                                                <div class="listing-feature-icon">
                                                    <i class="far fa-gas-pump"></i>
                                                </div>
                                                <div class="listing-feature-content">
                                                    <h6>Fuel Type</h6>
                                                    <span>Hybrid</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="listing-feature">
                                                <div class="listing-feature-icon">
                                                    <i class="far fa-steering-wheel"></i>
                                                </div>
                                                <div class="listing-feature-content">
                                                    <h6>Transmission</h6>
                                                    <span>Automatic</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="listing-item">
                                    <h4 class="mb-3">Description</h4>
                                    <p>There are many variations of passages of Lorem Ipsum available, but the majority
                                        have suffered alteration in some form, by injected humour, or randomised words
                                        which don't look even slightly believable. If you are going to use a passage of
                                        Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the
                                        middle of text. </p>
                                    <p class="mt-3">There are many variations of passages of Lorem Ipsum available, but
                                        the majority have suffered alteration in some form, by injected humour, or
                                        randomised words which don't look even slightly believable. If you are going to
                                        use a passage of Lorem Ipsum, you need to be sure there isn't anything
                                        embarrassing hidden in the middle of text. </p>
                                    <div class="listing-highlight">
                                        <h5>Highlights</h5>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <ul class="list">
                                                    <li>Contrary To Popular Belief</li>
                                                    <li>Sed Perspiciatis Unde Omnis</li>
                                                    <li>Quasi Architecto Beatae Vitae Dicta</li>
                                                    <li>Nemo Enim Ipsam Voluptatem Quia</li>
                                                    <li>Ratione quia Magni Dolores Eos</li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-6">
                                                <ul class="list">
                                                    <li>Contrary To Popular Belief</li>
                                                    <li>Sed Perspiciatis Unde Omnis</li>
                                                    <li>Quasi Architecto Beatae Vitae Dicta</li>
                                                    <li>Nemo Enim Ipsam Voluptatem Quia</li>
                                                    <li>Ratione quia Magni Dolores Eos</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="listing-item faq-area">
                                    <h4 class="mb-4">Faq's</h4>
                                    <div class="accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading1">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapse1" aria-expanded="true"
                                                    aria-controls="collapse1">
                                                    <span><i class="far fa-question"></i></span> What Are The
                                                    Charges Of Services ?
                                                </button>
                                            </h2>
                                            <div id="collapse1" class="accordion-collapse collapse show"
                                                aria-labelledby="heading1" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    We denounce with righteous indignation and dislike men who
                                                    are so beguiled and demoralized by the charms of pleasure of the
                                                    moment, so
                                                    blinded by desirente odio dignissim quam.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading2">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapse2"
                                                    aria-expanded="false" aria-controls="collapse2">
                                                    <span><i class="far fa-question"></i></span> How Can I Become A
                                                    Member
                                                    ?
                                                </button>
                                            </h2>
                                            <div id="collapse2" class="accordion-collapse collapse"
                                                aria-labelledby="heading2" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    We denounce with righteous indignation and dislike men who
                                                    are so beguiled and demoralized by the charms of pleasure of the
                                                    moment, so
                                                    blinded by desirente odio dignissim quam.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading3">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapse3"
                                                    aria-expanded="false" aria-controls="collapse3">
                                                    <span><i class="far fa-question"></i></span> Can I Upgrade My
                                                    Plan Any Time ?
                                                </button>
                                            </h2>
                                            <div id="collapse3" class="accordion-collapse collapse"
                                                aria-labelledby="heading3" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    We denounce with righteous indignation and dislike men who
                                                    are so beguiled and demoralized by the charms of pleasure of the
                                                    moment, so
                                                    blinded by desirente odio dignissim quam.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="listing-item">
                                    <h4 class="mb-3">Reviews</h4>
                                    <div class="listing-rating-box">
                                        <div class="listing-review-rating">
                                            <div class="listing-rating-count">
                                                <h2>4.5</h2>
                                                <div class="listing-rating-star">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="far fa-star"></i>
                                                </div>
                                                <p>Base On 15.5k Review</p>
                                            </div>
                                            <div class="listing-rating-range">
                                                <div class="listing-rating-range-item">
                                                    <div class="listing-rating-range-star">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                    </div>
                                                    <div class="listing-rating-range-bar">
                                                        <div class="listing-progress">
                                                            <div class="listing-progress-width" style="width: 90%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="listing-rating-range-percentage">
                                                        <span>90%</span>
                                                    </div>
                                                </div>
                                                <div class="listing-rating-range-item">
                                                    <div class="listing-rating-range-star">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="listing-rating-range-bar">
                                                        <div class="listing-progress">
                                                            <div class="listing-progress-width" style="width: 80%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="listing-rating-range-percentage">
                                                        <span>80%</span>
                                                    </div>
                                                </div>
                                                <div class="listing-rating-range-item">
                                                    <div class="listing-rating-range-star">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="listing-rating-range-bar">
                                                        <div class="listing-progress">
                                                            <div class="listing-progress-width" style="width: 59%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="listing-rating-range-percentage">
                                                        <span>59%</span>
                                                    </div>
                                                </div>
                                                <div class="listing-rating-range-item">
                                                    <div class="listing-rating-range-star">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="listing-rating-range-bar">
                                                        <div class="listing-progress">
                                                            <div class="listing-progress-width" style="width: 70%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="listing-rating-range-percentage">
                                                        <span>70%</span>
                                                    </div>
                                                </div>
                                                <div class="listing-rating-range-item">
                                                    <div class="listing-rating-range-star">
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                    <div class="listing-rating-range-bar">
                                                        <div class="listing-progress">
                                                            <div class="listing-progress-width" style="width: 49%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="listing-rating-range-percentage">
                                                        <span>49%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="listing-review">
                                            <h5>Showing 1.2k Reviews</h5>
                                            <div class="listing-review-item">
                                                <div class="listing-review-author">
                                                    <img src="assets/img/account/01.jpg" alt="">
                                                    <div class="listing-review-author-info">
                                                        <div>
                                                            <h6>Kenneth Evans</h6>
                                                            <span><i class="far fa-clock"></i> 1 day ago</span>
                                                        </div>
                                                        <div class="listing-review-author-rating">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p>
                                                    There are many variations of passages of Lorem Ipsum available,
                                                    but the majority have suffered alteration in some form, by
                                                    injected humourmust explain to you how all this mistaken idea of
                                                    denouncing pleasure words.
                                                </p>
                                                <div class="listing-review-reply">
                                                    <a href="#" class="review-reply-btn"><i class="fal fa-reply"></i>
                                                        Reply</a>
                                                    <div class="review-reaction">
                                                        <a href="#" class="review-like active"><i
                                                                class="fal fa-thumbs-up"></i> 15</a>
                                                        <a href="#" class="review-dislike"><i
                                                                class="fal fa-thumbs-down"></i> 05</a>
                                                        <a href="#" class="review-love"><i class="fal fa-heart"></i>
                                                            50</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="listing-review-item review-reply-item">
                                                <div class="listing-review-author">
                                                    <img src="assets/img/account/02.jpg" alt="">
                                                    <div class="listing-review-author-info">
                                                        <div>
                                                            <h6>Erich T. Genao</h6>
                                                            <span><i class="far fa-clock"></i> 1 day ago</span>
                                                        </div>
                                                        <div class="listing-review-author-rating">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p>
                                                    There are many variations of passages of Lorem Ipsum available,
                                                    but the majority have suffered alteration in some form, by
                                                    injected humour, or randomised words.
                                                </p>
                                                <div class="listing-review-reply">
                                                    <a href="#" class="review-reply-btn"><i class="fal fa-reply"></i>
                                                        Reply</a>
                                                    <div class="review-reaction">
                                                        <a href="#" class="review-like"><i class="fal fa-thumbs-up"></i>
                                                            15</a>
                                                        <a href="#" class="review-dislike active"><i
                                                                class="fal fa-thumbs-down"></i> 05</a>
                                                        <a href="#" class="review-love"><i class="fal fa-heart"></i>
                                                            50</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="listing-review-item">
                                                <div class="listing-review-author">
                                                    <img src="assets/img/account/03.jpg" alt="">
                                                    <div class="listing-review-author-info">
                                                        <div>
                                                            <h6>Jesse Sinkler</h6>
                                                            <span><i class="far fa-clock"></i> 1 day ago</span>
                                                        </div>
                                                        <div class="listing-review-author-rating">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p>
                                                    There are many variations of passages of Lorem Ipsum available,
                                                    but the majority have suffered alteration in some form, by
                                                    injected humourmust explain to you how all this mistaken idea of
                                                    denouncing pleasure words.
                                                </p>
                                                <div class="listing-review-reply">
                                                    <a href="#" class="review-reply-btn"><i class="fal fa-reply"></i>
                                                        Reply</a>
                                                    <div class="review-reaction">
                                                        <a href="#" class="review-like"><i class="fal fa-thumbs-up"></i>
                                                            15</a>
                                                        <a href="#" class="review-dislike"><i
                                                                class="fal fa-thumbs-down"></i> 05</a>
                                                        <a href="#" class="review-love active"><i
                                                                class="fal fa-heart"></i> 50</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center my-4">
                                                <a href="#" class="theme-btn"> <span class="far fa-sync-alt"></span>
                                                    Load More</a>
                                            </div>
                                        </div>
                                        <div class="listing-review-form">
                                            <h4>Leave A Review</h4>
                                            <form action="#">
                                                <div class="col-lg-12">
                                                    <div class="form-group mb-3">
                                                        <label class="star-label">Your Rating</label>
                                                        <div class="listing-review-form-star">
                                                            <div class="star-rating-wrapper">
                                                                <div class="star-rating-box">
                                                                    <input type="radio" name="rating" value="5"
                                                                        id="star-5"> <label for="star-5">&#9733;</label>
                                                                    <input type="radio" name="rating" value="4"
                                                                        id="star-4"> <label for="star-4">&#9733;</label>
                                                                    <input type="radio" name="rating" value="3"
                                                                        id="star-3"> <label for="star-3">&#9733;</label>
                                                                    <input type="radio" name="rating" value="2"
                                                                        id="star-2"> <label for="star-2">&#9733;</label>
                                                                    <input type="radio" name="rating" value="1"
                                                                        id="star-1"> <label for="star-1">&#9733;</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"
                                                                placeholder="Your Name*">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <input type="email" class="form-control"
                                                                placeholder="Your Email*">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <textarea class="form-control" cols="30" rows="5"
                                                        placeholder="Write Your Review*"></textarea>
                                                </div>
                                                <button class="theme-btn" type="button">Leave A Review</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="booking-sidebar listing-side-content">
                                <div class="booking-item">
                                    <div class="listing-price">
                                        <h4 class="listing-price-tag">Bestseller</h4>
                                        <div class="listing-price-amount">
                                            From <span>$450.00</span> <del>$590.00</del>
                                        </div>
                                    </div>
                                    <div class="search-form">
                                        <form action="#">
                                            <div class="tour-search-wrapper">
                                                <div class="row">
                                                    <div class="col-lg-12">
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
                                                    <div class="col-lg-12">
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
                                                    <div class="col-lg-12">
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
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label>Drop Off</label>
                                                            <div class="form-group-icon">
                                                                <input type="text" name="picking-up" class="form-control"
                                                                    value="New York, United States">
                                                                <i class="fal fa-location-dot"></i>
                                                            </div>
                                                            <p>City, Airport Or Address</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
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
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
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
                                                <div class="listing-side-btn">
                                                    <button type="submit" class="theme-btn"><span
                                                            class="far fa-shopping-bag"></span>Book Now</button>
                                                    <a href="#" class="border-btn"><i class="far fa-heart"></i> Add To Wishlist</a>        
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="listing-side-share">
                                        <a href="#"><i class="far fa-share-nodes"></i> Share</a>
                                        <span><i class="far fa-eye"></i> 250 Views</span>
                                    </div>
                                </div>
                            </div>
                            <div class="booking-sidebar listing-side-content mt-4">
                                <h4 class="booking-title">Why Book With Us?</h4>
                                <ul class="listing-side-list">
                                    <li><i class="far fa-dollar-sign"></i>Best Price Guarantee</li>
                                    <li><i class="far fa-headset"></i>24/7 Customer Care</li>
                                    <li><i class="far fa-globe"></i>Hand Picked Tours & Activities</li>
                                    <li><i class="far fa-flag"></i>Free Travel Insureance</li>
                                    <li><i class="far fa-car"></i>Comfortable And Hygienic Vehicle</li>
                                </ul>
                            </div>
                            <div class="booking-sidebar listing-side-content mt-4">
                                <h4 class="booking-title">Get A Question?</h4>
                                <p>It is a long established fact that a reader will be distracted by the readable content layout.</p>
                                <ul class="listing-side-list">
                                    <li><i class="far fa-phone"></i><a href="tel:+21234567897">+2 123 4567 897</a></li>
                                    <li><i class="far fa-envelope"></i><a href="mailto:info@example.com">info@example.com</a></li>
                                </ul>
                            </div>
                            <div class="booking-sidebar listing-side-content mt-4">
                                <h4 class="booking-title">Organized By</h4>
                                <div class="listing-side-organizer">
                                    <div class="listing-side-organizer-img">
                                        <img src="assets/img/account/01.jpg" alt="">
                                    </div>
                                    <div class="listing-side-organizer-info">
                                        <h5 class="listing-side-organizer-name">Roltak Travel Agency</h5>
                                        <p>Member Since 2025</p>
                                        <a href="#" class="theme-btn"><span class="far fa-paper-plane"></span> Send Message</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- car-single end -->

@endsection

@section('modal')

@endsection

@section('scripts')

@endsection