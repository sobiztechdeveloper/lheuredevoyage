@extends('layouts.admin.app')
@section('title', 'Car Request '.$bookingRequest->reference_number)
@section('content')
@php $vehicleSnap = $bookingRequest->selected_vehicle ?? []; @endphp
<div class="admin-page-header mb-4">
    <div>
        <h1>{{ $bookingRequest->reference_number }}</h1>
        <p class="text-muted small mb-0">{{ $bookingRequest->contact_email }} · <x-car-booking-status :status="$bookingRequest->status" /></p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        @if($bookingRequest->customer_id)
            <form method="POST" action="{{ route('admin.car-requests.generate-quote', $bookingRequest) }}" class="d-inline">@csrf
                <button type="submit" class="btn btn-admin-primary btn-sm"><i class="far fa-file-invoice-dollar me-1"></i> Generate Quote</button>
            </form>
        @else
            <span class="btn btn-admin-outline btn-sm disabled" title="Link a registered customer before generating a quote">Generate Quote</span>
        @endif
        <a href="{{ route('admin.car-requests.index') }}" class="btn btn-admin-outline btn-sm">Back to list</a>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Booking Summary</h5>
            <div class="row g-3">
                <div class="col-md-6"><span class="text-muted small d-block">Reference</span><strong>{{ $bookingRequest->reference_number }}</strong></div>
                <div class="col-md-6"><span class="text-muted small d-block">Status</span><x-car-booking-status :status="$bookingRequest->status" /></div>
                <div class="col-md-6"><span class="text-muted small d-block">Pickup</span>{{ $bookingRequest->pickup_date->format(config('date.display')) }} {{ $bookingRequest->pickup_time ?: '' }}</div>
                <div class="col-md-6"><span class="text-muted small d-block">Return</span>{{ $bookingRequest->return_date->format(config('date.display')) }} {{ $bookingRequest->return_time ?: '' }}</div>
                <div class="col-md-6"><span class="text-muted small d-block">Drivers</span>{{ $bookingRequest->drivers->count() }}</div>
                <div class="col-md-6"><span class="text-muted small d-block">Estimated</span>{{ strtoupper($bookingRequest->currency) }} {{ number_format((float) $bookingRequest->estimated_amount, 2) }}</div>
            </div>
        </div>
        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Vehicle & Route</h5>
            <div class="row g-3">
                <div class="col-md-6"><span class="text-muted small d-block">Vehicle</span>{{ $vehicleSnap['name'] ?? $bookingRequest->rentalCar?->name }}</div>
                <div class="col-md-6"><span class="text-muted small d-block">Location</span>{{ $vehicleSnap['location'] ?? $bookingRequest->rentalCar?->location ?? '—' }}</div>
                <div class="col-md-6"><span class="text-muted small d-block">Pickup location</span>{{ $bookingRequest->pickup_location }}</div>
                <div class="col-md-6"><span class="text-muted small d-block">Dropoff location</span>{{ $bookingRequest->dropoff_location ?: $bookingRequest->pickup_location }}</div>
            </div>
        </div>
        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Extras & Contact</h5>
            <p class="mb-1"><strong>GPS:</strong> {{ $bookingRequest->extra_gps ? 'Yes' : 'No' }}</p>
            <p class="mb-1"><strong>Child Seat:</strong> {{ $bookingRequest->extra_child_seat ? 'Yes' : 'No' }}</p>
            <p class="mb-1"><strong>Additional Driver:</strong> {{ $bookingRequest->extra_additional_driver ? 'Yes' : 'No' }}</p>
            <p class="mb-1"><strong>Insurance Option:</strong> {{ $bookingRequest->insurance_option ?: '—' }}</p>
            <p class="mb-1"><strong>Email:</strong> {{ $bookingRequest->contact_email }}</p>
            <p class="mb-1"><strong>Phone:</strong> {{ $bookingRequest->contact_phone }}</p>
            @if($bookingRequest->contact_whatsapp)<p class="mb-1"><strong>WhatsApp:</strong> {{ $bookingRequest->contact_whatsapp }}</p>@endif
            @if($bookingRequest->address)<p class="mb-1"><strong>Address:</strong> {{ $bookingRequest->address }}</p>@endif
            @if($bookingRequest->notes)<p class="mb-0"><strong>Notes:</strong> {{ $bookingRequest->notes }}</p>@endif
        </div>
        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Drivers</h5>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Name</th><th>DOB</th><th>License</th><th>Passport</th><th>Documents</th></tr></thead>
                    <tbody>
                    @forelse($bookingRequest->drivers as $driver)
                        <tr>
                            <td>{{ $driver->fullName() }}</td>
                            <td>{{ $driver->date_of_birth?->format(config('date.display')) ?? '—' }}</td>
                            <td>{{ $driver->license_number ?: '—' }}</td>
                            <td>{{ $driver->passport_number ?: '—' }}</td>
                            <td>
                                @if($driver->license_file)<a href="{{ route('admin.booking-files.car.driver', [$bookingRequest, $driver, 'license']) }}" target="_blank">License</a>@endif
                                @if($driver->license_file && $driver->passport_file) · @endif
                                @if($driver->passport_file)<a href="{{ route('admin.booking-files.car.driver', [$bookingRequest, $driver, 'passport']) }}" target="_blank">Passport</a>@endif
                                @if(! $driver->license_file && ! $driver->passport_file) — @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-muted text-center">No driver records.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        @if($bookingRequest->quotes->isNotEmpty())
        <div class="admin-panel-card mb-4">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Related Quotes</h5>
            @foreach($bookingRequest->quotes as $q)
                <p class="mb-1"><a href="{{ route('admin.quotes.show', $q) }}">{{ $q->quote_number }}</a></p>
            @endforeach
        </div>
        @endif
        <div class="admin-panel-card sticky-top" style="top:1rem">
            <h5 class="fw-bold mb-3" style="color:var(--admin-primary)">Manage Request</h5>
            <form method="POST" action="{{ route('admin.car-requests.update', $bookingRequest) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" @selected($bookingRequest->status === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3"><label class="form-label">Status note</label><input type="text" name="status_note" class="form-control"></div>
                <div class="mb-3"><label class="form-label">Agent notes</label><textarea name="agent_notes" class="form-control" rows="3">{{ old('agent_notes', $bookingRequest->agent_notes) }}</textarea></div>
                <div class="mb-3">
                    <label class="form-label">Voucher PDF</label>
                    @if($bookingRequest->voucher_path)<p class="small"><a href="{{ route('admin.booking-files.car.document', [$bookingRequest, 'voucher']) }}" target="_blank">Current voucher</a></p>@endif
                    <input type="file" name="voucher" class="form-control" accept="application/pdf">
                </div>
                <div class="mb-3">
                    <label class="form-label">Invoice PDF</label>
                    @if($bookingRequest->invoice_path)<p class="small"><a href="{{ route('admin.booking-files.car.document', [$bookingRequest, 'invoice']) }}" target="_blank">Current invoice</a></p>@endif
                    <input type="file" name="invoice" class="form-control" accept="application/pdf">
                </div>
                <div class="mb-3">
                    <label class="form-label">Rental Agreement PDF</label>
                    @if($bookingRequest->rental_agreement_path)<p class="small"><a href="{{ route('admin.booking-files.car.document', [$bookingRequest, 'rental_agreement']) }}" target="_blank">Current agreement</a></p>@endif
                    <input type="file" name="rental_agreement" class="form-control" accept="application/pdf">
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
