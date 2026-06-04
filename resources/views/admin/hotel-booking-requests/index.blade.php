@extends('layouts.admin.app')
@section('title', 'Hotel Requests')
@section('content')
<div class="admin-page-header">
    <div>
        <h1>Hotel Booking Requests</h1>
        <p class="text-muted small mb-0">Customer hotel enquiries — manual confirmation and quotations</p>
    </div>
</div>
<div class="admin-table-card">
    <div class="admin-table-toolbar">
        <form method="GET" class="d-flex flex-wrap gap-2 align-items-end flex-grow-1">
            <div class="search-wrap mb-0 flex-grow-1" style="min-width:200px">
                <i class="far fa-search"></i>
                <input type="search" name="q" class="form-control" placeholder="Reference, guest, hotel..." value="{{ $search }}">
            </div>
            <select name="status" class="form-select form-select-sm" style="width:auto">
                <option value="">All statuses</option>
                @foreach($statuses as $s)
                    <option value="{{ $s }}" @selected($filterStatus === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                @endforeach
            </select>
            <input type="date" name="check_in_from" class="form-control form-control-sm" value="{{ $checkInFrom }}" title="Check-in from">
            <input type="date" name="check_in_to" class="form-control form-control-sm" value="{{ $checkInTo }}" title="Check-in to">
            <button type="submit" class="btn btn-admin-primary btn-sm">Filter</button>
            @if($search || $filterStatus || $checkInFrom || $checkInTo)
                <a href="{{ route('admin.hotel-requests.index') }}" class="btn btn-admin-outline btn-sm">Clear</a>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Hotel</th>
                    <th>Guest</th>
                    <th>Check-In</th>
                    <th>Check-Out</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                <tr>
                    <td><code>{{ $req->reference_number }}</code></td>
                    <td>{{ $req->hotel?->name ?? '—' }}</td>
                    <td>
                        {{ $req->lead_guest_name }}<br>
                        <small class="text-muted">{{ $req->lead_guest_email }}</small>
                    </td>
                    <td>{{ $req->check_in_date->format('M d, Y') }}</td>
                    <td>{{ $req->check_out_date->format('M d, Y') }}</td>
                    <td><x-hotel-booking-status :status="$req->status" /></td>
                    <td><small class="text-muted">{{ $req->created_at->format('M d, Y') }}</small></td>
                    <td class="text-end">
                        <a href="{{ route('admin.hotel-requests.show', $req) }}" class="btn btn-sm btn-admin-primary">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-5 text-muted">No hotel booking requests yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($requests->hasPages())
        <div class="admin-table-footer">{{ $requests->links() }}</div>
    @endif
</div>
@endsection
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/flight-booking-admin.css') }}">
@endpush
