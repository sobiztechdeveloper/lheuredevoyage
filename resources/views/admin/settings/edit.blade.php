@extends('layouts.admin.app')

@section('title', 'Website Settings')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    <div>
        <p class="text-muted small mb-0">These values power the public site header, footer, SEO, legal pages, and default page banners. Changes appear immediately after save.</p>
    </div>
    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-admin-outline btn-sm">View site</a>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="admin-catalog-form">
    @csrf

    <div class="admin-form-card">
        <div class="admin-form-card-header">
            <h2><i class="far fa-building"></i> Company & Contact</h2>
        </div>
        <div class="admin-form-card-body">
            <p class="text-muted small mb-3">Header top bar, footer, invoices</p>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="admin-field-label">Company Name</label>
                    <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $settings->company_name) }}">
                    @error('company_name')<div class="admin-field-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="admin-field-label">Company Email</label>
                    <input type="email" name="company_email" class="form-control @error('company_email') is-invalid @enderror" value="{{ old('company_email', $settings->company_email) }}">
                    @error('company_email')<div class="admin-field-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="admin-field-label">Company Phone</label>
                    <input type="text" name="company_phone" class="form-control @error('company_phone') is-invalid @enderror" value="{{ old('company_phone', $settings->company_phone) }}">
                    @error('company_phone')<div class="admin-field-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="admin-field-label">Company Address</label>
                    <textarea name="company_address" class="form-control @error('company_address') is-invalid @enderror" rows="3" placeholder="Street and number&#10;Postcode City&#10;Country">{{ old('company_address', $settings->company_address) }}</textarea>
                    <p class="text-muted small mb-0 mt-1">One line per part: street, postcode + city, then country.</p>
                    @error('company_address')<div class="admin-field-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="admin-field-label">VAT Number</label>
                    <input type="text" name="vat_number" class="form-control" value="{{ old('vat_number', $settings->vat_number) }}">
                </div>
                <div class="col-md-4">
                    <label class="admin-field-label">Registration Number</label>
                    <input type="text" name="registration_number" class="form-control" value="{{ old('registration_number', $settings->registration_number) }}">
                </div>
                <div class="col-md-4">
                    <label class="admin-field-label">Business Hours</label>
                    <input type="text" name="business_hours" class="form-control" value="{{ old('business_hours', $settings->business_hours) }}" placeholder="Mon–Fri 09:00–18:00 CET">
                </div>
            </div>
        </div>
    </div>

    <div class="admin-form-card">
        <div class="admin-form-card-header">
            <h2><i class="far fa-image"></i> Branding</h2>
        </div>
        <div class="admin-form-card-body">
            <p class="text-muted small mb-3">Navbar, footer, browser tab, social sharing</p>
            <div class="row g-4">
                <div class="col-md-6">
                    @include('admin.partials.single-image-upload', [
                        'name' => 'logo',
                        'label' => 'Logo',
                        'hint' => 'Shown in the navigation bar and footer.',
                        'currentUrl' => $settings->logo ? $settings->logo_url : null,
                        'accept' => 'image/jpeg,image/png,image/webp,image/svg+xml',
                        'class' => 'col-12',
                    ])
                </div>
                <div class="col-md-6">
                    @include('admin.partials.single-image-upload', [
                        'name' => 'favicon',
                        'label' => 'Favicon',
                        'hint' => 'Browser tab icon.',
                        'currentUrl' => $settings->favicon ? $settings->favicon_url : null,
                        'accept' => 'image/jpeg,image/png,image/webp,image/x-icon,image/vnd.microsoft.icon',
                        'class' => 'col-12',
                    ])
                </div>
                @include('admin.partials.single-image-upload', [
                    'name' => 'default_breadcrumb_image',
                    'label' => 'Default Breadcrumb Background',
                    'hint' => 'Used on inner pages when no page-specific banner is set. Recommended: 1920×300 px.',
                    'currentUrl' => $settings->default_breadcrumb_image ? $settings->default_breadcrumb_image_url : null,
                    'class' => 'col-12',
                ])
            </div>
        </div>
    </div>

    <div class="admin-form-card">
        <div class="admin-form-card-header">
            <h2><i class="far fa-images"></i> Catalog Page Banners</h2>
        </div>
        <div class="admin-form-card-body">
            <p class="text-muted small mb-3">Breadcrumb backgrounds for tour packages, flights, hotels, cruises, cars, and travel insurance list pages. Recommended: 1920×300 px. Falls back to the default breadcrumb image above when not set.</p>
            <div class="row g-4">
                @foreach(\App\Support\PageBanner::catalogAdminLabels() as $pageKey => $label)
                    @include('admin.partials.single-image-upload', [
                        'name' => 'breadcrumb_' . $pageKey,
                        'label' => $label,
                        'hint' => 'List page banner for ' . $label . '.',
                        'currentUrl' => $settings->pageBreadcrumbImageUrl($pageKey),
                        'class' => 'col-md-6',
                    ])
                @endforeach
            </div>
        </div>
    </div>

    <div class="admin-form-card">
        <div class="admin-form-card-header">
            <h2><i class="far fa-share-nodes"></i> Social Media</h2>
        </div>
        <div class="admin-form-card-body">
            <p class="text-muted small mb-3">Footer icons</p>
            <div class="row g-3">
                <div class="col-md-6"><label class="admin-field-label">Facebook URL</label><input type="url" name="facebook_url" class="form-control" value="{{ old('facebook_url', $settings->facebook_url) }}" placeholder="https://"></div>
                <div class="col-md-6"><label class="admin-field-label">Instagram URL</label><input type="url" name="instagram_url" class="form-control" value="{{ old('instagram_url', $settings->instagram_url) }}" placeholder="https://"></div>
                <div class="col-md-6"><label class="admin-field-label">LinkedIn URL</label><input type="url" name="linkedin_url" class="form-control" value="{{ old('linkedin_url', $settings->linkedin_url) }}" placeholder="https://"></div>
                <div class="col-md-6"><label class="admin-field-label">YouTube URL</label><input type="url" name="youtube_url" class="form-control" value="{{ old('youtube_url', $settings->youtube_url) }}" placeholder="https://"></div>
            </div>
        </div>
    </div>

    <div class="admin-form-card">
        <div class="admin-form-card-header">
            <h2><i class="far fa-window-maximize"></i> Footer</h2>
        </div>
        <div class="admin-form-card-body">
            <p class="text-muted small mb-3">Site-wide footer block</p>
            <div class="row g-3">
                <div class="col-12">
                    <label class="admin-field-label">Footer Text</label>
                    <textarea name="footer_text" class="form-control" rows="3">{{ old('footer_text', $settings->footer_text) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.form-sticky-actions', ['cancelUrl' => route('admin.dashboard')])
</form>

<div class="admin-form-card mt-4">
    <div class="admin-form-card-body small text-muted">
        <strong>Also managed elsewhere:</strong>
        <a href="{{ route('admin.contact-details.edit') }}">Contact Details</a> (contact page sidebar &amp; map),
        <a href="{{ route('admin.about.edit') }}">About Page</a> (about breadcrumb),
        <a href="{{ route('admin.hero-sections.index') }}">Hero Sections</a> (homepage banner).
    </div>
</div>
@endsection
