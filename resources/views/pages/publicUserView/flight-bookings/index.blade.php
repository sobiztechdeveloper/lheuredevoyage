@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')
<div class="col-lg-9">
    <div class="user-profile-card">
        <h4 class="user-profile-card-title">My Flight Bookings</h4>
        <p class="text-muted">Track your flight booking requests and consultant updates.</p>
        <div class="table-responsive">
            <table class="table text-nowrap">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Route</th>
                        <th>Travel Date</th>
                        <th>Passengers</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td><strong>{{ $booking->booking_reference }}</strong></td>
                            <td>{{ $booking->routeLabel() }}</td>
                            <td>{{ $booking->departure_date->format(config('date.display')) }}</td>
                            <td>{{ $booking->passengers_count }}</td>
                            <td><span class="badge bg-primary">{{ $booking->statusLabel() }}</span></td>
                            <td>{{ $booking->created_at->format(config('date.display')) }}</td>
                            <td><a href="{{ route('my-flight-bookings.show', $booking) }}">View Details</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted">No flight booking requests yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $bookings->links() }}
    </div>
</div>
@endsection
