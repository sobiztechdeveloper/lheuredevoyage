@extends('layouts.admin.app')
@section('title', 'Holiday Package Requests')
@section('content')
<div class="admin-page-header">
    <div>
        <h1>Holiday Package Requests</h1>
        <p class="text-muted small mb-0">Custom holiday enquiries submitted from /holidaypackages</p>
    </div>
</div>
<div class="admin-table-card">
    <div class="admin-table-toolbar">
        <form method="GET" class="d-flex flex-wrap gap-2 align-items-end flex-grow-1">
            <div class="search-wrap mb-0 flex-grow-1" style="min-width:200px">
                <i class="far fa-search"></i>
                <input type="search" name="q" class="form-control" placeholder="Reference, name, email, destination..." value="{{ $search }}">
            </div>
            <select name="status" class="form-select form-select-sm" style="width:auto">
                <option value="">All statuses</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected($filterStatus === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-admin-primary btn-sm">Filter</button>
            @if($search || $filterStatus)
                <a href="{{ route('admin.holiday-package-requests.index') }}" class="btn btn-admin-outline btn-sm">Clear</a>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Contact</th>
                    <th>Destination</th>
                    <th>Travel Dates</th>
                    <th>Travelers</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $item)
                <tr>
                    <td><code>{{ $item->reference_number }}</code></td>
                    <td>
                        {{ $item->full_name }}<br>
                        <small class="text-muted">{{ $item->email }}</small>
                    </td>
                    <td>{{ $item->destination }}</td>
                    <td>
                        <small>
                            {{ $item->earliest_departure_date?->format(config('date.display')) ?: '—' }}
                            @if($item->latest_return_date)
                                <br>→ {{ $item->latest_return_date->format(config('date.display')) }}
                            @endif
                        </small>
                    </td>
                    <td>
                        {{ $item->adults }}A
                        @if($item->children > 0)
                            / {{ $item->children }}C
                        @endif
                    </td>
                    <td><span class="badge bg-secondary text-uppercase">{{ $item->status }}</span></td>
                    <td><small class="text-muted">{{ $item->created_at->format(config('date.display')) }}</small></td>
                    <td class="text-end">
                        <a href="{{ route('admin.holiday-package-requests.show', $item) }}" class="btn btn-sm btn-admin-primary">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-5 text-muted">No holiday package requests yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($requests->hasPages())
        <div class="admin-table-footer">{{ $requests->links() }}</div>
    @endif
</div>
@endsection
