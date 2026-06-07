@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')
<div class="col-lg-9">
    <div class="user-profile-card">
        <h4 class="user-profile-card-title mb-1">My Insurance Requests</h4>
        <p class="text-muted small mb-4">Track your insurance quote requests and agent responses.</p>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Plan</th>
                        <th>Destination</th>
                        <th>Travel dates</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr>
                        <td><code class="small">{{ $booking->reference_number }}</code></td>
                        <td>{{ $booking->travelInsurance?->name ?? '—' }}</td>
                        <td>{{ $booking->destination_country ?: $booking->destination ?: '—' }}</td>
                        <td class="small">{{ $booking->travel_start->format(config('date.display')) }} – {{ $booking->travel_end->format(config('date.display')) }}</td>
                        <td><x-insurance-booking-status :status="$booking->status" /></td>
                        <td><a href="{{ route('my-insurance-requests.show', $booking) }}">Details</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-muted text-center py-4">No insurance requests yet. <a href="{{ route('travelinsurance.quote.wizard') }}">Request a quote</a>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $bookings->links() }}
    </div>
</div>
@endsection
