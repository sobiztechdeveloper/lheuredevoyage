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
                        <a href="{{ route('home') }}" class="footer-logo footer-logo-stack">
                            <img src="{{ $settings->logo_url }}" alt="{{ $settings->company_name }} Logo">
                            <span class="footer-brand-text">{{ $settings->company_name }}</span>
                        </a>
                        @if($settings->footer_text)
                            <p class="mb-4">{{ $settings->footer_text }}</p>
                        @endif
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
                            <li class="footer-address-item">
                                <i class="far fa-map-marker-alt"></i>
                                <address class="footer-address-text mb-0">{!! nl2br(e($settings->company_address)) !!}</address>
                            </li>
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
                            <li><a href="{{ route('about') }}#faqAccordion"><i class="fas fa-angle-double-right"></i> FAQ</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-2">
                    <div class="footer-widget-box list">
                        <h4 class="footer-widget-title">Travel Services</h4>
                        <ul class="footer-list">
                            <li><a href="{{ route('flight') }}"><i class="fas fa-angle-double-right"></i> Flights</a></li>
                            <li><a href="{{ route('hotel') }}"><i class="fas fa-angle-double-right"></i> Hotels</a></li>
                            <li><a href="{{ route('cruise') }}"><i class="fas fa-angle-double-right"></i> Cruises</a></li>
                            <li><a href="{{ route('rentalcar') }}"><i class="fas fa-angle-double-right"></i> Cars</a></li>
                            <li><a href="{{ route('travelinsurance') }}"><i class="fas fa-angle-double-right"></i> Insurance</a></li>
                            <li><a href="{{ route('tourpackage') }}"><i class="fas fa-angle-double-right"></i> Holiday Packages</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-2">
                    <div class="footer-widget-box list">
                        <h4 class="footer-widget-title">Help &amp; Legal</h4>
                        <ul class="footer-list">
                            @foreach($legalFooterPages ?? [] as $legalPage)
                                <li>
                                    <a href="{{ $legalPage->publicUrl() }}">
                                        <i class="fas fa-angle-double-right"></i> {{ $legalPage->footerLabel() }}
                                    </a>
                                </li>
                            @endforeach
                            <li><a href="{{ route('cookie-settings') }}"><i class="fas fa-angle-double-right"></i> Cookie Settings</a></li>
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
                                <form action="#" method="post" aria-label="Newsletter signup">
                                    <div class="form-group">
                                        <div class="form-group-icon">
                                            <input type="email" class="form-control" name="email" placeholder="Your Email" autocomplete="email">
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
            <div class="row align-items-center">
                <div class="col-lg-4 col-md-12">
                    <p class="copyright-text">
                        &copy; <span id="date"></span>
                        <a href="{{ route('home') }}">{{ $settings->company_name }}</a>
                    </p>
                </div>
                @if(($legalFooterBarPages ?? collect())->isNotEmpty())
                <div class="col-lg-4 col-md-12">
                    <ul class="footer-menu footer-legal-menu">
                        @foreach($legalFooterBarPages as $legalPage)
                            <li><a href="{{ $legalPage->publicUrl() }}">{{ $legalPage->footerLabel() }}</a></li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="col-lg-{{ ($legalFooterBarPages ?? collect())->isNotEmpty() ? '4' : '8' }} col-md-12">
                    <ul class="footer-social">
                        @if($settings->facebook_url)<li><a href="{{ $settings->facebook_url }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a></li>@endif
                        @if($settings->instagram_url)<li><a href="{{ $settings->instagram_url }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fab fa-instagram"></i></a></li>@endif
                        @if($settings->linkedin_url)<li><a href="{{ $settings->linkedin_url }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a></li>@endif
                        @if($settings->youtube_url)<li><a href="{{ $settings->youtube_url }}" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><i class="fab fa-youtube"></i></a></li>@endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
