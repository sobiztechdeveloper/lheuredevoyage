@extends('layouts.app')

@section('body-class', 'home-3')

@section('styles')

@endsection

@section('content')

@php
    $aboutPage = $about ?? \App\Models\About::query()->where('is_active', true)->latest()->first();
@endphp

<x-site-breadcrumb
    title="About Us"
    page="about"
    :image="$aboutPage?->breadcrumb_image ? $aboutPage->breadcrumb_image_url : null"
/>

<!-- about-area -->
<div class="about-area py-120">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about-left wow fadeInLeft" data-wow-delay=".25s">
                    <div class="about-img">
                        <div class="row">
                            <div class="col-6">
                                <img class="img-1" src="{{ $about->image_primary_url }}" alt="">
                            </div>
                            <div class="col-6">
                                <img class="img-2" src="{{ $about->image_secondary_url }}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="about-experience">
                        <h5>{{ $about->experience_years ?? 30 }}<span>+</span></h5>
                        <p>Years Of Experience</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-right wow fadeInUp" data-wow-delay=".25s">
                    <div class="site-heading mb-3">
                        <span class="site-title-tagline"><i class="far fa-plane"></i> {{ $about->subheading ?? 'About Us' }}</span>
                        <h2 class="site-title">{{ $about->heading ?? 'We Are The World Best Travel Booking Agency Company' }}</h2>
                    </div>
                    <p class="about-text">{{ $about->content ?? 'Discover unforgettable journeys with L\'Heure De Voyage.' }}</p>
                    <div class="about-content">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="about-item">
                                    <div class="icon">
                                        <img src="assets/img/icon/deal.svg" alt="">
                                    </div>
                                    <div class="content">
                                        <h6>Get Your Best Deals</h6>
                                        <p>Take a look at our up of the round shows</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="about-item">
                                    <div class="icon">
                                        <img src="assets/img/icon/booking.svg" alt="">
                                    </div>
                                    <div class="content">
                                        <h6>Easy To Booking</h6>
                                        <p>Take a look at our up of the round shows</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="about.html" class="theme-btn">Discover More <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- about-area end -->

<!-- counter area -->
<div class="counter-area">
    <div class="container">
        <div class="counter-wrap rounded-5">
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="counter-box">
                        <div class="icon">
                            <img src="assets/img/icon/booking-confirm.svg" alt="">
                        </div>
                        <div class="counter-content">
                            <div class="counter-number">
                                <span class="counter" data-count="+" data-to="120" data-speed="3000">120</span>
                                <span class="counter-sign">k</span>
                            </div>
                            <h6 class="title">Booking Done</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="counter-box">
                        <div class="icon">
                            <img src="assets/img/icon/destination.svg" alt="">
                        </div>
                        <div class="counter-content">
                            <div class="counter-number">
                                <span class="counter" data-count="+" data-to="200" data-speed="3000">200</span>
                                <span class="counter-sign">+</span>
                            </div>
                            <h6 class="title">Our Destination</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="counter-box">
                        <div class="icon">
                            <img src="assets/img/icon/rating.svg" alt="">
                        </div>
                        <div class="counter-content">
                            <div class="counter-number">
                                <span class="counter" data-count="+" data-to="40" data-speed="3000">40</span>
                                <span class="counter-sign">k</span>
                            </div>
                            <h6 class="title">Happy Clients</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="counter-box">
                        <div class="icon">
                            <img src="assets/img/icon/partner.svg" alt="">
                        </div>
                        <div class="counter-content">
                            <div class="counter-number">
                                <span class="counter" data-count="+" data-to="180" data-speed="3000">180</span>
                                <span class="counter-sign">+</span>
                            </div>
                            <h6 class="title">Our Partners</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- counter area end -->


<!-- team-area -->
<!-- <div class="team-area py-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                <div class="site-heading text-center">
                    <span class="site-title-tagline"><i class="far fa-plane"></i> Our Team</span>
                    <h2 class="site-title">Meet With Our Experts Team</h2>
                </div>
            </div>
        </div>
        <div class="row g-5">
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="team-item wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                    <div class="team-img">
                        <img src="assets/img/team/01.jpg" alt="thumb">
                    </div>
                    <div class="team-content">
                        <div class="team-bio">
                            <h5><a href="#">Edna Craig</a></h5>
                            <span>Head of Design</span>
                        </div>
                        <div class="team-social">
                            <ul class="team-social-btn">
                                <li><span><i class="far fa-share-alt"></i></span></li>
                                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="#"><i class="fab fa-x-twitter"></i></a></li>
                                <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="team-item wow fadeInUp" data-wow-duration="1s" data-wow-delay=".50s">
                    <div class="team-img">
                        <img src="assets/img/team/02.jpg" alt="thumb">
                    </div>
                    <div class="team-content">
                        <div class="team-bio">
                            <h5><a href="#">Jeffrey Cox</a></h5>
                            <span>Founder & Director</span>
                        </div>
                        <div class="team-social">
                            <ul class="team-social-btn">
                                <li><span><i class="far fa-share-alt"></i></span></li>
                                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="#"><i class="fab fa-x-twitter"></i></a></li>
                                <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="team-item wow fadeInUp" data-wow-duration="1s" data-wow-delay=".75s">
                    <div class="team-img">
                        <img src="assets/img/team/03.jpg" alt="thumb">
                    </div>
                    <div class="team-content">
                        <div class="team-bio">
                            <h5><a href="#">Audrey Gadis</a></h5>
                            <span>Sales Support</span>
                        </div>
                        <div class="team-social">
                            <ul class="team-social-btn">
                                <li><span><i class="far fa-share-alt"></i></span></li>
                                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="#"><i class="fab fa-x-twitter"></i></a></li>
                                <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="team-item wow fadeInUp" data-wow-duration="1s" data-wow-delay="1s">
                    <div class="team-img">
                        <img src="assets/img/team/04.jpg" alt="thumb">
                    </div>
                    <div class="team-content">
                        <div class="team-bio">
                            <h5><a href="#">Rodger Garza</a></h5>
                            <span>Account Manager</span>
                        </div>
                        <div class="team-social">
                            <ul class="team-social-btn">
                                <li><span><i class="far fa-share-alt"></i></span></li>
                                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="#"><i class="fab fa-x-twitter"></i></a></li>
                                <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
<!-- team-area end -->


@include('partials.cms.testimonials', [
    'testimonials' => $testimonials ?? collect(),
    'sectionTitle' => 'What Our Customers Are Saying About Us?',
])


<!-- cta-area -->
<!-- <div class="cta-area py-120">
    <div class="container">
        <div class="cta-wrapper">
            <div class="col-md-10 col-lg-8 col-xl-6 mx-auto">
                <div class="cta-content">
                    <div class="cta-text">
                        <h1>First Booking <span>Get 70%</span> Discount!</h1>
                        <p>It is a long established fact that a reader will be distracted by the readable
                            content web page editors now use of a page when looking at its layout.</p>
                    </div>
                    <a href="contact.html" class="theme-btn mt-20">Book Now <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="cta-img">
                <img src="assets/img/cta/01.jpg" alt="">
            </div>
        </div>
    </div>
</div> -->
<!-- cta-area end -->


<!-- partner area -->
<div class="partner-area2 bg pt-40 pb-40">
    <div class="container">
        <div class="partner-slider owl-carousel owl-theme">
            <img src="assets/img/partner/01.png" alt="thumb">
            <img src="assets/img/partner/02.png" alt="thumb">
            <img src="assets/img/partner/03.png" alt="thumb">
            <img src="assets/img/partner/04.png" alt="thumb">
            <img src="assets/img/partner/01.png" alt="thumb">
            <img src="assets/img/partner/02.png" alt="thumb">
            <img src="assets/img/partner/03.png" alt="thumb">
        </div>
    </div>
</div>
<!-- partner area end -->

@include('partials.cms.faqs', [
    'faqs' => $faqs ?? collect(),
    'sectionClass' => 'inner-page-faq',
])

@endsection

@section('modal')

@endsection

@section('scripts')

@endsection