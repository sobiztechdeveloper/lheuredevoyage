@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')
<div class="col-lg-9">
    <div class="user-profile-card">
        <h4 class="user-profile-card-title">My Cruise Bookings</h4>
        <p class="text-muted">Track your cruise booking requests and consultant updates.</p>
        <div class="table-responsive">
            <table class="table text-nowrap align-middle">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Cruise</th>
                        <th>Departure</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $b)
                        <tr>
                            <td><strong>{{ $b->reference_number }}</strong></td>
                            <td>{{ $b->cruise?->name }}</td>
                            <td>{{ $b->departure_date->format('M d, Y') }}</td>
                            <td><x-cruise-booking-status :status="$b->status" /></td>
                            <td><a href="{{ route('my-cruise-bookings.show', $b) }}">View Details</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted">No cruise bookings yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $bookings->links() }}
    </div>
</div>
@endsection
