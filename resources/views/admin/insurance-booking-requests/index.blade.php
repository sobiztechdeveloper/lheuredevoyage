@extends('layouts.admin.app')
@section('title', 'Insurance Requests')
@section('content')
<div class="admin-page-header">
    <div>
        <h1>Insurance Quote Requests</h1>
        <p class="text-muted small mb-0">Customer insurance quote enquiries — processed manually by agents</p>
    </div>
</div>
<div class="admin-table-card">
    <div class="admin-table-toolbar">
        <form method="GET" class="d-flex flex-wrap gap-2 align-items-end flex-grow-1">
            <div class="search-wrap mb-0 flex-grow-1" style="min-width:180px">
                <i class="far fa-search"></i>
                <input type="search" name="q" class="form-control" placeholder="Reference, email, phone..." value="{{ $search }}">
            </div>
            <input type="text" name="destination" class="form-control form-control-sm" placeholder="Destination" value="{{ $filterDestination }}" style="width:130px">
            <input type="text" name="company" class="form-control form-control-sm" placeholder="Insurer" value="{{ $filterCompany }}" style="width:120px">
            <select name="status" class="form-select form-select-sm" style="width:auto">
                <option value="">All statuses</option>
                @foreach($statuses as $s)
                    <option value="{{ $s }}" @selected($filterStatus === $s)>{{ config('insurance.request_statuses.'.$s, ucfirst(str_replace('_', ' ', $s))) }}</option>
                @endforeach
            </select>
            <input type="date" name="travel_from" class="form-control form-control-sm" value="{{ $travelFrom }}" title="Travel from">
            <input type="date" name="travel_to" class="form-control form-control-sm" value="{{ $travelTo }}" title="Travel to">
            <input type="date" name="submitted_from" class="form-control form-control-sm" value="{{ $submittedFrom }}" title="Submitted from">
            <input type="date" name="submitted_to" class="form-control form-control-sm" value="{{ $submittedTo }}" title="Submitted to">
            <button type="submit" class="btn btn-admin-primary btn-sm">Filter</button>
            @if($search || $filterStatus || $filterDestination || $filterCompany || $travelFrom || $travelTo || $submittedFrom || $submittedTo)
                <a href="{{ route('admin.insurance-requests.index') }}" class="btn btn-admin-outline btn-sm">Clear</a>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Plan / Company</th>
                    <th>Destination</th>
                    <th>Contact</th>
                    <th>Travel</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                <tr>
                    <td><code>{{ $req->reference_number }}</code></td>
                    <td>
                        <div class="fw-semibold">{{ $req->travelInsurance?->name ?? '—' }}</div>
                        @if($req->travelInsurance?->insurance_company)<small class="text-muted">{{ $req->travelInsurance->insurance_company }}</small>@endif
                    </td>
                    <td>{{ $req->destination_country ?: $req->destination ?: '—' }}</td>
                    <td class="small">{{ $req->contact_email }}</td>
                    <td class="small">{{ $req->travel_start->format(config('date.display')) }} – {{ $req->travel_end->format(config('date.display')) }}</td>
                    <td class="small">{{ $req->created_at->format(config('date.display')) }}</td>
                    <td><x-insurance-booking-status :status="$req->status" /></td>
                    <td class="text-end">
                        <a href="{{ route('admin.insurance-requests.show', $req) }}" class="btn btn-sm btn-admin-primary">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-5 text-muted">No insurance quote requests yet.</td></tr>
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
