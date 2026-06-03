@extends('layouts.admin.app')

@section('title', 'Website Settings')

@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Company Name</label>
            <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $settings->company_name) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Company Email</label>
            <input type="email" name="company_email" class="form-control" value="{{ old('company_email', $settings->company_email) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Company Phone</label>
            <input type="text" name="company_phone" class="form-control" value="{{ old('company_phone', $settings->company_phone) }}">
        </div>
        <div class="col-12">
            <label class="form-label">Company Address</label>
            <textarea name="company_address" class="form-control" rows="2">{{ old('company_address', $settings->company_address) }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label">Logo</label>
            @if($settings->logo)<img src="{{ $settings->logo_url }}" alt="" class="d-block mb-2" height="48">@endif
            <input type="file" name="logo" class="form-control" accept="image/*">
        </div>
        <div class="col-md-6">
            <label class="form-label">Favicon</label>
            @if($settings->favicon)<img src="{{ $settings->favicon_url }}" alt="" class="d-block mb-2" height="32">@endif
            <input type="file" name="favicon" class="form-control" accept="image/*">
        </div>
        <div class="col-md-6"><label class="form-label">Facebook URL</label><input type="url" name="facebook_url" class="form-control" value="{{ old('facebook_url', $settings->facebook_url) }}"></div>
        <div class="col-md-6"><label class="form-label">Instagram URL</label><input type="url" name="instagram_url" class="form-control" value="{{ old('instagram_url', $settings->instagram_url) }}"></div>
        <div class="col-md-6"><label class="form-label">LinkedIn URL</label><input type="url" name="linkedin_url" class="form-control" value="{{ old('linkedin_url', $settings->linkedin_url) }}"></div>
        <div class="col-md-6"><label class="form-label">YouTube URL</label><input type="url" name="youtube_url" class="form-control" value="{{ old('youtube_url', $settings->youtube_url) }}"></div>
        <div class="col-12">
            <label class="form-label">Footer Text</label>
            <textarea name="footer_text" class="form-control" rows="3">{{ old('footer_text', $settings->footer_text) }}</textarea>
        </div>
        <div class="col-12">
            <label class="form-label">Copyright Text</label>
            <input type="text" name="copyright_text" class="form-control" value="{{ old('copyright_text', $settings->copyright_text) }}" placeholder="All Rights Reserved.">
        </div>
    </div>
    <button type="submit" class="btn btn-primary mt-4">Save Settings</button>
</form>
@endsection
