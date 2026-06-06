@extends('layouts.admin.app')

@section('title', 'About Page')

@section('content')
<form method="POST" action="{{ route('admin.about.update') }}" enctype="multipart/form-data" class="admin-catalog-form">
    @csrf

    <div class="admin-form-card">
        <div class="admin-form-card-header">
            <h2><i class="far fa-circle-info"></i> About Content</h2>
        </div>
        <div class="admin-form-card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="admin-field-label">Heading</label>
                    <input type="text" name="heading" class="form-control" value="{{ old('heading', $about->heading) }}">
                </div>
                <div class="col-md-6">
                    <label class="admin-field-label">Subheading</label>
                    <input type="text" name="subheading" class="form-control" value="{{ old('subheading', $about->subheading) }}">
                </div>
                <div class="col-12">
                    <label class="admin-field-label">Content</label>
                    <textarea name="content" class="form-control" rows="5">{{ old('content', $about->content) }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="admin-field-label">Experience Years</label>
                    <input type="number" name="experience_years" class="form-control" value="{{ old('experience_years', $about->experience_years) }}">
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
                @include('admin.partials.single-image-upload', [
                    'name' => 'breadcrumb_image',
                    'label' => 'Breadcrumb Image',
                    'hint' => 'Optional. Overrides the site default on the About page only. Recommended: 1920×350 px.',
                    'currentUrl' => $about->breadcrumb_image ? $about->breadcrumb_image_url : null,
                    'class' => 'col-12',
                ])
                @include('admin.partials.single-image-upload', [
                    'name' => 'image_primary',
                    'label' => 'Primary Image',
                    'hint' => 'Left column image on the About section. Recommended: portrait, at least 400×500 px.',
                    'currentUrl' => $about->image_primary ? $about->image_primary_url : null,
                    'class' => 'col-md-6',
                ])
                @include('admin.partials.single-image-upload', [
                    'name' => 'image_secondary',
                    'label' => 'Secondary Image',
                    'hint' => 'Right column image on the About section. Recommended: portrait, at least 400×500 px.',
                    'currentUrl' => $about->image_secondary ? $about->image_secondary_url : null,
                    'class' => 'col-md-6',
                ])
            </div>
        </div>
    </div>

    <div class="admin-form-card">
        <div class="admin-form-card-header">
            <h2><i class="far fa-toggle-on"></i> Publishing</h2>
        </div>
        <div class="admin-form-card-body">
            <div class="admin-switch">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="about_is_active" @checked(old('is_active', $about->is_active ?? true))>
                <label class="form-check-label fw-semibold" for="about_is_active">Active (published on site)</label>
            </div>
        </div>
    </div>

    @include('admin.partials.form-sticky-actions', ['cancelUrl' => route('admin.dashboard')])
</form>
@endsection
