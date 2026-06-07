@extends('layouts.admin.app')
@section('title', 'Insurance Request '.$bookingRequest->reference_number)
@section('content')
@php
    $plan = $bookingRequest->travelInsurance;
    $policySnap = $bookingRequest->selected_policy ?? [];
@endphp
<div class="admin-page-header mb-4">
    <div>
        <h1>{{ $bookingRequest->reference_number }}</h1>
        <p class="text-muted small mb-0">{{ $bookingRequest->contact_email }} · <x-insurance-booking-status :status="$bookingRequest->status" /></p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        @if($bookingRequest->customer_id)
            <form method="POST" action="{{ route('admin.insurance-requests.generate-quote', $bookingRequest) }}" class="d-inline">@csrf
                <button type="submit" class="btn btn-admin-primary btn-sm"><i class="far fa-file-invoice-dollar me-1"></i> Generate Quote</button>
            </form>
        @else
            <span class="btn btn-admin-outline btn-sm disabled" title="Link a registered customer before generating a quote">Generate Quote</span>
        @endif
        <a href="{{ route('admin.insurance-requests.index') }}" class="btn btn-admin-outline btn-sm">Back to list</a>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Request summary</h5>
            <div class="row g-3">
                <div class="col-md-6"><span class="text-muted small d-block">Reference</span><strong>{{ $bookingRequest->reference_number }}</strong></div>
                <div class="col-md-6"><span class="text-muted small d-block">Status</span><x-insurance-booking-status :status="$bookingRequest->status" /></div>
                <div class="col-md-6"><span class="text-muted small d-block">Destination</span>{{ $bookingRequest->destination_country ?: $bookingRequest->destination ?: '—' }}</div>
                <div class="col-md-6"><span class="text-muted small d-block">Purpose</span>{{ $bookingRequest->purposeLabel() }}</div>
                <div class="col-md-6"><span class="text-muted small d-block">Travel dates</span>{{ $bookingRequest->travel_start->format(config('date.display')) }} – {{ $bookingRequest->travel_end->format(config('date.display')) }}</div>
                <div class="col-md-6"><span class="text-muted small d-block">Submitted</span>{{ $bookingRequest->created_at->format(config('date.display_datetime')) }}</div>
                <div class="col-md-6"><span class="text-muted small d-block">Estimated premium</span>{{ strtoupper($bookingRequest->currency) }} {{ number_format((float) $bookingRequest->estimated_amount, 2) }}</div>
            </div>
        </div>

        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Selected plan</h5>
            <div class="row g-3">
                <div class="col-md-6"><span class="text-muted small d-block">Company</span>{{ $plan?->insurance_company ?: ($policySnap['company'] ?? '—') }}</div>
                <div class="col-md-6"><span class="text-muted small d-block">Plan</span>{{ $policySnap['name'] ?? $plan?->name ?? '—' }}</div>
                @if($plan?->medical_coverage_amount)
                <div class="col-md-6"><span class="text-muted small d-block">Medical coverage</span>{{ $plan->coverage_currency }} {{ number_format((float) $plan->medical_coverage_amount, 0) }}</div>
                @endif
            </div>
            @if($plan && $plan->benefits->isNotEmpty())
            <ul class="mt-3 mb-0 small">
                @foreach($plan->benefits->take(6) as $benefit)
                    <li><strong>{{ $benefit->title }}</strong>@if($benefit->description) — {{ $benefit->description }}@endif</li>
                @endforeach
            </ul>
            @endif
        </div>

        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Primary traveler & contact</h5>
            @php $primary = $bookingRequest->primaryTraveler(); @endphp
            @if($primary)
            <p class="mb-1"><strong>{{ $primary->fullName() }}</strong> · DOB {{ $primary->date_of_birth?->format(config('date.display')) ?? '—' }} · {{ $primary->nationality ?: '—' }}</p>
            <p class="mb-1 small">Passport: {{ $primary->passport_number ?: '—' }} @if($primary->passport_expiry)(exp. {{ $primary->passport_expiry->format(config('date.display_month_year')) }})@endif</p>
            @endif
            <p class="mb-1"><strong>Email:</strong> {{ $bookingRequest->contact_email }}</p>
            <p class="mb-1"><strong>Phone:</strong> {{ $bookingRequest->contact_phone }}</p>
            @if($bookingRequest->address)<p class="mb-0"><strong>Address:</strong> {{ $bookingRequest->address }}, {{ $bookingRequest->city }} {{ $bookingRequest->country }}</p>@endif
        </div>

        @if($bookingRequest->additionalTravelers()->isNotEmpty())
        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Additional travelers</h5>
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-sm">
                    <thead><tr><th>Name</th><th>DOB</th><th>Nationality</th><th>Relationship</th><th>Passport</th></tr></thead>
                    <tbody>
                    @foreach($bookingRequest->additionalTravelers() as $traveler)
                        <tr>
                            <td>{{ $traveler->fullName() }}</td>
                            <td>{{ $traveler->date_of_birth?->format(config('date.display')) ?? '—' }}</td>
                            <td>{{ $traveler->nationality ?: '—' }}</td>
                            <td>{{ $traveler->relationship ?: '—' }}</td>
                            <td>{{ $traveler->passport_number ?: '—' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Risk assessment</h5>
            <div class="row g-2 small">
                <div class="col-md-6">Pre-existing conditions: <strong>{{ $bookingRequest->pre_existing_conditions ? 'Yes' : 'No' }}</strong></div>
                <div class="col-md-6">Pregnancy: <strong>{{ $bookingRequest->pregnancy ? 'Yes' : 'No' }}</strong></div>
                <div class="col-md-6">Adventure sports: <strong>{{ $bookingRequest->adventure_sports ? 'Yes' : 'No' }}</strong></div>
                <div class="col-md-6">Winter sports: <strong>{{ $bookingRequest->winter_sports ? 'Yes' : 'No' }}</strong></div>
                <div class="col-md-6">Long stay: <strong>{{ $bookingRequest->long_stay ? 'Yes' : 'No' }}</strong></div>
            </div>
            @if($bookingRequest->medical_notes)<p class="mb-1 mt-2"><strong>Medical notes:</strong> {{ $bookingRequest->medical_notes }}</p>@endif
            @if($bookingRequest->additional_notes)<p class="mb-0"><strong>Additional notes:</strong> {{ $bookingRequest->additional_notes }}</p>@endif
        </div>

        @if($bookingRequest->documents->isNotEmpty())
        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Customer uploaded documents</h5>
            <ul class="list-unstyled mb-0">
                @foreach($bookingRequest->documents as $doc)
                    <li class="mb-1">
                        <span class="text-muted">{{ $doc->document_type }}:</span>
                        {{ $doc->original_name }}
                        <a href="{{ route('admin.booking-files.insurance.uploaded', [$bookingRequest, $doc]) }}" class="ms-1" target="_blank">Download</a>
                    </li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Status timeline</h5>
            @forelse($bookingRequest->statusHistories as $history)
                <div class="d-flex gap-2 mb-2 small">
                    <span class="text-muted" style="min-width:120px">{{ $history->created_at->format(config('date.display_datetime')) }}</span>
                    <span><x-insurance-booking-status :status="$history->status" /></span>
                    @if($history->note)<span class="text-muted">— {{ $history->note }}</span>@endif
                    @if($history->changedBy)<span class="text-muted">({{ $history->changedBy->name }})</span>@endif
                </div>
            @empty
                <p class="text-muted mb-0">No status history recorded.</p>
            @endforelse
        </div>
    </div>

    <div class="col-lg-4">
        @if($bookingRequest->quotes->isNotEmpty())
        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Related quotes</h5>
            @foreach($bookingRequest->quotes as $q)
                <p class="mb-1"><a href="{{ route('admin.quotes.show', $q) }}">{{ $q->quote_number }}</a> — {{ ucfirst($q->status) }}</p>
            @endforeach
        </div>
        @endif

        <div class="admin-panel-card sticky-top" style="top:1rem">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Manage request</h5>
            <form method="POST" action="{{ route('admin.insurance-requests.update', $bookingRequest) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" @selected($bookingRequest->status === $status)>{{ config('insurance.request_statuses.'.$status, ucfirst(str_replace('_', ' ', $status))) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3"><label class="form-label">Status note</label><input type="text" name="status_note" class="form-control" placeholder="Visible in timeline"></div>
                <div class="mb-3"><label class="form-label">Internal notes</label><textarea name="agent_notes" class="form-control" rows="4">{{ old('agent_notes', $bookingRequest->agent_notes) }}</textarea></div>
                <hr>
                <p class="small fw-semibold mb-2">Admin documents (private storage)</p>
                <div class="mb-3">
                    <label class="form-label">Policy PDF</label>
                    @if($bookingRequest->policy_path)<p class="small"><a href="{{ route('admin.booking-files.insurance.document', [$bookingRequest, 'policy']) }}" target="_blank">Current policy</a></p>@endif
                    <input type="file" name="policy" class="form-control" accept="application/pdf">
                </div>
                <div class="mb-3">
                    <label class="form-label">Coverage certificate</label>
                    @if($bookingRequest->coverage_document_path)<p class="small"><a href="{{ route('admin.booking-files.insurance.document', [$bookingRequest, 'coverage']) }}" target="_blank">Current certificate</a></p>@endif
                    <input type="file" name="coverage_document" class="form-control" accept="application/pdf">
                </div>
                <div class="mb-3">
                    <label class="form-label">Invoice PDF</label>
                    @if($bookingRequest->invoice_path)<p class="small"><a href="{{ route('admin.booking-files.insurance.document', [$bookingRequest, 'invoice']) }}" target="_blank">Current invoice</a></p>@endif
                    <input type="file" name="invoice" class="form-control" accept="application/pdf">
                </div>
                <div class="mb-3">
                    <label class="form-label">Claim instructions</label>
                    @if($bookingRequest->claim_instructions_path)<p class="small"><a href="{{ route('admin.booking-files.insurance.document', [$bookingRequest, 'claim_instructions']) }}" target="_blank">Current file</a></p>@endif
                    <input type="file" name="claim_instructions" class="form-control" accept="application/pdf">
                </div>
                <button type="submit" class="btn btn-admin-primary w-100">Save changes</button>
            </form>
        </div>
    </div>
</div>
@endsection
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/flight-booking-admin.css') }}">
@endpush
