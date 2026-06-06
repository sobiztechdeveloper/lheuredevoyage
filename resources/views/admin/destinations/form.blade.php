@extends('layouts.admin.app')
@section('title', $destination->exists ? 'Edit Destination' : 'Add Destination')
@section('content')
<div class="admin-page-header mb-4">
    <div>
        <h1>{{ $destination->exists ? 'Edit Destination' : 'Add Destination' }}</h1>
        <p class="text-muted small mb-0">Used across flight, hotel, cruise, car and package search</p>
    </div>
    <a href="{{ route('admin.destinations.index') }}" class="btn btn-admin-outline btn-sm">Back to list</a>
</div>

<form method="POST" action="{{ $destination->exists ? route('admin.destinations.update', $destination) : route('admin.destinations.store') }}">
    @csrf
    @if($destination->exists) @method('PUT') @endif

    <div class="admin-form-card">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Type <span class="text-danger">*</span></label>
                <select name="type" class="form-select admin-select2" required>
                    @foreach($typeOptions as $value => $label)
                        <option value="{{ $value }}" @selected(old('type', $destination->type) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $destination->name) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Code (IATA / short)</label>
                <input type="text" name="code" class="form-control text-uppercase" value="{{ old('code', $destination->code) }}" maxlength="20">
            </div>
            <div class="col-md-4">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $destination->slug) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sort Order</label>
                <input type="number" name="sort_order" class="form-control" min="0" value="{{ old('sort_order', $destination->sort_order ?? 0) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Country</label>
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
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-control" value="{{ old('city', $destination->city) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Region</label>
                <input type="text" name="region" class="form-control" value="{{ old('region', $destination->region) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Latitude</label>
                <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $destination->latitude) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Longitude</label>
                <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $destination->longitude) }}">
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $destination->description) }}</textarea>
            </div>
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="destination-active" @checked(old('is_active', $destination->is_active ?? true))>
                    <label class="form-check-label" for="destination-active">Active</label>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-sticky-actions">
        <button type="submit" class="btn btn-admin-primary">{{ $destination->exists ? 'Update Destination' : 'Create Destination' }}</button>
        <a href="{{ route('admin.destinations.index') }}" class="btn btn-admin-outline">Cancel</a>
    </div>
</form>
@endsection
