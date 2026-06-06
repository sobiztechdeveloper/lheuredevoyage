@extends('layouts.admin.app')
@section('title', $line->exists ? 'Edit Cruise Line' : 'Add Cruise Line')
@section('content')
<div class="admin-page-header mb-4">
    <div>
        <h1>{{ $line->exists ? 'Edit Cruise Line' : 'Add Cruise Line' }}</h1>
    </div>
    <a href="{{ route('admin.cruise-lines.index') }}" class="btn btn-admin-outline btn-sm">Back to list</a>
</div>

<form method="POST" action="{{ $line->exists ? route('admin.cruise-lines.update', $line) : route('admin.cruise-lines.store') }}" enctype="multipart/form-data">
    @csrf
    @if($line->exists) @method('PUT') @endif

    <div class="admin-form-card">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $line->name) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Sort Order</label>
                <input type="number" name="sort_order" class="form-control" min="0" value="{{ old('sort_order', $line->sort_order ?? 0) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $line->slug) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Logo</label>
                <input type="file" name="logo" class="form-control" accept="image/*">
                @if($line->logo_url)
                    <div class="mt-2 d-flex align-items-center gap-2">
                        <img src="{{ $line->logo_url }}" alt="" width="48" height="48" class="rounded border">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" name="remove_logo" value="1" id="remove-logo">
                            <label class="form-check-label" for="remove-logo">Remove current logo</label>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $line->description) }}</textarea>
            </div>
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="line-active" @checked(old('is_active', $line->is_active ?? true))>
                    <label class="form-check-label" for="line-active">Active</label>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-sticky-actions">
        <button type="submit" class="btn btn-admin-primary">{{ $line->exists ? 'Update Cruise Line' : 'Create Cruise Line' }}</button>
        <a href="{{ route('admin.cruise-lines.index') }}" class="btn btn-admin-outline">Cancel</a>
    </div>
</form>
@endsection
