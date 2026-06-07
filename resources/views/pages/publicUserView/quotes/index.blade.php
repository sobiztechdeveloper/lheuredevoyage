@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')
<div class="col-lg-9">
    <div class="user-profile-card">
        <h4 class="user-profile-card-title">My Quotes</h4>
        <p class="text-muted">Review quotations from our travel consultants and accept or reject before they expire.</p>
        <div class="table-responsive">
            <table class="table text-nowrap">
                <thead>
                    <tr>
                        <th>Quote Number</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Valid Until</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quotes as $quote)
                    <tr>
                        <td><strong>{{ $quote->quote_number }}</strong></td>
                        <td>{{ $quote->typeLabel() }}</td>
                        <td>{{ strtoupper($quote->currency) }} {{ number_format($quote->total_amount, 2) }}</td>
                        <td><span class="badge bg-primary">{{ $quote->statusLabel() }}</span></td>
                        <td>{{ $quote->valid_until->format(config('date.display')) }}</td>
                        <td><a href="{{ route('my-quotes.show', $quote) }}">View</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-muted">No quotes yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $quotes->links() }}
    </div>
</div>
@endsection
