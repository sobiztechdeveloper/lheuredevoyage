<div class="main-navigation">
    <nav class="navbar navbar-expand-lg ">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <div class="logo-wrapper">
                    <img src="{{ ($siteSettings ?? null)?->logo_url ?? asset('assets/img/logo/logo.png') }}" class="logo-display" alt="logo">
                    <img src="{{ ($siteSettings ?? null)?->logo_url ?? asset('assets/img/logo/logo-dark.png') }}" class="logo-scrolled" alt="logo">
                </div>

                <span class="brand-text ms-2">
                    {{ ($siteSettings ?? null)?->company_name ?? "L'Heure De Voyage" }}
                </span>
            </a>
            <div class="mobile-menu-right">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#main_nav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-btn-icon"><i class="far fa-bars"></i></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="main_nav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('flight') }}">
                            <i class="fas fa-plane me-1"></i> Flights
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('hotel') }}">
                            <i class="fas fa-hotel me-1"></i> Hotels
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cruise') }}">
                            <i class="fas fa-ship me-1"></i> Cruises
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('rentalcar') }}">
                            <i class="fas fa-car me-1"></i> Cars
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('travelinsurance') }}">
                            <i class="fas fa-shield-alt me-1"></i> Travel Insurances
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tourpackage') }}">
                            <i class="fas fa-binoculars me-1"></i> Tour Packages
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">
                            <i class="fas fa-phone me-1"></i> Contact
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">
                            <i class="fas fa-info-circle me-1"></i> About Us
                        </a>
                    </li>
                </ul>

                @auth
                <div class="header-nav-right">
                    <div class="header-account">
                        <div class="dropdown">
                            <div data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ asset('assets/img/account/user.jpg') }}" alt="">
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('my-dashboard') }}"><i class="far fa-gauge-high"></i> Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('my-profile') }}"><i class="far fa-user"></i> My Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('my-bookings-list') }}"><i class="far fa-shopping-bag"></i> My Booking</a></li>
                                <li><a class="dropdown-item" href="{{ route('my-flight-bookings.index') }}"><i class="far fa-plane"></i> My Flight Bookings</a></li>
                                <li><a class="dropdown-item" href="{{ route('my-quotes.index') }}"><i class="far fa-file-invoice-dollar"></i> My Quotes</a></li>

                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="far fa-sign-out"></i> Log Out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                @endauth
                <!-- @guest
                <div class="header-nav-right">
                    <div class="header-btn">
                        <a href="{{ route('login') }}" class="theme-btn mt-2">
                            <span class="far fa-sign-in"></span> Log In
                        </a>
                    </div>
                </div>
                @endguest -->
            </div>
        </div>
    </nav>
</div>