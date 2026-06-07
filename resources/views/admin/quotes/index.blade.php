@extends('layouts.admin.app')

@section('title', 'Quotes')

@section('content')
<div class="admin-page-header">
    <div>
        <h1>Quotes</h1>
        <p class="text-muted small mb-0">Travel consultant quotations — manual processing, no payment gateway</p>
    </div>
    <a href="{{ route('admin.quotes.create') }}" class="btn btn-admin-primary btn-sm"><i class="far fa-plus me-1"></i> Create Quote</a>
</div>

<div class="admin-table-card">
    <div class="admin-table-toolbar">
        <form method="GET" class="d-flex flex-wrap gap-2 align-items-end flex-grow-1">
            <div class="search-wrap mb-0 flex-grow-1" style="min-width:200px">
                <i class="far fa-search"></i>
                <input type="search" name="q" class="form-control" placeholder="Quote number, customer..." value="{{ $search }}">
            </div>
            <select name="status" class="form-select form-select-sm" style="width:auto">
                <option value="">All statuses</option>
                @foreach($statuses as $s)
                    <option value="{{ $s }}" @selected($filterStatus === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <select name="quote_type" class="form-select form-select-sm" style="width:auto">
                <option value="">All types</option>
                @foreach($types as $t)
                    <option value="{{ $t }}" @selected($filterType === $t)>{{ ucfirst($t === 'package' ? 'Tour Package' : ($t === 'car' ? 'Rental Car' : $t)) }}</option>
                @endforeach
            </select>
            <input type="date" name="from" class="form-control form-control-sm" style="width:auto" value="{{ $from }}">
            <input type="date" name="to" class="form-control form-control-sm" style="width:auto" value="{{ $to }}">
            <button type="submit" class="btn btn-admin-primary btn-sm">Filter</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Quote Number</th>
                    <th>Customer</th>
                    <th>Type</th>
                    <th>Total</th>
                    <th>Valid Until</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quotes as $quote)
                <tr>
                    <td><code>{{ $quote->quote_number }}</code></td>
                    <td>
                        @if($quote->customer)
                            {{ $quote->customer->name }}<br><small class="text-muted">{{ $quote->customer->email }}</small>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>{{ $quote->typeLabel() }}</td>
                    <td class="fw-semibold">{{ strtoupper($quote->currency) }} {{ number_format($quote->total_amount, 2) }}</td>
                    <td>{{ $quote->valid_until->format(config('date.display')) }}</td>
                    <td><x-quote-status :status="$quote->status" /></td>
                    <td><small class="text-muted">{{ $quote->created_at->format(config('date.display')) }}</small></td>
                    <td class="text-end">
                        <a href="{{ route('admin.quotes.show', $quote) }}" class="btn btn-sm btn-admin-primary">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-5 text-muted">No quotes yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($quotes->hasPages())<div class="admin-table-footer">{{ $quotes->links() }}</div>@endif
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/quote-admin.css') }}">
@endpush
