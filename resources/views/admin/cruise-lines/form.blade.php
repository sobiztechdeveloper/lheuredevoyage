@extends('layouts.admin.app')
@section('title', $line->exists ? 'Edit Cruise Line' : 'Add Cruise Line')
@section('content')
<form method="POST" action="{{ $line->exists ? route('admin.cruise-lines.update', $line) : route('admin.cruise-lines.store') }}" enctype="multipart/form-data">
    @csrf
    @if($line->exists) @method('PUT') @endif

    <div class="admin-form-card">
        <div class="admin-form-card-header">
            <h2><i class="far fa-ship"></i> Cruise Line Details</h2>
        </div>
        <div class="admin-form-card-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="admin-field-label">Name <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $line->name) }}" required>
                    @error('name')<div class="admin-field-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="admin-field-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" min="0" value="{{ old('sort_order', $line->sort_order ?? 0) }}">
                </div>
                <div class="col-md-6">
                    <label class="admin-field-label">Slug</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $line->slug) }}">
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
                    'label' => 'Cruise Line Logo',
                    'currentUrl' => $line->logo_url,
                    'class' => 'col-md-6',
                ])
                <div class="col-md-6">
                    <label class="admin-field-label">Description</label>
                    <textarea name="description" class="form-control" rows="5">{{ old('description', $line->description) }}</textarea>
                    @if($line->logo_url)
                        <div class="admin-switch mt-3">
                            <input type="checkbox" name="remove_logo" value="1" class="form-check-input" id="remove-logo">
                            <label class="form-check-label fw-semibold" for="remove-logo">Remove current logo</label>
                        </div>
                    @endif
                    <div class="admin-switch mt-3">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="line-active" @checked(old('is_active', $line->is_active ?? true))>
                        <label class="form-check-label fw-semibold" for="line-active">Active</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.form-sticky-actions', ['cancelUrl' => route('admin.cruise-lines.index')])
</form>
@endsection
