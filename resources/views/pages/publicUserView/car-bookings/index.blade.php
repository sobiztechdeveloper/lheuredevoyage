@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')
<div class="col-lg-9">
    <div class="user-profile-card">
        <h4 class="user-profile-card-title">My Car Bookings</h4>
        <p class="text-muted">Track your rental car booking requests and consultant updates.</p>
        <div class="table-responsive">
            <table class="table text-nowrap align-middle">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Vehicle</th>
                        <th>Pickup</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $b)
                        <tr>
                            <td><strong>{{ $b->reference_number }}</strong></td>
                            <td>{{ $b->rentalCar?->name }}</td>
                            <td>{{ $b->pickup_date->format(config('date.display')) }}</td>
                            <td><x-car-booking-status :status="$b->status" /></td>
                            <td><a href="{{ route('my-car-bookings.show', $b) }}">View Details</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted">No car bookings yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $bookings->links() }}
    </div>
</div>
@endsection
