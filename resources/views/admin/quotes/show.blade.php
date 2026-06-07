@extends('layouts.admin.app')

@section('title', 'Quote '.$quote->quote_number)

@section('content')
<div class="admin-page-header mb-4">
    <div>
        <h1>{{ $quote->quote_number }}</h1>
        <p class="text-muted small mb-0">{{ $quote->title }} · <x-quote-status :status="$quote->status" /></p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.quotes.pdf', $quote) }}" class="btn btn-admin-outline btn-sm" target="_blank"><i class="far fa-file-pdf me-1"></i> PDF</a>
        <a href="{{ route('admin.quotes.edit', $quote) }}" class="btn btn-admin-outline btn-sm">Edit</a>
        @if(in_array($quote->status, ['draft']))
            <form method="POST" action="{{ route('admin.quotes.send', $quote) }}" class="d-inline">@csrf<button type="submit" class="btn btn-admin-primary btn-sm">Send to Customer</button></form>
        @endif
        <a href="{{ route('admin.quotes.index') }}" class="btn btn-admin-outline btn-sm">Back</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Quote Information</h5>
            <div class="row g-3">
                <div class="col-md-6"><span class="text-muted small d-block">Type</span>{{ $quote->typeLabel() }}</div>
                <div class="col-md-6"><span class="text-muted small d-block">Valid until</span>{{ $quote->valid_until->format(config('date.display')) }}</div>
                <div class="col-12"><span class="text-muted small d-block">Title</span>{{ $quote->title }}</div>
                @if($quote->description)<div class="col-12"><span class="text-muted small d-block">Description</span><pre class="mb-0 small" style="white-space:pre-wrap;font-family:inherit">{{ $quote->description }}</pre></div>@endif
                @if($quote->flightBookingRequest)
                    <div class="col-12">
                        <a href="{{ route('admin.flight-requests.show', $quote->flightBookingRequest) }}">Flight request {{ $quote->flightBookingRequest->booking_reference }}</a>
                    </div>
                @endif
            </div>
        </div>

        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Customer</h5>
            @if($quote->customer)
                <p class="mb-0"><strong>{{ $quote->customer->name }}</strong><br>{{ $quote->customer->email }}</p>
            @else
                <p class="text-muted mb-0">No registered customer linked.</p>
            @endif
        </div>

        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Items</h5>
            <table class="table mb-0">
                <thead><tr><th>Item</th><th>Qty</th><th class="text-end">Unit</th><th class="text-end">Total</th></tr></thead>
                <tbody>
                    @foreach($quote->items as $item)
                    <tr>
                        <td>{{ $item->item_name }}@if($item->description)<br><small class="text-muted">{{ $item->description }}</small>@endif</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-end">{{ number_format($item->total_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($quote->notes)
        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Notes</h5>
            <p class="mb-0">{{ $quote->notes }}</p>
        </div>
        @endif

        <div class="admin-panel-card">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Status History</h5>
            <ul class="list-unstyled mb-0">
                @foreach($quote->statusHistories as $history)
                <li class="mb-3 pb-3 border-bottom">
                    <strong>{{ ucfirst(str_replace('_', ' ', $history->new_status)) }}</strong>
                    @if($history->old_status)<span class="text-muted small">from {{ $history->old_status }}</span>@endif
                    <span class="d-block text-muted small">{{ $history->created_at->format(config('date.display_datetime')) }}</span>
                    @if($history->changedBy)<span class="small">By {{ $history->changedBy->name }}</span>@endif
                    @if($history->notes)<span class="d-block small">{{ $history->notes }}</span>@endif
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Pricing</h5>
            <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><span>{{ strtoupper($quote->currency) }} {{ number_format($quote->amount, 2) }}</span></div>
            <div class="d-flex justify-content-between mb-2"><span>Tax</span><span>{{ number_format($quote->tax_amount ?? 0, 2) }}</span></div>
            <div class="d-flex justify-content-between mb-2"><span>Service fee</span><span>{{ number_format($quote->service_fee ?? 0, 2) }}</span></div>
            <div class="d-flex justify-content-between border-top pt-2 fw-bold"><span>Grand total</span><span style="color:var(--admin-primary)">{{ strtoupper($quote->currency) }} {{ number_format($quote->total_amount, 2) }}</span></div>
        </div>
        <div class="admin-panel-card">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Audit</h5>
            <p class="small mb-1">Created: {{ $quote->created_at->format(config('date.display_datetime')) }}</p>
            @if($quote->creator)<p class="small mb-1">By: {{ $quote->creator->name }}</p>@endif
            <p class="small mb-0">Updated: {{ $quote->updated_at->format(config('date.display_datetime')) }}</p>
            @if($quote->updater)<p class="small mb-0">By: {{ $quote->updater->name }}</p>@endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/quote-admin.css') }}">
@endpush
