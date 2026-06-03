@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')

<div class="col-lg-9">
    <div class="user-profile-wrapper">
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="dashboard-widget dashboard-widget-color-1">
                    <div class="dashboard-widget-info">
                        <h1>{{ $stats['total'] }}</h1>
                        <span>Total Booking</span>
                    </div>
                    <div class="dashboard-widget-icon">
                        <i class="fal fa-shopping-bag"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="dashboard-widget dashboard-widget-color-2">
                    <div class="dashboard-widget-info">
                        <h1>{{ $stats['pending'] }}</h1>
                        <span>Pending Booking</span>
                    </div>
                    <div class="dashboard-widget-icon">
                        <i class="fal fa-loader"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="dashboard-widget dashboard-widget-color-3">
                    <div class="dashboard-widget-info">
                        <h1>${{ number_format($stats['earned'], 0) }}</h1>
                        <span>Total Spent</span>
                    </div>
                    <div class="dashboard-widget-icon">
                        <i class="fal fa-sack-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="user-profile-card">
                    <h4 class="user-profile-card-title">Sales Chart</h4>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="user-profile-card">
                    <h4 class="user-profile-card-title">Notifications</h4>
                    <div class="user-notification">
                        @forelse($notifications as $notification)
                            <div class="user-notification-item">
                                <a href="{{ route('my-notifications') }}">
                                    <div class="user-notification-icon">
                                        <i class="far fa-bell"></i>
                                    </div>
                                    <div class="user-notification-info">
                                        <p><b>{{ $notification->title }}</b> — {{ $notification->body }}</p>
                                        <span>{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No notifications yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="user-profile-card profile-booking">
                    <h4 class="user-profile-card-title">Recent Booking</h4>
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Booking ID</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentBookings as $index => $booking)
                                    <tr>
                                        <td>{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}.</td>
                                        <td><b>{{ $booking->reference }}</b></td>
                                        <td>{{ class_basename($booking->bookable_type) }}</td>
                                        <td>{{ ($booking->booked_at ?? $booking->created_at)->format('M d, Y') }}</td>
                                        <td>${{ number_format($booking->total_amount, 2) }}</td>
                                        <td><span class="badge badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($booking->status) }}</span></td>
                                        <td>
                                            <a href="{{ route('booking-history') }}" class="btn btn-outline-secondary btn-sm"><i class="far fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center">No bookings yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection