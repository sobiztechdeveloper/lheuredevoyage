@extends('layouts.admin.app')

@section('title', ($item->exists ? 'Edit' : 'Create').' '.$label)

@section('content')
<form method="POST" enctype="multipart/form-data" class="admin-catalog-form"
    action="{{ $item->exists ? route('admin.'.$resource.'.update', $item->getRouteKey()) : route('admin.'.$resource.'.store') }}">
    @csrf
    @if($item->exists) @method('PUT') @endif

    <div class="admin-form-card">
        <div class="admin-form-card-header">
            <h2><i class="far fa-circle-info"></i> {{ $label }} Information</h2>
        </div>
        <div class="admin-form-card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="admin-field-label">Name <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $item->name) }}" required>
                    @error('name')<div class="admin-field-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="admin-field-label">Slug</label>
                    <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $item->slug) }}" placeholder="auto-generated if empty">
                    @error('slug')<div class="admin-field-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="admin-field-label">Short Description</label>
                    <textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $item->short_description) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="admin-field-label">Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $item->description) }}</textarea>
                </div>
                @if(in_array('location', $extraFields ?? []) || $item->getTable() === 'hotels')
                <div class="col-md-6">
                    <label class="admin-field-label">Location</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $item->location) }}">
                </div>
                @endif
                @if(in_array('destination', $extraFields ?? []))
                <div class="col-md-6">
                    <label class="admin-field-label">Destination</label>
                    <input type="text" name="destination" class="form-control" value="{{ old('destination', $item->destination) }}">
                </div>
                @endif
                @if(in_array('departure_port', $extraFields ?? []))
                <div class="col-md-6">
                    <label class="admin-field-label">Departure Port</label>
                    <input type="text" name="departure_port" class="form-control" value="{{ old('departure_port', $item->departure_port) }}">
                </div>
                @endif
                @if(in_array('airline', $extraFields ?? []))
                <div class="col-md-6">
                    <label class="admin-field-label">Airline</label>
                    <input type="text" name="airline" class="form-control" value="{{ old('airline', $item->airline) }}" placeholder="Air France">
                </div>
                @endif
                @if(in_array('flight_number', $extraFields ?? []))
                <div class="col-md-6">
                    <label class="admin-field-label">Flight Number</label>
                    <input type="text" name="flight_number" class="form-control" value="{{ old('flight_number', $item->flight_number) }}" placeholder="AF123">
                </div>
                @endif
                @if(in_array('departure_city', $extraFields ?? []))
                <div class="col-md-6">
                    <label class="admin-field-label">Departure City</label>
                    <input type="text" name="departure_city" class="form-control" value="{{ old('departure_city', $item->departure_city) }}">
                </div>
                @endif
                @if(in_array('arrival_city', $extraFields ?? []))
                <div class="col-md-6">
                    <label class="admin-field-label">Arrival City</label>
                    <input type="text" name="arrival_city" class="form-control" value="{{ old('arrival_city', $item->arrival_city) }}">
                </div>
                @endif
                @if(in_array('flight_class', $extraFields ?? []))
                <div class="col-md-4">
                    <label class="admin-field-label">Cabin Class</label>
                    <select name="flight_class" class="form-select">
                        @foreach(['Economy', 'Premium Economy', 'Business', 'First Class'] as $classOption)
                            <option value="{{ $classOption }}" @selected(old('flight_class', $item->flight_class) === $classOption)>{{ $classOption }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                @if(in_array('stops', $extraFields ?? []))
                <div class="col-md-4">
                    <label class="admin-field-label">Stops</label>
                    <input type="number" name="stops" class="form-control" min="0" max="9" value="{{ old('stops', $item->stops ?? 0) }}">
                </div>
                @endif
                @if(in_array('refundable_type', $extraFields ?? []))
                <div class="col-md-4">
                    <label class="admin-field-label">Refundable</label>
                    <select name="refundable_type" class="form-select">
                        <option value="refundable" @selected(old('refundable_type', $item->refundable_type) === 'refundable')>Refundable</option>
                        <option value="non_refundable" @selected(old('refundable_type', $item->refundable_type) === 'non_refundable')>Non Refundable</option>
                        <option value="as_per_rules" @selected(old('refundable_type', $item->refundable_type ?? 'as_per_rules') === 'as_per_rules')>As Per Rules</option>
                    </select>
                </div>
                @endif
                @if(in_array('baggage_kg', $extraFields ?? []))
                <div class="col-md-4">
                    <label class="admin-field-label">Baggage (kg)</label>
                    <input type="number" name="baggage_kg" class="form-control" min="0" max="100" value="{{ old('baggage_kg', $item->baggage_kg ?? 23) }}">
                </div>
                @endif
                @if(in_array('departure_time', $extraFields ?? []))
                <div class="col-md-4">
                    <label class="admin-field-label">Departure Time</label>
                    <input type="time" name="departure_time" class="form-control" value="{{ old('departure_time', $item->departure_time ? \Illuminate\Support\Str::of($item->departure_time)->substr(0, 5) : '') }}">
                </div>
                @endif
                @if(in_array('arrival_time', $extraFields ?? []))
                <div class="col-md-4">
                    <label class="admin-field-label">Arrival Time</label>
                    <input type="time" name="arrival_time" class="form-control" value="{{ old('arrival_time', $item->arrival_time ? \Illuminate\Support\Str::of($item->arrival_time)->substr(0, 5) : '') }}">
                </div>
                @endif
                @if(in_array('duration', $extraFields ?? []))
                <div class="col-md-6">
                    <label class="admin-field-label">Duration</label>
                    <input type="text" name="duration" class="form-control" value="{{ old('duration', $item->duration) }}" placeholder="7h 30m">
                </div>
                @endif
                @if(in_array('star_rating', $extraFields ?? []))
                <div class="col-md-4">
                    <label class="admin-field-label">Star Rating</label>
                    <input type="number" name="star_rating" class="form-control" min="1" max="5" value="{{ old('star_rating', $item->star_rating ?? $item->stars) }}">
                </div>
                @endif
                @if(in_array('vehicle_type', $extraFields ?? []))
                <div class="col-md-4">
                    <label class="admin-field-label">Vehicle Type (legacy text)</label>
                    <input type="text" name="vehicle_type" class="form-control" value="{{ old('vehicle_type', $item->vehicle_type ?? $item->car_type) }}">
                </div>
                <div class="col-md-4">
                    <label class="admin-field-label">Passenger Capacity</label>
                    <input type="number" name="passenger_capacity" class="form-control" value="{{ old('passenger_capacity', $item->passenger_capacity ?? $item->seats) }}">
                </div>
                @endif
                @if(in_array('coverage', $extraFields ?? []))
                <div class="col-md-6">
                    <label class="admin-field-label">Coverage (legacy text)</label>
                    <input type="text" name="coverage" class="form-control" value="{{ old('coverage', $item->coverage ?? $item->coverage_type) }}">
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="admin-form-card">
        <div class="admin-form-card-header">
            <h2><i class="far fa-tag"></i> Pricing</h2>
        </div>
        <div class="admin-form-card-body">
            <div class="row g-3">
                @if(in_array('price_per_day', $extraFields ?? []))
                <div class="col-md-4">
                    <label class="admin-field-label">Price Per Day <span class="required">*</span></label>
                    <input type="number" step="0.01" name="price_per_day" class="form-control @error('price_per_day') is-invalid @enderror" value="{{ old('price_per_day', $item->price_per_day ?? $item->price) }}" required>
                    @error('price_per_day')<div class="admin-field-error">{{ $message }}</div>@enderror
                </div>
                @else
                <div class="col-md-4">
                    <label class="admin-field-label">Price <span class="required">*</span></label>
                    <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $item->price) }}" required>
                    @error('price')<div class="admin-field-error">{{ $message }}</div>@enderror
                </div>
                @endif
                <div class="col-md-4">
                    <label class="admin-field-label">Price Unit</label>
                    <input type="text" name="price_unit" class="form-control" value="{{ old('price_unit', $item->price_unit) }}" placeholder="per night, per person">
                </div>
                <div class="col-md-4">
                    <label class="admin-field-label">Rating</label>
                    <input type="number" step="0.1" name="rating" class="form-control" min="0" max="5" value="{{ old('rating', $item->rating) }}">
                </div>
                <div class="col-md-4">
                    <label class="admin-field-label">Review Count</label>
                    <input type="number" name="review_count" class="form-control" min="0" value="{{ old('review_count', $item->review_count) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="admin-form-card">
        <div class="admin-form-card-header">
            <h2><i class="far fa-image"></i> Media</h2>
        </div>
        <div class="admin-form-card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="admin-field-label">Featured Image</label>
                    <div class="admin-upload-zone" data-featured-upload>
                        <input type="file" name="featured_image" class="d-none" accept="image/*">
                        <i class="far fa-cloud-arrow-up fa-2x text-muted mb-2"></i>
                        <p class="small text-muted mb-0">Click to upload or drag image here</p>
                        <div class="admin-upload-preview {{ $item->image_url ? '' : 'd-none' }}" data-preview>
                            <img src="{{ $item->image_url ?? '' }}" alt="Preview">
                            <button type="button" class="admin-upload-remove" data-remove-preview aria-label="Remove"><i class="far fa-xmark"></i></button>
                        </div>
                    </div>
                    <label class="admin-field-label mt-3">Or theme asset path</label>
                    <input type="text" name="featured_image_path" class="form-control" placeholder="assets/img/hotel/01.jpg" value="{{ old('featured_image_path', str_starts_with($item->featured_image ?? $item->image ?? '', 'assets/') ? ($item->featured_image ?? $item->image) : '') }}">
                </div>
                <div class="col-md-6">
                    <label class="admin-field-label">Gallery Images</label>
                    <div class="admin-upload-zone" data-gallery-upload>
                        <input type="file" name="gallery_images[]" class="d-none" accept="image/*" multiple>
                        <i class="far fa-images fa-2x text-muted mb-2"></i>
                        <p class="small text-muted mb-0">Select multiple images</p>
                        <div class="admin-gallery-grid" data-gallery-grid>
                            @foreach($item->gallery_urls ?? [] as $src)
                                <div class="admin-gallery-item"><img src="{{ $src }}" alt=""></div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.catalog.master-data-fields')

    <div class="admin-form-card">
        <div class="admin-form-card-header">
            <h2><i class="far fa-toggle-on"></i> Publishing</h2>
        </div>
        <div class="admin-form-card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="admin-switch">
                        <input type="checkbox" name="featured" value="1" class="form-check-input" id="featured" @checked(old('featured', $item->is_featured))>
                        <label class="form-check-label fw-semibold" for="featured">Featured on homepage</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="admin-switch">
                        <input type="checkbox" name="status" value="1" class="form-check-input" id="status" @checked(old('status', $item->is_active ?? true))>
                        <label class="form-check-label fw-semibold" for="status">Active (published)</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.form-sticky-actions', ['cancelUrl' => route('admin.'.$resource.'.index')])
</form>
@endsection
