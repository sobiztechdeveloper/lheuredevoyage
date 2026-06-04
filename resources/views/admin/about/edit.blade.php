@extends('layouts.admin.app')

@section('title', 'About Page')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
<form method="POST" action="{{ route('admin.about.update') }}" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
        <div class="col-12">
            <label class="form-label">About page breadcrumb image</label>
            <p class="text-muted small mb-2">Optional. Overrides the site default on the About page only. Recommended size: 1920×350 px.</p>
            @if($about->breadcrumb_image ?? null)
                <div class="mb-2">
                    <img src="{{ $about->breadcrumb_image_url }}" alt="Current breadcrumb" class="d-block rounded border" style="max-height:120px;width:auto;max-width:100%;">
                    <small class="text-muted d-block mt-1">Current image (shown on the public About page breadcrumb).</small>
                </div>
            @endif
            <input type="file" name="breadcrumb_image" class="form-control @error('breadcrumb_image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp,image/gif">
            @error('breadcrumb_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6"><label class="form-label">Heading</label><input type="text" name="heading" class="form-control" value="{{ old('heading', $about->heading) }}"></div>
        <div class="col-md-6"><label class="form-label">Subheading</label><input type="text" name="subheading" class="form-control" value="{{ old('subheading', $about->subheading) }}"></div>
        <div class="col-12"><label class="form-label">Content</label><textarea name="content" class="form-control" rows="5">{{ old('content', $about->content) }}</textarea></div>
        <div class="col-md-6"><label class="form-label">Primary Image (path)</label><input type="text" name="image_primary" class="form-control" value="{{ old('image_primary', $about->image_primary) }}" placeholder="assets/img/about/01.jpg"></div>
        <div class="col-md-6"><label class="form-label">Secondary Image (path)</label><input type="text" name="image_secondary" class="form-control" value="{{ old('image_secondary', $about->image_secondary) }}" placeholder="assets/img/about/02.jpg"></div>
        <div class="col-md-3"><label class="form-label">Experience Years</label><input type="number" name="experience_years" class="form-control" value="{{ old('experience_years', $about->experience_years) }}"></div>
        <div class="col-md-3 d-flex align-items-end">
            <div class="form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="about_is_active" @checked(old('is_active', $about->is_active ?? true))>
                <label class="form-check-label" for="about_is_active">Active</label>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Save</button>
</form>
@endsection
