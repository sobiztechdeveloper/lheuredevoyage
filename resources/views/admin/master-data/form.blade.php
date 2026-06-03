@extends('layouts.admin.app')
@section('title', ($item->exists ? 'Edit' : 'Create').' '.$config['label'])
@section('content')
<form method="POST" action="{{ $item->exists ? route('admin.master-data.'.$type.'.update', $item) : route('admin.master-data.'.$type.'.store') }}">
    @csrf
    @if($item->exists) @method('PUT') @endif

    <div class="admin-form-card">
        <div class="admin-form-card-header">
            <h2><i class="far fa-sliders"></i> {{ $config['label'] }} Details</h2>
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
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $item->slug) }}">
                </div>
                <div class="col-12">
                    <label class="admin-field-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $item->description) }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="admin-field-label">Icon (CSS class)</label>
                    <input type="text" name="icon" class="form-control" value="{{ old('icon', $item->icon) }}" placeholder="fal fa-wifi">
                </div>
                <div class="col-md-4">
                    <label class="admin-field-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" min="0" value="{{ old('sort_order', $item->sort_order ?? 0) }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="admin-switch w-100">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', $item->is_active ?? true))>
                        <label class="form-check-label fw-semibold" for="is_active">Active</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.form-sticky-actions', ['cancelUrl' => route('admin.master-data.'.$type.'.index')])
</form>
@endsection
