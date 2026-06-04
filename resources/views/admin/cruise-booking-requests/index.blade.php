@extends('layouts.admin.app')
@section('title', 'Cruise Requests')
@section('content')
<div class="admin-page-header">
    <div>
        <h1>Cruise Booking Requests</h1>
        <p class="text-muted small mb-0">Customer cruise enquiries and quotations</p>
    </div>
</div>
<div class="admin-table-card">
    <div class="admin-table-toolbar">
        <form method="GET" class="d-flex flex-wrap gap-2 align-items-end flex-grow-1">
            <div class="search-wrap mb-0 flex-grow-1" style="min-width:200px">
                <i class="far fa-search"></i>
                <input type="search" name="q" class="form-control" placeholder="Reference, contact, cruise..." value="{{ $search }}">
            </div>
            <select name="status" class="form-select form-select-sm" style="width:auto">
                <option value="">All statuses</option>
                @foreach($statuses as $s)
                    <option value="{{ $s }}" @selected($filterStatus === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                @endforeach
            </select>
            <input type="date" name="departure_from" class="form-control form-control-sm" value="{{ $departureFrom }}" title="Departure from">
            <input type="date" name="departure_to" class="form-control form-control-sm" value="{{ $departureTo }}" title="Departure to">
            <button type="submit" class="btn btn-admin-primary btn-sm">Filter</button>
            @if($search || $filterStatus || $departureFrom || $departureTo)
                <a href="{{ route('admin.cruise-requests.index') }}" class="btn btn-admin-outline btn-sm">Clear</a>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Cruise</th>
                    <th>Contact</th>
                    <th>Departure</th>
                    <th>Passengers</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                <tr>
                    <td><code>{{ $req->reference_number }}</code></td>
                    <td>{{ $req->cruise?->name ?? '—' }}</td>
                    <td>
                        {{ $req->contact_name }}<br>
                        <small class="text-muted">{{ $req->contact_email }}</small>
                    </td>
                    <td>{{ $req->departure_date->format('M d, Y') }}</td>
                    <td>{{ $req->passengers_count ?: $req->passengerCount() }}</td>
                    <td><x-cruise-booking-status :status="$req->status" /></td>
                    <td><small class="text-muted">{{ $req->created_at->format('M d, Y') }}</small></td>
                    <td class="text-end">
                        <a href="{{ route('admin.cruise-requests.show', $req) }}" class="btn btn-sm btn-admin-primary">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-5 text-muted">No cruise booking requests yet.</td></tr>
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
