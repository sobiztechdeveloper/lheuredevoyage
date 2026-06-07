@extends('layouts.admin.app')
@section('title', $destination->exists ? 'Edit Destination' : 'Add Destination')
@section('content')
<form method="POST" action="{{ $destination->exists ? route('admin.destinations.update', $destination) : route('admin.destinations.store') }}">
    @csrf
    @if($destination->exists) @method('PUT') @endif

    <div class="admin-form-card">
        <div class="admin-form-card-header">
            <h2><i class="far fa-location-dot"></i> Destination Details</h2>
        </div>
        <div class="admin-form-card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="admin-field-label">Type <span class="required">*</span></label>
                    <select name="type" class="form-select admin-select2 @error('type') is-invalid @enderror" required>
                        @foreach($typeOptions as $value => $label)
                            <option value="{{ $value }}" @selected(old('type', $destination->type) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type')<div class="admin-field-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-8">
                    <label class="admin-field-label">Name <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $destination->name) }}" required>
                    @error('name')<div class="admin-field-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="admin-field-label">Code (IATA / short)</label>
                    <input type="text" name="code" class="form-control text-uppercase" value="{{ old('code', $destination->code) }}" maxlength="20">
                </div>
                <div class="col-md-4">
                    <label class="admin-field-label">Slug</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $destination->slug) }}">
                </div>
                <div class="col-md-4">
                    <label class="admin-field-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" min="0" value="{{ old('sort_order', $destination->sort_order ?? 0) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="admin-form-card">
        <div class="admin-form-card-header">
            <h2><i class="far fa-map"></i> Location</h2>
        </div>
        <div class="admin-form-card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="admin-field-label">Country</label>
                    <select name="country" class="form-select admin-select2" data-tags="true">
                        <option value=""></option>
                        @foreach($countryOptions as $country)
                            <option value="{{ $country }}" @selected(old('country', $destination->country) === $country)>{{ $country }}</option>
                        @endforeach
                        @if(old('country', $destination->country) && ! in_array(old('country', $destination->country), $countryOptions, true))
                            <option value="{{ old('country', $destination->country) }}" selected>{{ old('country', $destination->country) }}</option>
                        @endif
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="admin-field-label">City</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city', $destination->city) }}">
                </div>
                <div class="col-md-4">
                    <label class="admin-field-label">Region</label>
                    <input type="text" name="region" class="form-control" value="{{ old('region', $destination->region) }}">
                </div>
                <div class="col-md-6">
                    <label class="admin-field-label">Latitude</label>
                    <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $destination->latitude) }}">
                </div>
                <div class="col-md-6">
                    <label class="admin-field-label">Longitude</label>
                    <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $destination->longitude) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="admin-form-card">
        <div class="admin-form-card-header">
            <h2><i class="far fa-file-lines"></i> Additional Info</h2>
        </div>
        <div class="admin-form-card-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="admin-field-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $destination->description) }}</textarea>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="admin-switch w-100">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="destination-active" @checked(old('is_active', $destination->is_active ?? true))>
                        <label class="form-check-label fw-semibold" for="destination-active">Active</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.form-sticky-actions', ['cancelUrl' => route('admin.destinations.index')])
</form>
@endsection
