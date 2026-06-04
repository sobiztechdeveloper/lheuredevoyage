@extends('layouts.app')
@section('title', $page->resolvedMetaTitle())
@section('meta_description', $page->resolvedMetaDescription())
@section('canonical', $page->publicUrl())
@push('styles')
<style>
.legal-page-content h2,.legal-page-content h3{margin-top:1.5rem;margin-bottom:.75rem;color:#162F65;}
.legal-page-content p,.legal-page-content li{color:#444;line-height:1.75;}
.legal-page-content table{width:100%;margin:1rem 0;}
.legal-company-card{background:#f8fafc;border:1px solid #e8eef5;border-radius:8px;padding:1.5rem;margin-bottom:2rem;}
</style>
@endpush
@section('content')
<div class="site-breadcrumb" style="background: url({{ asset('assets/img/breadcrumb/01.jpg') }})">
    <div class="container">
        <h2 class="breadcrumb-title">{{ $page->title }}</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="active">{{ $page->title }}</li>
        </ul>
    </div>
</div>
<div class="py-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @if($page->summary)
                    <p class="lead text-muted mb-4">{{ $page->summary }}</p>
                @endif

                @if($companyInfo)
                    <div class="legal-company-card">
                        <h3 class="h5 mb-3" style="color:#162F65;">{{ $companyInfo['company_name'] }}</h3>
                        <div class="row g-3">
                            @if($companyInfo['address'])
                            <div class="col-md-6"><small class="text-muted d-block">Address</small>{{ $companyInfo['address'] }}</div>
                            @endif
                            @if($companyInfo['email'])
                            <div class="col-md-6"><small class="text-muted d-block">Email</small><a href="mailto:{{ $companyInfo['email'] }}">{{ $companyInfo['email'] }}</a></div>
                            @endif
                            @if($companyInfo['phone'])
                            <div class="col-md-6"><small class="text-muted d-block">Phone</small><a href="tel:{{ preg_replace('/\s+/', '', $companyInfo['phone']) }}">{{ $companyInfo['phone'] }}</a></div>
                            @endif
                            @if($companyInfo['whatsapp'])
                            <div class="col-md-6"><small class="text-muted d-block">WhatsApp</small>{{ $companyInfo['whatsapp'] }}</div>
                            @endif
                            @if($companyInfo['vat_number'])
                            <div class="col-md-6"><small class="text-muted d-block">VAT Number</small>{{ $companyInfo['vat_number'] }}</div>
                            @endif
                            @if($companyInfo['registration_number'])
                            <div class="col-md-6"><small class="text-muted d-block">Registration Number</small>{{ $companyInfo['registration_number'] }}</div>
                            @endif
                            @if($companyInfo['business_hours'])
                            <div class="col-md-6"><small class="text-muted d-block">Business Hours</small>{{ $companyInfo['business_hours'] }}</div>
                            @endif
                        </div>
                        @if($companyInfo['facebook_url'] || $companyInfo['instagram_url'] || $companyInfo['linkedin_url'] || $companyInfo['youtube_url'])
                        <div class="mt-3 pt-3 border-top">
                            <small class="text-muted d-block mb-2">Follow us</small>
                            @if($companyInfo['facebook_url'])<a href="{{ $companyInfo['facebook_url'] }}" class="me-2" target="_blank" rel="noopener">Facebook</a>@endif
                            @if($companyInfo['instagram_url'])<a href="{{ $companyInfo['instagram_url'] }}" class="me-2" target="_blank" rel="noopener">Instagram</a>@endif
                            @if($companyInfo['linkedin_url'])<a href="{{ $companyInfo['linkedin_url'] }}" class="me-2" target="_blank" rel="noopener">LinkedIn</a>@endif
                            @if($companyInfo['youtube_url'])<a href="{{ $companyInfo['youtube_url'] }}" target="_blank" rel="noopener">YouTube</a>@endif
                        </div>
                        @endif
                    </div>
                @endif

                <div class="legal-page-content terms-content">
                    {!! $page->content !!}
                </div>

                @if($page->updated_at)
                    <p class="text-muted small mt-4 mb-0">Last updated: {{ $page->updated_at->format('F j, Y') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
