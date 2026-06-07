<aside class="admin-sidebar" id="adminSidebar">
    <div class="admin-brand">
        <span class="admin-brand-icon"><i class="far fa-plane"></i></span>
        <span class="admin-brand-text">L'Heure De Voyage</span>
    </div>

    <nav class="admin-nav">
        <a href="{{ route('admin.dashboard') }}" class="nav-link-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="far fa-gauge-high"></i><span class="nav-label">Dashboard</span>
        </a>

        <button class="nav-section-title" type="button" data-bs-toggle="collapse" data-bs-target="#navCms" data-nav-section aria-expanded="true">
            <span>CMS</span><i class="far fa-chevron-down nav-chevron small"></i>
        </button>
        <div class="collapse show nav-submenu" id="navCms">
            <a href="{{ route('admin.settings.edit') }}" class="nav-link-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"><i class="far fa-globe"></i><span class="nav-label">Website Settings</span></a>
            <a href="{{ route('admin.hero-sections.index') }}" class="nav-link-item {{ request()->routeIs('admin.hero-sections.*') ? 'active' : '' }}"><i class="far fa-images"></i><span class="nav-label">Hero Sections</span></a>
            <a href="{{ route('admin.testimonials.index') }}" class="nav-link-item {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}"><i class="far fa-quote-left"></i><span class="nav-label">Testimonials</span></a>
            <a href="{{ route('admin.faqs.index') }}" class="nav-link-item {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}"><i class="far fa-circle-question"></i><span class="nav-label">FAQs</span></a>
            <a href="{{ route('admin.contact-details.edit') }}" class="nav-link-item {{ request()->routeIs('admin.contact-details.*') ? 'active' : '' }}"><i class="far fa-address-book"></i><span class="nav-label">Contact Details</span></a>
            <a href="{{ route('admin.home-blocks.index') }}" class="nav-link-item {{ request()->routeIs('admin.home-blocks.*') ? 'active' : '' }}"><i class="far fa-grid-2"></i><span class="nav-label">Home Blocks</span></a>
            <a href="{{ route('admin.about.edit') }}" class="nav-link-item {{ request()->routeIs('admin.about.*') ? 'active' : '' }}"><i class="far fa-file-lines"></i><span class="nav-label">About Page</span></a>
            <a href="{{ route('admin.legal-pages.index') }}" class="nav-link-item {{ request()->routeIs('admin.legal-pages.*') ? 'active' : '' }}"><i class="far fa-scale-balanced"></i><span class="nav-label">Legal Pages</span></a>
        </div>

        <button class="nav-section-title" type="button" data-bs-toggle="collapse" data-bs-target="#navCatalog" data-nav-section aria-expanded="true">
            <span>Catalog</span><i class="far fa-chevron-down nav-chevron small"></i>
        </button>
        <div class="collapse show nav-submenu" id="navCatalog">
            <a href="{{ route('admin.hotels.index') }}" class="nav-link-item {{ request()->routeIs('admin.hotels.*') ? 'active' : '' }}"><i class="far fa-hotel"></i><span class="nav-label">Hotels</span></a>
            <a href="{{ route('admin.flights.index') }}" class="nav-link-item {{ request()->routeIs('admin.flights.*') ? 'active' : '' }}"><i class="far fa-plane"></i><span class="nav-label">Flights</span></a>
            <a href="{{ route('admin.cruises.index') }}" class="nav-link-item {{ request()->routeIs('admin.cruises.*') ? 'active' : '' }}"><i class="far fa-ship"></i><span class="nav-label">Cruises</span></a>
            <a href="{{ route('admin.cars.index') }}" class="nav-link-item {{ request()->routeIs('admin.cars.*') ? 'active' : '' }}"><i class="far fa-car"></i><span class="nav-label">Rental Cars</span></a>
            <a href="{{ route('admin.insurances.index') }}" class="nav-link-item {{ request()->routeIs('admin.insurances.*') ? 'active' : '' }}"><i class="far fa-shield-halved"></i><span class="nav-label">Insurance</span></a>
            <a href="{{ route('admin.packages.index') }}" class="nav-link-item {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}"><i class="far fa-umbrella-beach"></i><span class="nav-label">Tour Packages</span></a>
        </div>

        <button class="nav-section-title" type="button" data-bs-toggle="collapse" data-bs-target="#navMaster" data-nav-section aria-expanded="{{ request()->routeIs('admin.master-data.*') || request()->routeIs('admin.destinations.*') || request()->routeIs('admin.airlines.*') || request()->routeIs('admin.cruise-lines.*') ? 'true' : 'false' }}">
            <span>Master Data</span><i class="far fa-chevron-down nav-chevron small"></i>
        </button>
        <div class="collapse {{ request()->routeIs('admin.master-data.*') || request()->routeIs('admin.destinations.*') || request()->routeIs('admin.airlines.*') || request()->routeIs('admin.cruise-lines.*') ? 'show' : '' }} nav-submenu" id="navMaster">
            <div class="nav-subgroup">
                <div class="nav-subgroup-title">General</div>
                <a href="{{ route('admin.destinations.index') }}" class="nav-link-item {{ request()->routeIs('admin.destinations.*') ? 'active' : '' }}"><i class="far fa-location-dot"></i><span class="nav-label">Destinations</span></a>
                <a href="{{ route('admin.airlines.index') }}" class="nav-link-item {{ request()->routeIs('admin.airlines.*') ? 'active' : '' }}"><i class="far fa-plane"></i><span class="nav-label">Airlines</span></a>
            </div>

            <div class="nav-subgroup">
                <div class="nav-subgroup-title">Hotels</div>
                <a href="{{ route('admin.master-data.hotel_facilities.index') }}" class="nav-link-item {{ request()->routeIs('admin.master-data.hotel_facilities.*') ? 'active' : '' }}"><i class="far fa-concierge-bell"></i><span class="nav-label">Facilities</span></a>
                <a href="{{ route('admin.master-data.hotel_sports.index') }}" class="nav-link-item {{ request()->routeIs('admin.master-data.hotel_sports.*') ? 'active' : '' }}"><i class="far fa-person-running"></i><span class="nav-label">Sports</span></a>
                <a href="{{ route('admin.master-data.hotel_wellness.index') }}" class="nav-link-item {{ request()->routeIs('admin.master-data.hotel_wellness.*') ? 'active' : '' }}"><i class="far fa-spa"></i><span class="nav-label">Wellness</span></a>
                <a href="{{ route('admin.master-data.hotel_beach_types.index') }}" class="nav-link-item {{ request()->routeIs('admin.master-data.hotel_beach_types.*') ? 'active' : '' }}"><i class="far fa-umbrella-beach"></i><span class="nav-label">Beach Types</span></a>
                <a href="{{ route('admin.master-data.room_types.index') }}" class="nav-link-item {{ request()->routeIs('admin.master-data.room_types.*') ? 'active' : '' }}"><i class="far fa-door-open"></i><span class="nav-label">Room Types</span></a>
                <a href="{{ route('admin.master-data.room_facilities.index') }}" class="nav-link-item {{ request()->routeIs('admin.master-data.room_facilities.*') ? 'active' : '' }}"><i class="far fa-bed"></i><span class="nav-label">Room Facilities</span></a>
                <a href="{{ route('admin.master-data.meal_plans.index') }}" class="nav-link-item {{ request()->routeIs('admin.master-data.meal_plans.*') ? 'active' : '' }}"><i class="far fa-utensils"></i><span class="nav-label">Meal Plans</span></a>
            </div>

            <div class="nav-subgroup">
                <div class="nav-subgroup-title">Packages</div>
                <a href="{{ route('admin.master-data.package_categories.index') }}" class="nav-link-item {{ request()->routeIs('admin.master-data.package_categories.*') ? 'active' : '' }}"><i class="far fa-tags"></i><span class="nav-label">Categories</span></a>
                <a href="{{ route('admin.master-data.package_themes.index') }}" class="nav-link-item {{ request()->routeIs('admin.master-data.package_themes.*') ? 'active' : '' }}"><i class="far fa-palette"></i><span class="nav-label">Themes</span></a>
            </div>

            <div class="nav-subgroup">
                <div class="nav-subgroup-title">Cruises</div>
                <a href="{{ route('admin.cruise-lines.index') }}" class="nav-link-item {{ request()->routeIs('admin.cruise-lines.*') ? 'active' : '' }}"><i class="far fa-ship"></i><span class="nav-label">Cruise Lines</span></a>
                <a href="{{ route('admin.master-data.cruise_categories.index') }}" class="nav-link-item {{ request()->routeIs('admin.master-data.cruise_categories.*') ? 'active' : '' }}"><i class="far fa-layer-group"></i><span class="nav-label">Categories</span></a>
                <a href="{{ route('admin.master-data.cruise_facilities.index') }}" class="nav-link-item {{ request()->routeIs('admin.master-data.cruise_facilities.*') ? 'active' : '' }}"><i class="far fa-list-check"></i><span class="nav-label">Facilities</span></a>
            </div>

            <div class="nav-subgroup">
                <div class="nav-subgroup-title">Rental Cars</div>
                <a href="{{ route('admin.master-data.vehicle_types.index') }}" class="nav-link-item {{ request()->routeIs('admin.master-data.vehicle_types.*') ? 'active' : '' }}"><i class="far fa-car"></i><span class="nav-label">Vehicle Types</span></a>
                <a href="{{ route('admin.master-data.vehicle_features.index') }}" class="nav-link-item {{ request()->routeIs('admin.master-data.vehicle_features.*') ? 'active' : '' }}"><i class="far fa-gears"></i><span class="nav-label">Vehicle Features</span></a>
            </div>

            <div class="nav-subgroup">
                <div class="nav-subgroup-title">Insurance</div>
                <a href="{{ route('admin.master-data.insurance_types.index') }}" class="nav-link-item {{ request()->routeIs('admin.master-data.insurance_types.*') ? 'active' : '' }}"><i class="far fa-shield-halved"></i><span class="nav-label">Insurance Types</span></a>
                <a href="{{ route('admin.master-data.insurance_coverage_types.index') }}" class="nav-link-item {{ request()->routeIs('admin.master-data.insurance_coverage_types.*') ? 'active' : '' }}"><i class="far fa-file-contract"></i><span class="nav-label">Coverage Types</span></a>
            </div>
        </div>

        <button class="nav-section-title" type="button" data-bs-toggle="collapse" data-bs-target="#navOps" data-nav-section aria-expanded="true">
            <span>Operations</span><i class="far fa-chevron-down nav-chevron small"></i>
        </button>
        <div class="collapse show nav-submenu" id="navOps">
            <a href="{{ route('admin.users.index') }}" class="nav-link-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="far fa-users"></i><span class="nav-label">Users</span></a>
            <a href="{{ route('admin.bookings.index') }}" class="nav-link-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}"><i class="far fa-calendar-check"></i><span class="nav-label">Bookings</span></a>
            <a href="{{ route('admin.flight-requests.index') }}" class="nav-link-item {{ request()->routeIs('admin.flight-requests.*') ? 'active' : '' }}">
                <i class="far fa-plane-circle-check"></i><span class="nav-label">Flight Requests</span>
                @if(($flightRequestsNewCount ?? 0) > 0)
                    <span class="badge rounded-pill bg-warning text-dark ms-auto">{{ $flightRequestsNewCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.hotel-requests.index') }}" class="nav-link-item {{ request()->routeIs('admin.hotel-requests.*') ? 'active' : '' }}">
                <i class="far fa-hotel"></i><span class="nav-label">Hotel Requests</span>
                @if(($hotelRequestsNewCount ?? 0) > 0)
                    <span class="badge rounded-pill bg-warning text-dark ms-auto">{{ $hotelRequestsNewCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.cruise-requests.index') }}" class="nav-link-item {{ request()->routeIs('admin.cruise-requests.*') ? 'active' : '' }}">
                <i class="far fa-ship"></i><span class="nav-label">Cruise Requests</span>
                @if(($cruiseRequestsNewCount ?? 0) > 0)
                    <span class="badge rounded-pill bg-warning text-dark ms-auto">{{ $cruiseRequestsNewCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.car-requests.index') }}" class="nav-link-item {{ request()->routeIs('admin.car-requests.*') ? 'active' : '' }}">
                <i class="far fa-car"></i><span class="nav-label">Car Requests</span>
                @if(($carRequestsNewCount ?? 0) > 0)
                    <span class="badge rounded-pill bg-warning text-dark ms-auto">{{ $carRequestsNewCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.insurance-requests.index') }}" class="nav-link-item {{ request()->routeIs('admin.insurance-requests.*') ? 'active' : '' }}">
                <i class="far fa-shield-halved"></i><span class="nav-label">Insurance Requests</span>
                @if(($insuranceRequestsNewCount ?? 0) > 0)
                    <span class="badge rounded-pill bg-warning text-dark ms-auto">{{ $insuranceRequestsNewCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.quotes.index') }}" class="nav-link-item {{ request()->routeIs('admin.quotes.*') ? 'active' : '' }}"><i class="far fa-file-invoice-dollar"></i><span class="nav-label">Quotes</span></a>
            <a href="{{ route('admin.cancellation-requests.index') }}" class="nav-link-item {{ request()->routeIs('admin.cancellation-requests.*') ? 'active' : '' }}"><i class="far fa-ban"></i><span class="nav-label">Cancellations</span></a>
            <a href="{{ route('admin.support-tickets.index') }}" class="nav-link-item {{ request()->routeIs('admin.support-tickets.*') ? 'active' : '' }}"><i class="far fa-life-ring"></i><span class="nav-label">Support</span></a>
            <a href="{{ route('admin.inquiries.index') }}" class="nav-link-item {{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}"><i class="far fa-envelope"></i><span class="nav-label">Inquiries</span></a>
            <a href="{{ route('admin.activity-logs.index') }}" class="nav-link-item {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}"><i class="far fa-clock-rotate-left"></i><span class="nav-label">Activity Logs</span></a>
        </div>

        <button class="nav-section-title" type="button" data-bs-toggle="collapse" data-bs-target="#navReports" data-nav-section aria-expanded="true">
            <span>Reports</span><i class="far fa-chevron-down nav-chevron small"></i>
        </button>
        <div class="collapse show nav-submenu" id="navReports">
            <a href="{{ route('admin.reports.bookings') }}" class="nav-link-item {{ request()->routeIs('admin.reports.bookings') ? 'active' : '' }}"><i class="far fa-chart-column"></i><span class="nav-label">Booking Reports</span></a>
            <a href="{{ route('admin.reports.customers') }}" class="nav-link-item {{ request()->routeIs('admin.reports.customers') ? 'active' : '' }}"><i class="far fa-chart-pie"></i><span class="nav-label">Customer Reports</span></a>
        </div>
    </nav>

    <div class="admin-sidebar-footer">
        <a href="{{ route('home') }}" target="_blank" class="nav-link-item">
            <i class="far fa-arrow-up-right-from-square"></i><span class="nav-label">View Site</span>
        </a>
    </div>
</aside>
