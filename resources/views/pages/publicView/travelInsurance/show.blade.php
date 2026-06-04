@extends('layouts.app')
@section('body-class', 'home-3')
@section('content')
<x-site-breadcrumb :title="$item->displayPlanName()" page="travelinsurance" />
<div class="py-80">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    @if($item->featuredImageUrl())
                        <img src="{{ $item->featuredImageUrl() }}" class="card-img-top" alt="{{ $item->displayPlanName() }}" style="max-height:320px;object-fit:cover;">
                    @endif
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            @if($item->logo)<img src="{{ $item->logoUrl() }}" alt="" height="48">@endif
                            <div>
                                <p class="text-muted mb-0">{{ $item->displayCompany() }}</p>
                                <h1 class="h3 mb-0">{{ $item->displayPlanName() }}</h1>
                                <span class="badge bg-primary">{{ $item->planTypeLabel() }}</span>
                            </div>
                        </div>
                        @if($item->short_description)<p class="lead">{{ $item->short_description }}</p>@endif
                        @if($item->description)<div class="mb-4">{!! nl2br(e($item->description)) !!}</div>@endif

                        @if($item->benefits->isNotEmpty())
                        <h4 class="h5">Key benefits</h4>
                        <ul class="list-unstyled">
                            @foreach($item->benefits as $benefit)
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i><strong>{{ $benefit->title }}</strong> @if($benefit->description)<span class="text-muted">— {{ $benefit->description }}</span>@endif</li>
                            @endforeach
                        </ul>
                        @endif

                        @if($item->exclusions->isNotEmpty())
                        <h4 class="h5 mt-4">Exclusions</h4>
                        <ul>
                            @foreach($item->exclusions as $ex)
                                <li><strong>{{ $ex->title }}</strong> @if($ex->description)— {{ $ex->description }}@endif</li>
                            @endforeach
                        </ul>
                        @endif

                        <h4 class="h5 mt-4">Eligibility</h4>
                        <ul>
                            @if($item->min_age || $item->max_age)<li>Age: {{ $item->min_age ?? '—' }} – {{ $item->max_age ?? '—' }}</li>@endif
                            @if($item->schengen_covered)<li>Schengen area covered</li>@endif
                            @if($item->worldwide_covered)<li>Worldwide coverage</li>@endif
                            @if($item->covered_countries)<li>Countries: {{ $item->covered_countries }}</li>@endif
                        </ul>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            @if($item->brochureUrl())<a href="{{ $item->brochureUrl() }}" class="btn btn-outline-primary btn-sm" target="_blank">Brochure PDF</a>@endif
                            @if($item->policyWordingUrl())<a href="{{ $item->policyWordingUrl() }}" class="btn btn-outline-primary btn-sm" target="_blank">Policy wording</a>@endif
                            @if($item->termsPdfUrl())<a href="{{ $item->termsPdfUrl() }}" class="btn btn-outline-primary btn-sm" target="_blank">Terms PDF</a>@endif
                        </div>
                    </div>
                </div>
                @if($cmsBlocks->get('faq'))
                    <div class="card border-0 shadow-sm p-4 mb-4"><h4>{{ $cmsBlocks->get('faq')->title }}</h4>{!! $cmsBlocks->get('faq')->content !!}</div>
                @endif
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 sticky-top" style="top:100px;">
                    @if($item->formattedMedicalCoverage())<p class="mb-1 text-muted">Medical coverage</p><h3 class="text-primary">{{ $item->formattedMedicalCoverage() }}</h3>@endif
                    <p class="h4 mb-3">{{ $item->displayPremium() }} <small class="text-muted">indicative</small></p>
                    <a href="{{ route('travelinsurance.quote.wizard', ['travelInsurance' => $item->slug]) }}" class="theme-btn w-100 text-center mb-2">Request Insurance Quote</a>
                    <a href="{{ route('travelinsurance') }}" class="btn btn-outline-secondary w-100">Compare all plans</a>
                </div>
                @if($related->isNotEmpty())
                <div class="card border-0 shadow-sm p-4 mt-4">
                    <h5>Related plans</h5>
                    @foreach($related as $rel)
                        <a href="{{ route('travelinsurance.show', $rel->slug) }}" class="d-block mb-2">{{ $rel->displayPlanName() }}</a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
