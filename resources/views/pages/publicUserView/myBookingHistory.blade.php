@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')

<div class="col-lg-9">
    <div class="user-profile-wrapper">
        <div class="user-profile-card">
            <h4 class="user-profile-card-title">Booking History</h4>
            <div class="table-responsive">
                <table class="table text-nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Booking ID</th>
                            <th>Product</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($histories as $index => $history)
                            <tr>
                                <td>{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}.</td>
                                <td><b>{{ $history->booking?->reference }}</b></td>
                                <td>{{ class_basename($history->booking?->bookable_type) }} — {{ $history->booking?->bookable?->title }}</td>
                                <td><span class="badge badge-{{ $history->status === 'confirmed' ? 'success' : ($history->status === 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($history->status) }}</span></td>
                                <td>{{ $history->created_at->format(config('date.display_datetime')) }}</td>
                                <td>{{ $history->notes }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">No booking history yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $histories->links() }}
        </div>
    </div>
</div>

@endsection
