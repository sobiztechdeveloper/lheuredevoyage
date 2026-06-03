@extends('layouts.admin.app')

@section('title', 'Bookings')

@section('content')
<div class="admin-page-header">
    <div><h1>Bookings</h1><p class="text-muted small mb-0">Customer reservations and status</p></div>
</div>

<div class="admin-table-card">
    <div class="admin-table-toolbar">
        <form method="GET" class="d-flex flex-wrap gap-2 align-items-center flex-grow-1">
            <div class="search-wrap mb-0">
                <i class="far fa-search"></i>
                <input type="search" name="q" class="form-control" placeholder="Search reference, status, customer..." value="{{ $search }}">
            </div>
            <button type="submit" class="btn btn-admin-primary btn-sm">Search</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr>
                    <td><code>{{ $booking->reference }}</code></td>
                    <td>{{ $booking->user?->name }}<br><small class="text-muted">{{ $booking->user?->email }}</small></td>
                    <td>{{ class_basename($booking->bookable_type) }}<br><small>{{ $booking->bookable?->title }}</small></td>
                    <td class="fw-semibold">{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</td>
                    <td>
                        @php $statusClass = match($booking->status) { 'confirmed','completed' => 'badge-status-active', 'pending' => 'badge-status-pending', default => 'badge-status-inactive' }; @endphp
                        <span class="badge-status {{ $statusClass }}">{{ ucfirst($booking->status) }}</span>
                    </td>
                    <td>{{ ($booking->booked_at ?? $booking->created_at)->format('M d, Y') }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-admin-primary">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">No bookings found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bookings->hasPages())<div class="admin-table-footer">{{ $bookings->links() }}</div>@endif
</div>
@endsection
