@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
@php
    use App\Models\Hotel;
    use App\Models\TourPackage;
    use App\Models\Cruise;
    use App\Models\RentalCar;
    use App\Models\TravelInsurance;
    use App\Models\Booking;
    use App\Models\FlightBookingRequest;
    use App\Models\HolidayPackageRequest;

    $catalogStats = [
        ['label' => 'Total Hotels', 'value' => Hotel::query()->count(), 'icon' => 'fa-hotel', 'class' => 'stat-icon-hotels', 'route' => 'admin.hotels.index'],
        ['label' => 'Tour Packages', 'value' => TourPackage::query()->count(), 'icon' => 'fa-umbrella-beach', 'class' => 'stat-icon-packages', 'route' => 'admin.packages.index'],
        ['label' => 'Total Cruises', 'value' => Cruise::query()->count(), 'icon' => 'fa-ship', 'class' => 'stat-icon-cruises', 'route' => 'admin.cruises.index'],
        ['label' => 'Rental Cars', 'value' => RentalCar::query()->count(), 'icon' => 'fa-car', 'class' => 'stat-icon-cars', 'route' => 'admin.cars.index'],
        ['label' => 'Travel Insurance', 'value' => TravelInsurance::query()->count(), 'icon' => 'fa-shield-halved', 'class' => 'stat-icon-insurance', 'route' => 'admin.insurances.index'],
        ['label' => 'Total Bookings', 'value' => $stats['bookings'] ?? Booking::query()->count(), 'icon' => 'fa-calendar-check', 'class' => 'stat-icon-bookings', 'route' => 'admin.bookings.index'],
        ['label' => 'Flight Requests', 'value' => $stats['flight_requests'] ?? FlightBookingRequest::query()->count(), 'icon' => 'fa-plane-circle-check', 'class' => 'stat-icon-bookings', 'route' => 'admin.flight-requests.index'],
        ['label' => 'Holiday Requests', 'value' => HolidayPackageRequest::query()->count(), 'icon' => 'fa-umbrella-beach', 'class' => 'stat-icon-packages', 'route' => 'admin.holiday-package-requests.index'],
    ];
@endphp

<div class="admin-dashboard">
<div class="admin-page-header">
    <div>
        <h1>Dashboard</h1>
        <p class="text-muted small mb-0">Welcome back — here's your travel platform at a glance.</p>
    </div>
</div>

<div class="row g-3 mb-3">
    @foreach($catalogStats as $stat)
    <div class="col-6 col-md-4 col-lg-3 col-xl">
        <a href="{{ route($stat['route']) }}" class="admin-stat-card">
            <div class="stat-icon {{ $stat['class'] }}"><i class="far {{ $stat['icon'] }}"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stat['value']) }}</div>
                <div class="stat-label">{{ $stat['label'] }}</div>
            </div>
        </a>
    </div>
    @endforeach
</div>

<div class="row g-3 mb-3">
    @foreach([
        ['label' => 'Total Quotes', 'key' => 'quotes_total', 'route' => 'admin.quotes.index'],
        ['label' => 'Pending Quotes', 'key' => 'quotes_pending', 'route' => 'admin.quotes.index'],
        ['label' => 'Accepted Quotes', 'key' => 'quotes_accepted', 'route' => 'admin.quotes.index'],
        ['label' => 'Rejected Quotes', 'key' => 'quotes_rejected', 'route' => 'admin.quotes.index'],
        ['label' => 'Expired Quotes', 'key' => 'quotes_expired', 'route' => 'admin.quotes.index'],
    ] as $qStat)
    <div class="col-6 col-md-4 col-lg">
        <a href="{{ route($qStat['route']) }}" class="admin-stat-card">
            <div class="stat-icon stat-icon-bookings"><i class="far fa-file-invoice-dollar"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ number_format($stats[$qStat['key']] ?? 0) }}</div>
                <div class="stat-label">{{ $qStat['label'] }}</div>
            </div>
        </a>
    </div>
    @endforeach
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="admin-panel-card">
            <h5 class="fw-bold mb-3" style="color: var(--admin-primary)"><i class="far fa-chart-line me-2"></i>Operations Overview</h5>
            <div class="row g-2">
                @foreach([
                    ['key' => 'bookings_pending', 'label' => 'Pending Bookings', 'badge' => 'badge-status-pending'],
                    ['key' => 'bookings_confirmed', 'label' => 'Confirmed Bookings', 'badge' => 'badge-status-active'],
                    ['key' => 'users', 'label' => 'Customers', 'badge' => 'badge-status-featured'],
                    ['key' => 'users_this_month', 'label' => 'New This Month', 'badge' => 'badge-status-featured'],
                    ['key' => 'messages', 'label' => 'Unread Inquiries', 'badge' => 'badge-status-pending'],
                    ['key' => 'tickets_open', 'label' => 'Open Tickets', 'badge' => 'badge-status-pending'],
                    ['key' => 'cancellations_pending', 'label' => 'Pending Cancellations', 'badge' => 'badge-status-pending'],
                    ['key' => 'flight_requests_new', 'label' => 'New Flight Requests', 'badge' => 'badge-status-pending'],
                ] as $row)
                <div class="col-md-6">
                    <div class="ops-metric d-flex justify-content-between align-items-center rounded" style="background:#f8fafc;border:1px solid var(--admin-border)">
                        <span class="fw-semibold">{{ $row['label'] }}</span>
                        <span class="badge-status {{ $row['badge'] }}">{{ $stats[$row['key']] ?? 0 }}</span>
                    </div>
                </div>
                @endforeach
                <div class="col-md-6">
                    <div class="ops-metric d-flex justify-content-between align-items-center rounded" style="background:#f8fafc;border:1px solid var(--admin-border)">
                        <span class="fw-semibold">Revenue (Month)</span>
                        <span class="fw-bold" style="color:var(--admin-primary)">${{ number_format($stats['revenue_month'] ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-panel-card h-100">
            <h5 class="fw-bold mb-3" style="color: var(--admin-primary)"><i class="far fa-bolt me-2"></i>Quick Actions</h5>
            <div class="d-grid gap-2 quick-actions">
                <a href="{{ route('admin.hotels.create') }}" class="btn btn-admin-outline text-start"><i class="far fa-hotel me-2"></i> New Hotel</a>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-admin-outline text-start"><i class="far fa-calendar-check me-2"></i> View Bookings</a>
                <a href="{{ route('admin.flight-requests.index', ['status' => 'new']) }}" class="btn btn-admin-outline text-start"><i class="far fa-plane-circle-check me-2"></i> Flight Requests @if(($stats['flight_requests_new'] ?? 0) > 0)<span class="badge bg-warning text-dark ms-1">{{ $stats['flight_requests_new'] }}</span>@endif</a>
                <a href="{{ route('admin.quotes.create') }}" class="btn btn-admin-outline text-start"><i class="far fa-file-invoice-dollar me-2"></i> Create Quote</a>
                <a href="{{ route('admin.quotes.index') }}" class="btn btn-admin-outline text-start"><i class="far fa-file-lines me-2"></i> Manage Quotes</a>
                <a href="{{ route('admin.inquiries.index') }}" class="btn btn-admin-outline text-start"><i class="far fa-envelope me-2"></i> Inquiries</a>
                <a href="{{ route('admin.reports.bookings') }}" class="btn btn-admin-secondary text-start"><i class="far fa-chart-column me-2"></i> Reports</a>
                <a href="{{ route('admin.settings.edit') }}" class="btn btn-admin-primary text-start"><i class="far fa-gear me-2"></i> Website Settings</a>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
