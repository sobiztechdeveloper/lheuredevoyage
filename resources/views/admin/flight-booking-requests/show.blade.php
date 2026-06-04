@extends('layouts.admin.app')

@section('title', 'Flight Request '.$bookingRequest->booking_reference)

@section('content')
@php
    $flight = $bookingRequest->selected_flight ?? [];
    $contactPassenger = $bookingRequest->passengers[$bookingRequest->contact_passenger_index] ?? null;
@endphp

<div class="admin-page-header mb-4">
    <div>
        <h1>{{ $bookingRequest->booking_reference }}</h1>
        <p class="text-muted small mb-0">
            {{ $bookingRequest->contact_name }} ·
            <x-flight-booking-status :status="$bookingRequest->status" />
        </p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        @if($bookingRequest->user_id)
            <form method="POST" action="{{ route('admin.flight-requests.generate-quote', $bookingRequest) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-admin-primary btn-sm"><i class="far fa-file-invoice-dollar me-1"></i> Generate Quote</button>
            </form>
        @else
            <span class="btn btn-admin-outline btn-sm disabled" title="Link a registered customer before generating a quote">Generate Quote</span>
        @endif
        <a href="{{ route('admin.flight-requests.index') }}" class="btn btn-admin-outline btn-sm">Back to list</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Booking Summary</h5>
            <div class="row g-3">
                <div class="col-md-6"><span class="text-muted small d-block">Reference</span><strong>{{ $bookingRequest->booking_reference }}</strong></div>
                <div class="col-md-6"><span class="text-muted small d-block">Status</span><x-flight-booking-status :status="$bookingRequest->status" /></div>
                <div class="col-md-6"><span class="text-muted small d-block">Submitted</span>{{ $bookingRequest->created_at->format('M d, Y H:i') }}</div>
                <div class="col-md-6"><span class="text-muted small d-block">Passengers</span>{{ $bookingRequest->passengerCount() }}</div>
                @if($bookingRequest->user)
                    <div class="col-12"><span class="text-muted small d-block">Registered user</span>
                        <a href="{{ route('admin.users.show', $bookingRequest->user) }}">{{ $bookingRequest->user->name }}</a>
                        <span class="text-muted">({{ $bookingRequest->user->email }})</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Flight Details</h5>
            <div class="row g-3">
                <div class="col-md-6"><span class="text-muted small d-block">Route</span>{{ $bookingRequest->routeLabel() }}</div>
                <div class="col-md-6"><span class="text-muted small d-block">Trip type</span>{{ ucfirst(str_replace('_', ' ', $bookingRequest->trip_type)) }}</div>
                @if(!empty($flight['airline']))
                    <div class="col-md-6"><span class="text-muted small d-block">Airline</span>{{ $flight['airline'] }} @if(!empty($flight['flight_number']))<span class="text-muted">· {{ $flight['flight_number'] }}</span>@endif</div>
                @endif
                <div class="col-md-6"><span class="text-muted small d-block">Departure</span>{{ $bookingRequest->departure_date->format('M d, Y') }}</div>
                <div class="col-md-6"><span class="text-muted small d-block">Return</span>{{ $bookingRequest->return_date?->format('M d, Y') ?? '—' }}</div>
                <div class="col-md-6"><span class="text-muted small d-block">Cabin</span>{{ ucfirst(str_replace('_', ' ', $bookingRequest->cabin_class)) }}</div>
                <div class="col-md-6"><span class="text-muted small d-block">Estimated fare</span>{{ strtoupper($bookingRequest->currency) }} {{ number_format($bookingRequest->estimated_price, 2) }}</div>
            </div>
        </div>

        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Passenger Details</h5>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Gender</th>
                            <th>DOB</th>
                            <th>Passport</th>
                            <th>Expiry</th>
                            <th>Nationality</th>
                            <th>Issuing country</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookingRequest->passengers as $index => $passenger)
                        <tr @if($index === $bookingRequest->contact_passenger_index) class="table-warning" @endif>
                            <td>
                                {{ $passenger->fullName() }}
                                @if($index === $bookingRequest->contact_passenger_index)
                                    <span class="badge bg-warning text-dark ms-1">Contact</span>
                                @endif
                            </td>
                            <td>{{ ucfirst($passenger->passenger_type) }}</td>
                            <td>{{ ucfirst($passenger->gender ?? '—') }}</td>
                            <td>{{ $passenger->date_of_birth->format('M d, Y') }}</td>
                            <td>{{ $passenger->passport_number }}</td>
                            <td>{{ $passenger->passport_expiry->format('M d, Y') }}</td>
                            <td>{{ $passenger->nationality }}</td>
                            <td>{{ $passenger->passport_country ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Uploaded Documents — Passport Copies</h5>
            <div class="row g-3">
                @foreach($bookingRequest->passengers as $passenger)
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100" style="border-color:var(--admin-border)!important">
                        <h6 class="mb-2">{{ $passenger->fullName() }}</h6>
                        @if($passenger->passport_file)
                            @if($passenger->isPassportImage())
                                <a href="{{ route('admin.booking-files.flight.passport', [$bookingRequest, $passenger]) }}" target="_blank" rel="noopener">
                                    <img src="{{ route('admin.booking-files.flight.passport', [$bookingRequest, $passenger]) }}" alt="Passport" class="fbr-doc-thumb img-fluid mb-2 d-block">
                                </a>
                            @else
                                <p class="mb-2"><i class="far fa-file-pdf text-danger"></i> PDF document</p>
                            @endif
                            <a href="{{ route('admin.booking-files.flight.passport', [$bookingRequest, $passenger]) }}" target="_blank" rel="noopener" class="btn btn-sm btn-admin-outline">Download / view</a>
                        @else
                            <p class="text-muted small mb-0">No passport copy uploaded.</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Contact Details</h5>
            @if($contactPassenger)
                <p class="small text-muted mb-2">Booking contact passenger: <strong>{{ $contactPassenger->fullName() }}</strong></p>
            @endif
            <p class="mb-1"><strong>{{ $bookingRequest->contact_name }}</strong></p>
            <p class="mb-1"><i class="far fa-envelope me-1"></i> {{ $bookingRequest->email }}</p>
            <p class="mb-1"><i class="far fa-phone me-1"></i> {{ $bookingRequest->phone }}</p>
            @if($bookingRequest->whatsapp)<p class="mb-1"><i class="fab fa-whatsapp me-1"></i> {{ $bookingRequest->whatsapp }}</p>@endif
            @if($bookingRequest->country)<p class="mb-0"><i class="far fa-globe me-1"></i> {{ $bookingRequest->country }}</p>@endif
        </div>

        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Travel Preferences</h5>
            <p class="mb-1"><strong>Preferred airline:</strong> {{ $bookingRequest->preferred_airline ?: '—' }}</p>
            <p class="mb-1"><strong>Seat:</strong> {{ $bookingRequest->seatPreferenceLabel() }}</p>
            <p class="mb-0"><strong>Meal:</strong> {{ $bookingRequest->mealPreferenceLabel() }}</p>
        </div>

        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Special Requests</h5>
            @php $selected = $bookingRequest->special_assistance ?? []; @endphp
            @if($selected !== [])
                <ul class="mb-3">
                    @foreach($assistanceOptions as $key => $label)
                        @if(!empty($selected[$key]))
                            <li>{{ $label }}</li>
                        @endif
                    @endforeach
                </ul>
            @else
                <p class="text-muted">No special assistance selected.</p>
            @endif
            @if($bookingRequest->special_requests)
                <p class="mb-0"><strong>Notes:</strong> {{ $bookingRequest->special_requests }}</p>
            @endif
        </div>

        <div class="admin-panel-card">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Status History</h5>
            <ul class="fbr-timeline">
                @forelse($bookingRequest->statusHistories as $history)
                <li>
                    <strong>{{ ucfirst(str_replace('_', ' ', $history->status)) }}</strong>
                    <span class="text-muted small d-block">{{ $history->created_at->format('M d, Y H:i') }}</span>
                    @if($history->changedBy)<span class="small text-muted">By {{ $history->changedBy->name }}</span><br>@endif
                    @if($history->notes)<span class="small">{{ $history->notes }}</span>@endif
                </li>
                @empty
                <li class="text-muted">No status history recorded.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="col-lg-4">
        @if($bookingRequest->quotes->isNotEmpty())
        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Related Quotes</h5>
            <ul class="list-unstyled mb-0">
                @foreach($bookingRequest->quotes as $relatedQuote)
                <li class="mb-2">
                    <a href="{{ route('admin.quotes.show', $relatedQuote) }}">{{ $relatedQuote->quote_number }}</a>
                    — <x-quote-status :status="$relatedQuote->status" />
                </li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="admin-panel-card sticky-top" style="top:1rem">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Manage Request</h5>
            <form method="POST" action="{{ route('admin.flight-requests.update', $bookingRequest) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" @selected($bookingRequest->status === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status note</label>
                    <input type="text" name="status_note" class="form-control" placeholder="Shown on timeline">
                </div>
                <div class="mb-3">
                    <label class="form-label">Agent notes</label>
                    <textarea name="agent_notes" class="form-control" rows="4">{{ old('agent_notes', $bookingRequest->agent_notes) }}</textarea>
                    <div class="form-text">Visible to customer on their booking detail when logged in.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ticket PDF</label>
                    @if($bookingRequest->ticket_path)
                        <p class="small mb-1"><a href="{{ route('admin.booking-files.flight.document', [$bookingRequest, 'ticket']) }}" target="_blank" rel="noopener">Current ticket</a></p>
                    @endif
                    <input type="file" name="ticket" class="form-control" accept="application/pdf">
                </div>
                <div class="mb-3">
                    <label class="form-label">Invoice PDF</label>
                    @if($bookingRequest->invoice_path)
                        <p class="small mb-1"><a href="{{ route('admin.booking-files.flight.document', [$bookingRequest, 'invoice']) }}" target="_blank" rel="noopener">Current invoice</a></p>
                    @endif
                    <input type="file" name="invoice" class="form-control" accept="application/pdf">
                </div>
                <button type="submit" class="btn btn-admin-primary w-100">Save changes</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/flight-booking-admin.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/quote-admin.css') }}">
@endpush
