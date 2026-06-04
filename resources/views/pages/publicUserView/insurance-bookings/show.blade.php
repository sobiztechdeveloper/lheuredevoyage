@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')
<div class="col-lg-9">
    <div class="user-profile-card mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
            <div>
                <h4 class="user-profile-card-title mb-1">{{ $booking->reference_number }}</h4>
                <p class="text-muted mb-0">{{ $booking->travelInsurance?->name }} · <x-insurance-booking-status :status="$booking->status" /></p>
            </div>
            <a href="{{ route('my-insurance-requests.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6"><small class="text-muted">Destination</small><div>{{ $booking->destination_country ?: $booking->destination ?: '—' }}</div></div>
            <div class="col-md-6"><small class="text-muted">Purpose</small><div>{{ $booking->purposeLabel() }}</div></div>
            <div class="col-md-6"><small class="text-muted">Travel dates</small><div>{{ $booking->travel_start->format('M d, Y') }} – {{ $booking->travel_end->format('M d, Y') }}</div></div>
            <div class="col-md-6"><small class="text-muted">Estimated premium</small><div>{{ strtoupper($booking->currency) }} {{ number_format((float) $booking->estimated_amount, 2) }}</div></div>
        </div>

        @if($booking->quotes->isNotEmpty())
        <h5 class="mt-4">Quotes</h5>
        <ul class="list-unstyled mb-4">
            @foreach($booking->quotes as $quote)
                <li class="mb-2">
                    <a href="{{ route('my-quotes.show', $quote) }}">{{ $quote->quote_number }}</a>
                    — {{ ucfirst($quote->status) }}
                    @if($quote->status === 'sent' && $quote->expires_at)
                        <span class="text-muted small">(expires {{ $quote->expires_at->format('M d, Y') }})</span>
                    @endif
                </li>
            @endforeach
        </ul>
        @endif

        <h5>Documents</h5>
        <ul class="list-unstyled mb-4">
            @if($booking->policy_path)<li><a href="{{ route('booking-files.insurance.document', [$booking, 'policy']) }}" target="_blank">Policy (PDF)</a></li>@endif
            @if($booking->coverage_document_path)<li><a href="{{ route('booking-files.insurance.document', [$booking, 'coverage']) }}" target="_blank">Coverage certificate (PDF)</a></li>@endif
            @if($booking->invoice_path)<li><a href="{{ route('booking-files.insurance.document', [$booking, 'invoice']) }}" target="_blank">Invoice (PDF)</a></li>@endif
            @if($booking->claim_instructions_path)<li><a href="{{ route('booking-files.insurance.document', [$booking, 'claim_instructions']) }}" target="_blank">Claim instructions (PDF)</a></li>@endif
            @foreach($booking->documents as $doc)
                <li>
                    <a href="{{ route('booking-files.insurance.uploaded', [$booking, $doc]) }}" target="_blank">{{ $doc->original_name }}</a>
                    <span class="text-muted small">({{ $doc->document_type }})</span>
                </li>
            @endforeach
            @if(!$booking->policy_path && !$booking->invoice_path && !$booking->coverage_document_path && !$booking->claim_instructions_path && $booking->documents->isEmpty())
                <li class="text-muted">No documents available yet.</li>
            @endif
        </ul>

        <h5>Status timeline</h5>
        <ul class="list-unstyled mb-4">
            @forelse($booking->statusHistories as $history)
                <li class="mb-2 small">
                    <span class="text-muted">{{ $history->created_at->format('M d, Y H:i') }}</span>
                    — <x-insurance-booking-status :status="$history->status" />
                    @if($history->note)<span class="text-muted">({{ $history->note }})</span>@endif
                </li>
            @empty
                <li class="text-muted">Submitted {{ $booking->created_at->format('M d, Y') }}</li>
            @endforelse
        </ul>

        <h5>Travelers</h5>
        <ul class="mb-0">
            @forelse($booking->travelers as $traveler)
                <li>
                    {{ $traveler->fullName() }}
                    @if($traveler->is_primary)<span class="badge bg-light text-dark">Primary</span>@endif
                    @if($traveler->relationship)<span class="text-muted small">({{ $traveler->relationship }})</span>@endif
                </li>
            @empty
                <li class="text-muted">No traveler records available.</li>
            @endforelse
        </ul>

        @if($booking->agent_notes)
            <h5 class="mt-4">Agent notes</h5>
            <p class="mb-0">{{ $booking->agent_notes }}</p>
        @endif
    </div>
</div>
@endsection
