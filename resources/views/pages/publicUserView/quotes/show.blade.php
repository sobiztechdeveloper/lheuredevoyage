@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')
<div class="col-lg-9">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="user-profile-card mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
            <div>
                <h4 class="user-profile-card-title mb-1">{{ $quote->quote_number }}</h4>
                <p class="text-muted mb-0">{{ $quote->title }}</p>
            </div>
            <span class="badge bg-primary fs-6">{{ $quote->statusLabel() }}</span>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4"><span class="text-muted small d-block">Quote date</span>{{ $quote->created_at->format('M d, Y') }}</div>
            <div class="col-md-4"><span class="text-muted small d-block">Valid until</span><strong>{{ $quote->valid_until->format('M d, Y') }}</strong></div>
            <div class="col-md-4"><span class="text-muted small d-block">Type</span>{{ $quote->typeLabel() }}</div>
        </div>

        @if($quote->description)
            <p class="small" style="white-space:pre-wrap">{{ $quote->description }}</p>
        @endif

        <h5 class="mt-4 mb-3">Items</h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr><th>Description</th><th>Qty</th><th class="text-end">Unit</th><th class="text-end">Total</th></tr>
                </thead>
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

        <div class="row justify-content-end">
            <div class="col-md-5">
                <div class="border rounded p-3">
                    <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><span>{{ strtoupper($quote->currency) }} {{ number_format($quote->amount, 2) }}</span></div>
                    <div class="d-flex justify-content-between mb-2"><span>Tax</span><span>{{ number_format($quote->tax_amount ?? 0, 2) }}</span></div>
                    <div class="d-flex justify-content-between mb-2"><span>Service fee</span><span>{{ number_format($quote->service_fee ?? 0, 2) }}</span></div>
                    <div class="d-flex justify-content-between fw-bold border-top pt-2"><span>Grand total</span><span>{{ strtoupper($quote->currency) }} {{ number_format($quote->total_amount, 2) }}</span></div>
                </div>
            </div>
        </div>

        @if($quote->notes)
            <h5 class="mt-4">Notes</h5>
            <p>{{ $quote->notes }}</p>
        @endif

        <p class="mt-3">
            <a href="{{ route('my-quotes.pdf', $quote) }}" class="theme-btn style-outline btn-sm" target="_blank">Download PDF</a>
        </p>
    </div>

    @if($quote->canBeAcceptedOrRejected())
    <div class="user-profile-card">
        <h5 class="mb-3">Your response</h5>
        <p class="text-muted small">This is a quotation only — no online payment. Accept to proceed with manual ticketing by our consultants.</p>
        <x-legal-quote-acceptance />
        <div class="row g-3">
            <div class="col-md-6">
                <form method="POST" action="{{ route('my-quotes.accept', $quote) }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label small">Comment (optional)</label>
                        <textarea name="comment" class="form-control" rows="2" placeholder="Acceptance notes"></textarea>
                    </div>
                    <button type="submit" class="theme-btn">Accept Quote</button>
                </form>
            </div>
            <div class="col-md-6">
                <form method="POST" action="{{ route('my-quotes.reject', $quote) }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label small">Reason (optional)</label>
                        <textarea name="comment" class="form-control" rows="2" placeholder="Why rejecting"></textarea>
                    </div>
                    <button type="submit" class="theme-btn style-outline">Reject Quote</button>
                </form>
            </div>
        </div>
    </div>
    @endif

    @if($quote->statusHistories->isNotEmpty())
    <div class="user-profile-card mt-4">
        <h5>Status history</h5>
        <ul class="list-unstyled mb-0">
            @foreach($quote->statusHistories as $history)
            <li class="mb-2 pb-2 border-bottom">
                <strong>{{ ucfirst(str_replace('_', ' ', $history->new_status)) }}</strong>
                <span class="text-muted small d-block">{{ $history->created_at->format('M d, Y H:i') }}</span>
                @if($history->notes)<span class="small">{{ $history->notes }}</span>@endif
            </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@endsection
