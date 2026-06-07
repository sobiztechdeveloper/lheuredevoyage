@extends('layouts.admin.app')
@section('title', $airline->exists ? 'Edit Airline' : 'Add Airline')
@section('content')
<form method="POST" action="{{ $airline->exists ? route('admin.airlines.update', $airline) : route('admin.airlines.store') }}" enctype="multipart/form-data">
    @csrf
    @if($airline->exists) @method('PUT') @endif

    <div class="admin-form-card">
        <div class="admin-form-card-header">
            <h2><i class="far fa-plane"></i> Airline Details</h2>
        </div>
        <div class="admin-form-card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="admin-field-label">Airline Name <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $airline->name) }}" required>
                    @error('name')<div class="admin-field-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="admin-field-label">IATA Code</label>
                    <input type="text" name="code" class="form-control text-uppercase" maxlength="8" value="{{ old('code', $airline->code) }}" placeholder="EK">
                    <p class="text-muted small mb-0 mt-1">Matches flight numbers like EK515.</p>
                </div>
                <div class="col-md-3">
                    <label class="admin-field-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" min="0" value="{{ old('sort_order', $airline->sort_order ?? 0) }}">
                </div>
                <div class="col-md-6">
                    <label class="admin-field-label">Slug</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $airline->slug) }}">
                </div>
                <div class="col-12">
                    <label class="admin-field-label">Aliases</label>
                    <textarea name="aliases" class="form-control" rows="3" placeholder="Emirates Airlines, Emirates Airline">{{ old('aliases', $airline->exists ? implode("\n", $airline->aliasList()) : '') }}</textarea>
                    <p class="text-muted small mb-0 mt-1">One alias per line or comma-separated. Helps match API airline names.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-form-card">
        <div class="admin-form-card-header">
            <h2><i class="far fa-image"></i> Branding & Status</h2>
        </div>
        <div class="admin-form-card-body">
            <div class="row g-4">
                @include('admin.partials.single-image-upload', [
                    'name' => 'logo',
                    'label' => 'Airline Logo',
                    'currentUrl' => $airline->logo_url,
                    'class' => 'col-md-6',
                ])
                <div class="col-md-6">
                    <label class="admin-field-label">Description</label>
                    <textarea name="description" class="form-control" rows="5">{{ old('description', $airline->description) }}</textarea>
                    @if($airline->logo_url)
                        <div class="admin-switch mt-3">
                            <input type="checkbox" name="remove_logo" value="1" class="form-check-input" id="remove-logo">
                            <label class="form-check-label fw-semibold" for="remove-logo">Remove current logo</label>
                        </div>
                    @endif
                    <div class="admin-switch mt-3">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="airline-active" @checked(old('is_active', $airline->is_active ?? true))>
                        <label class="form-check-label fw-semibold" for="airline-active">Active</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.form-sticky-actions', ['cancelUrl' => route('admin.airlines.index')])
</form>
@endsection
