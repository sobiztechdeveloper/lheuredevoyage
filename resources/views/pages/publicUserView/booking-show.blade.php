@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')
<div class="col-lg-9">
    <div class="user-profile-wrapper">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

        <div class="user-profile-card mb-3">
            <h4 class="user-profile-card-title">Booking {{ $booking->reference }}</h4>
            <p><strong>Product:</strong> {{ $booking->bookableTypeLabel() }} — {{ $booking->bookable?->title }}</p>
            <p><strong>Amount:</strong> {{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</p>
            <p><strong>Status:</strong> <span class="badge badge-secondary">{{ ucfirst($booking->status) }}</span></p>
            <p><strong>Booked:</strong> {{ ($booking->booked_at ?? $booking->created_at)->format('M d, Y H:i') }}</p>
            <div class="mt-3">
                <a href="{{ route('my-bookings.invoice', $booking) }}" class="theme-btn btn-sm">View Invoice</a>
                <a href="{{ route('my-bookings.invoice.pdf', $booking) }}" class="btn btn-outline-secondary btn-sm">Download PDF</a>
            </div>
        </div>

        @if($details = $booking->displayBookingDetails())
        <div class="user-profile-card mb-3">
            <h5>Booking Details</h5>
            <ul class="list-unstyled mb-0">
                @foreach($details as $label => $value)
                    <li class="mb-2"><strong>{{ $label }}:</strong> {{ $value }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="user-profile-card mb-3">
            <h5>Status Timeline</h5>
            <ul class="list-unstyled">
                @foreach($booking->histories as $history)
                    <li class="border-start border-3 border-primary ps-3 mb-3">
                        <strong>{{ ucfirst($history->status) }}</strong>
                        <span class="text-muted small d-block">{{ $history->created_at->format('M d, Y H:i') }}</span>
                        @if($history->notes)<span class="small">{{ $history->notes }}</span>@endif
                    </li>
                @endforeach
            </ul>
        </div>

        @if($booking->cancellationRequests->where('status', 'pending')->isNotEmpty())
            <div class="alert alert-warning">Cancellation request pending review.</div>
        @elseif(!in_array($booking->status, ['cancelled', 'completed']))
            <div class="user-profile-card">
                <h5>Request Cancellation</h5>
                <form method="POST" action="{{ route('my-bookings.cancel-request', $booking) }}">
                    @csrf
                    <textarea name="reason" class="form-control mb-2" rows="3" placeholder="Reason for cancellation" required></textarea>
                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Submit cancellation request?')">Request Cancellation</button>
                </form>
            </div>
        @endif

        <a href="{{ route('my-bookings-list') }}" class="btn btn-link mt-3">← Back to bookings</a>
    </div>
</div>
@endsection
