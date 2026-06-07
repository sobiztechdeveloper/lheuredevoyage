@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')

<div class="col-lg-9">
    <div class="user-profile-wrapper">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="user-profile-card">
            <h4 class="user-profile-card-title">My Bookings</h4>
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
                        @forelse($bookings as $index => $booking)
                        <tr>
                            <td>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}.</td>
                            <td><b>{{ $booking->reference }}</b></td>
                            <td>{{ $booking->bookableTypeLabel() }}<br><small>{{ $booking->bookable?->title }}</small>
                                @if($booking->travelersLabel())<br><small class="text-muted">{{ $booking->travelersLabel() }}</small>@endif
                            </td>
                            <td>{{ ($booking->booked_at ?? $booking->created_at)->format(config('date.display')) }}</td>
                            <td>${{ number_format($booking->total_amount, 2) }}</td>
                            <td><span class="badge badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($booking->status) }}</span></td>
                            <td><a href="{{ route('my-bookings.show', $booking) }}">Details</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center">No bookings yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $bookings->links() }}
        </div>
    </div>
</div>

@endsection