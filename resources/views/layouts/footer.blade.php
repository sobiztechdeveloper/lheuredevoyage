@php
    $settings = $siteSettings ?? \App\Models\WebsiteSetting::cached();
    $contact = $siteContact ?? \App\Models\ContactDetail::cached();
@endphp
<footer class="footer-area">
    <div class="footer-widget">
        <div class="container">
            <div class="row footer-widget-wrapper pt-100 pb-70">
                <div class="col-md-6 col-lg-3">
                    <div class="footer-widget-box about-us">
                        <a href="{{ route('home') }}" class="footer-logo d-flex align-items-center">
                            <img src="{{ $settings->logo_url }}" alt="{{ $settings->company_name }} Logo">
                            <span class="footer-brand-text ms-2">{{ $settings->company_name }}</span>
                        </a>
                        <p class="mb-4">{{ $settings->footer_text }}</p>
                        <ul class="footer-contact">
                            @if($settings->company_phone)
                            <li>
                                <div class="footer-call">
                                    <div class="footer-call-icon"><i class="fal fa-headset"></i></div>
                                    <div class="footer-call-info">
                                        <h6>24/7 Call Service</h6>
                                        <a href="tel:{{ preg_replace('/\s+/', '', $settings->company_phone) }}">{{ $settings->company_phone }}</a>
                                    </div>
                                </div>
                            </li>
                            @endif
                            @if($settings->company_address)
                            <li><i class="far fa-map-marker-alt"></i>{{ $settings->company_address }}</li>
                            @endif
                            @if($settings->company_email)
                            <li><a href="mailto:{{ $settings->company_email }}"><i class="far fa-envelopes"></i>{{ $settings->company_email }}</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-2">
                    <div class="footer-widget-box list">
                        <h4 class="footer-widget-title">Our Company</h4>
                        <ul class="footer-list">
                            <li><a href="{{ route('about') }}"><i class="fas fa-angle-double-right"></i> About Us</a></li>
                            <li><a href="{{ route('contact') }}"><i class="fas fa-angle-double-right"></i> Contact Us</a></li>
                            <li><a href="{{ route('hotel') }}"><i class="fas fa-angle-double-right"></i> Hotels</a></li>
                            <li><a href="{{ route('flight') }}"><i class="fas fa-angle-double-right"></i> Flights</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-2">
                    <div class="footer-widget-box list">
                        <h4 class="footer-widget-title">Travel Services</h4>
                        <ul class="footer-list">
                            <li><a href="{{ route('cruise') }}"><i class="fas fa-angle-double-right"></i> Cruises</a></li>
                            <li><a href="{{ route('rentalcar') }}"><i class="fas fa-angle-double-right"></i> Cars</a></li>
                            <li><a href="{{ route('travelinsurance') }}"><i class="fas fa-angle-double-right"></i> Insurance</a></li>
                            <li><a href="{{ route('tourpackage') }}"><i class="fas fa-angle-double-right"></i> Packages</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-2">
                    <div class="footer-widget-box list">
                        <h4 class="footer-widget-title">Help Center</h4>
                        <ul class="footer-list">
                            <li><a href="{{ route('about') }}#faqAccordion"><i class="fas fa-angle-double-right"></i> FAQ's</a></li>
                            @auth
                            <li><a href="{{ route('my-dashboard') }}"><i class="fas fa-angle-double-right"></i> My Account</a></li>
                            @else
                            <li><a href="{{ route('login') }}"><i class="fas fa-angle-double-right"></i> Login</a></li>
                            @endauth
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="footer-widget-box list">
                        <h4 class="footer-widget-title">Newsletter</h4>
                        <div class="footer-newsletter">
                            <p>Subscribe for latest offers and travel inspiration.</p>
                            <div class="subscribe-form">
                                <form action="#">
                                    <div class="form-group">
                                        <div class="form-group-icon">
                                            <input type="email" class="form-control" placeholder="Your Email">
                                            <i class="far fa-envelopes"></i>
                                        </div>
                                    </div>
                                    <button class="theme-btn" type="submit">Subscribe Now <i class="far fa-paper-plane"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-md-6 align-self-center">
                    <p class="copyright-text">
                        &copy; <span id="date"></span>
                        <a href="{{ route('home') }}">{{ $settings->company_name }}</a>
                        {{ $settings->copyright_text ?? 'All Rights Reserved.' }}
                    </p>
                </div>
                <div class="col-md-6 align-self-center">
                    <ul class="footer-social">
                        @if($settings->facebook_url)<li><a href="{{ $settings->facebook_url }}" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a></li>@endif
                        @if($settings->instagram_url)<li><a href="{{ $settings->instagram_url }}" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a></li>@endif
                        @if($settings->linkedin_url)<li><a href="{{ $settings->linkedin_url }}" target="_blank" rel="noopener"><i class="fab fa-linkedin-in"></i></a></li>@endif
                        @if($settings->youtube_url)<li><a href="{{ $settings->youtube_url }}" target="_blank" rel="noopener"><i class="fab fa-youtube"></i></a></li>@endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
