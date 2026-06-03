@extends('layouts.admin.app')

@section('title', 'About Page')

@section('content')
<form method="POST" action="{{ route('admin.about.update') }}">
    @csrf @method('PUT')
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Heading</label><input type="text" name="heading" class="form-control" value="{{ old('heading', $about->heading) }}"></div>
        <div class="col-md-6"><label class="form-label">Subheading</label><input type="text" name="subheading" class="form-control" value="{{ old('subheading', $about->subheading) }}"></div>
        <div class="col-12"><label class="form-label">Content</label><textarea name="content" class="form-control" rows="5">{{ old('content', $about->content) }}</textarea></div>
        <div class="col-md-6"><label class="form-label">Primary Image</label><input type="text" name="image_primary" class="form-control" value="{{ old('image_primary', $about->image_primary) }}"></div>
        <div class="col-md-6"><label class="form-label">Secondary Image</label><input type="text" name="image_secondary" class="form-control" value="{{ old('image_secondary', $about->image_secondary) }}"></div>
        <div class="col-md-3"><label class="form-label">Experience Years</label><input type="number" name="experience_years" class="form-control" value="{{ old('experience_years', $about->experience_years) }}"></div>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Save</button>
</form>
@endsection
