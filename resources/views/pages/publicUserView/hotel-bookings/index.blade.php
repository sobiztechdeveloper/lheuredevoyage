@extends('layouts.app')
@section('content')
<div class="user-profile py-120">
    <div class="container">
        <div class="row">
            @include('layouts.publicUserAdmin.sidebar')
            <div class="col-lg-9">
                <div class="user-profile-wrapper">
                    <h4 class="user-profile-title">My Hotel Bookings</h4>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead><tr><th>Reference</th><th>Hotel</th><th>Check-In</th><th>Check-Out</th><th>Status</th><th></th></tr></thead>
                            <tbody>
                                @forelse($bookings as $b)
                                <tr>
                                    <td><code>{{ $b->reference_number }}</code></td>
                                    <td>{{ $b->hotel?->name }}</td>
                                    <td>{{ $b->check_in_date->format(config('date.display')) }}</td>
                                    <td>{{ $b->check_out_date->format(config('date.display')) }}</td>
                                    <td><x-hotel-booking-status :status="$b->status" /></td>
                                    <td><a href="{{ route('my-hotel-bookings.show', $b) }}" class="theme-btn btn-sm">View</a></td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">No hotel bookings yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
