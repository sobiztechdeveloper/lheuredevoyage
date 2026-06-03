@extends('layouts.admin.app')

@section('title', 'Flight Requests')

@section('content')
<div class="admin-page-header">
    <div>
        <h1>Flight Booking Requests</h1>
        <p class="text-muted small mb-0">Customer flight enquiries for manual ticketing — synced with the booking wizard</p>
    </div>
</div>

<div class="admin-table-card">
    <div class="admin-table-toolbar">
        <form method="GET" class="d-flex flex-wrap gap-2 align-items-end flex-grow-1">
            <div class="search-wrap mb-0 flex-grow-1" style="min-width:200px">
                <i class="far fa-search"></i>
                <input type="search" name="q" class="form-control" placeholder="Reference, name, email, route..." value="{{ $search }}">
            </div>
            <select name="status" class="form-select form-select-sm" style="width:auto">
                <option value="">All statuses</option>
                @foreach($statuses as $s)
                    <option value="{{ $s }}" @selected($filterStatus === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                @endforeach
            </select>
            <input type="date" name="from" class="form-control form-control-sm" style="width:auto" value="{{ $from }}" title="Travel from">
            <input type="date" name="to" class="form-control form-control-sm" style="width:auto" value="{{ $to }}" title="Travel to">
            <button type="submit" class="btn btn-admin-primary btn-sm">Filter</button>
            @if($search || $filterStatus || $from || $to)
                <a href="{{ route('admin.flight-requests.index') }}" class="btn btn-admin-outline btn-sm">Clear</a>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Customer</th>
                    <th>Route</th>
                    <th>Travel Date</th>
                    <th>Passengers</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                <tr>
                    <td><code>{{ $req->booking_reference }}</code></td>
                    <td>
                        {{ $req->contact_name }}<br>
                        <small class="text-muted">{{ $req->email }}</small>
                        @if($req->phone)<br><small class="text-muted">{{ $req->phone }}</small>@endif
                    </td>
                    <td>{{ $req->routeLabel() }}</td>
                    <td>{{ $req->departure_date->format('M d, Y') }}</td>
                    <td>{{ $req->passengers_count ?: $req->passengerCount() }}</td>
                    <td><x-flight-booking-status :status="$req->status" /></td>
                    <td><small class="text-muted">{{ $req->created_at->format('M d, Y') }}</small></td>
                    <td class="text-end">
                        <a href="{{ route('admin.flight-requests.show', $req) }}" class="btn btn-sm btn-admin-primary">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-5 text-muted">No flight booking requests yet.</td></tr>
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
