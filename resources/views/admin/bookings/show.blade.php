@extends('layouts.admin.app')

@section('title', 'Booking '.$booking->reference)

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <h2 class="h5">{{ $booking->reference }}</h2>
        <p class="mb-1"><strong>Customer:</strong> {{ $booking->user?->name }} ({{ $booking->user?->email }})</p>
        <p class="mb-1"><strong>Product:</strong> {{ class_basename($booking->bookable_type) }} — {{ $booking->bookable?->title }}</p>
        <p class="mb-1"><strong>Amount:</strong> {{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</p>
        <p class="mb-1"><strong>Booked:</strong> {{ ($booking->booked_at ?? $booking->created_at)->format('M d, Y H:i') }}</p>
        <p class="mb-0">
            <a href="{{ route('admin.bookings.invoice', $booking) }}" class="btn btn-outline-primary btn-sm">Invoice</a>
            <a href="{{ route('admin.bookings.invoice.pdf', $booking) }}" class="btn btn-outline-secondary btn-sm">PDF</a>
        </p>
        @if($booking->booking_data)
            <hr>
            <h6>Guest details</h6>
            <ul class="mb-0">
                @foreach($booking->booking_data as $key => $value)
                    @if($value)
                        <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</li>
                    @endif
                @endforeach
            </ul>
        @endif
    </div>
</div>

<form method="POST" action="{{ route('admin.bookings.update', $booking) }}" class="card">
    @csrf
    @method('PUT')
    <div class="card-body">
        <label class="form-label">Status</label>
        <select name="status" class="form-select w-auto">
            @foreach(['pending', 'confirmed', 'cancelled', 'completed'] as $status)
                <option value="{{ $status }}" @selected($booking->status === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary ms-2">Update Status</button>
    </div>
</form>

@if($booking->cancellationRequests->isNotEmpty())
<div class="card mt-4">
    <div class="card-header">Cancellation Requests</div>
    <ul class="list-group list-group-flush">
        @foreach($booking->cancellationRequests as $req)
            <li class="list-group-item">
                {{ ucfirst($req->status) }} — {{ $req->reason }}
                <a href="{{ route('admin.cancellation-requests.index', ['status' => 'pending']) }}" class="float-end small">Manage</a>
            </li>
        @endforeach
    </ul>
</div>
@endif

<div class="card mt-4">
    <div class="card-header">Status Timeline</div>
    <ul class="list-group list-group-flush">
        @forelse($booking->histories as $history)
            <li class="list-group-item">
                <strong>{{ ucfirst($history->status) }}</strong> — {{ $history->notes }}
                <span class="text-muted float-end">{{ $history->created_at->diffForHumans() }}</span>
            </li>
        @empty
            <li class="list-group-item text-muted">No history entries.</li>
        @endforelse
    </ul>
</div>

<a href="{{ route('admin.bookings.index') }}" class="btn btn-link mt-3">← Back to bookings</a>
@endsection
