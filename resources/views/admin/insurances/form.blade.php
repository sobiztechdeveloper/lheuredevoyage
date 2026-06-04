@extends('layouts.admin.app')
@section('title', ($item->exists ? 'Edit' : 'Create').' Insurance Plan')
@section('content')
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<form method="POST" action="{{ $item->exists ? route('admin.insurances.update', $item) : route('admin.insurances.store') }}" enctype="multipart/form-data">
    @csrf @if($item->exists) @method('PUT') @endif

    <div class="card mb-3"><div class="card-header fw-semibold">1. Insurance information</div><div class="card-body row g-3">
        <div class="col-md-4"><label class="form-label">Insurance company</label><input type="text" name="insurance_company" class="form-control" value="{{ old('insurance_company', $item->insurance_company) }}"></div>
        <div class="col-md-4"><label class="form-label">Plan name *</label><input type="text" name="name" class="form-control" required value="{{ old('name', $item->name) }}"></div>
        <div class="col-md-4"><label class="form-label">Plan code</label><input type="text" name="plan_code" class="form-control" value="{{ old('plan_code', $item->plan_code) }}"></div>
        <div class="col-md-4"><label class="form-label">Plan type</label><select name="plan_type" class="form-select"><option value="">—</option>@foreach($planTypes as $k => $l)<option value="{{ $k }}" @selected(old('plan_type', $item->plan_type) === $k)>{{ $l }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label">Slug</label><input type="text" name="slug" class="form-control" value="{{ old('slug', $item->slug) }}"></div>
        <div class="col-12"><label class="form-label">Short description</label><textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $item->short_description) }}</textarea></div>
        <div class="col-12"><label class="form-label">Full description</label><textarea name="description" class="form-control" rows="4">{{ old('description', $item->description) }}</textarea></div>
        <div class="col-md-4"><label class="form-label">Logo</label>@if($item->logo)<img src="{{ $item->logoUrl() }}" height="40" class="d-block mb-2">@endif<input type="file" name="logo" class="form-control"></div>
        <div class="col-md-4"><label class="form-label">Featured image</label>@if($item->featured_image)<img src="{{ $item->featuredImageUrl() }}" height="40" class="d-block mb-2">@endif<input type="file" name="featured_image" class="form-control"></div>
        <div class="col-md-4"><label class="form-label">Gallery images</label><input type="file" name="gallery_images[]" class="form-control" multiple accept="image/*"></div>
        <div class="col-md-4"><label class="form-label">Brochure PDF</label><input type="file" name="brochure_pdf" class="form-control" accept=".pdf"></div>
        <div class="col-md-4"><label class="form-label">Policy wording PDF</label><input type="file" name="policy_wording_pdf" class="form-control" accept=".pdf"></div>
        <div class="col-md-4"><label class="form-label">Terms PDF</label><input type="file" name="terms_pdf" class="form-control" accept=".pdf"></div>
    </div></div>

    <div class="card mb-3"><div class="card-header fw-semibold">2. Coverage details</div><div class="card-body row g-3">
        <div class="col-md-3"><label class="form-label">Medical coverage</label><input type="number" step="0.01" name="medical_coverage_amount" class="form-control" value="{{ old('medical_coverage_amount', $item->medical_coverage_amount) }}"></div>
        <div class="col-md-3"><label class="form-label">Currency</label><select name="coverage_currency" class="form-select">@foreach($currencies as $c)<option value="{{ $c }}" @selected(old('coverage_currency', $item->coverage_currency) === $c)>{{ $c }}</option>@endforeach</select></div>
        <div class="col-md-3"><label class="form-label">Trip cancellation</label><input type="number" step="0.01" name="trip_cancellation" class="form-control" value="{{ old('trip_cancellation', $item->trip_cancellation) }}"></div>
        <div class="col-md-3"><label class="form-label">Baggage loss</label><input type="number" step="0.01" name="baggage_loss" class="form-control" value="{{ old('baggage_loss', $item->baggage_loss) }}"></div>
        <div class="col-md-3 form-check mt-4"><input type="checkbox" name="covid_coverage" value="1" class="form-check-input" @checked(old('covid_coverage', $item->covid_coverage))><label class="form-check-label">COVID coverage</label></div>
        <div class="col-md-3 form-check mt-4"><input type="checkbox" name="adventure_sports_coverage" value="1" class="form-check-input" @checked(old('adventure_sports_coverage', $item->adventure_sports_coverage))><label class="form-check-label">Adventure sports</label></div>
        <div class="col-md-3 form-check mt-4"><input type="checkbox" name="winter_sports_coverage" value="1" class="form-check-input" @checked(old('winter_sports_coverage', $item->winter_sports_coverage))><label class="form-check-label">Winter sports</label></div>
    </div></div>

    <div class="card mb-3"><div class="card-header fw-semibold">3. Eligibility</div><div class="card-body row g-3">
        <div class="col-md-2"><label class="form-label">Min age</label><input type="number" name="min_age" class="form-control" value="{{ old('min_age', $item->min_age) }}"></div>
        <div class="col-md-2"><label class="form-label">Max age</label><input type="number" name="max_age" class="form-control" value="{{ old('max_age', $item->max_age) }}"></div>
        <div class="col-12"><label class="form-label">Covered countries</label><textarea name="covered_countries" class="form-control" rows="2">{{ old('covered_countries', $item->covered_countries) }}</textarea></div>
        <div class="col-md-3 form-check"><input type="checkbox" name="schengen_covered" value="1" class="form-check-input" @checked(old('schengen_covered', $item->schengen_covered))><label class="form-check-label">Schengen</label></div>
        <div class="col-md-3 form-check"><input type="checkbox" name="worldwide_covered" value="1" class="form-check-input" @checked(old('worldwide_covered', $item->worldwide_covered))><label class="form-check-label">Worldwide</label></div>
    </div></div>

    <div class="card mb-3"><div class="card-header fw-semibold">4. Benefits & exclusions</div><div class="card-body">
        <p class="small text-muted">Benefits</p>
        @for($i = 0; $i < 5; $i++)
            @php $b = $item->benefits[$i] ?? null; @endphp
            <div class="row g-2 mb-2">
                <div class="col-md-4"><input type="text" name="benefits[{{ $i }}][title]" class="form-control" placeholder="Benefit title" value="{{ old("benefits.$i.title", $b?->title) }}"></div>
                <div class="col-md-6"><input type="text" name="benefits[{{ $i }}][description]" class="form-control" placeholder="Description" value="{{ old("benefits.$i.description", $b?->description) }}"></div>
                <div class="col-md-2"><input type="text" name="benefits[{{ $i }}][icon]" class="form-control" placeholder="Icon class" value="{{ old("benefits.$i.icon", $b?->icon) }}"></div>
            </div>
        @endfor
        <p class="small text-muted mt-3">Exclusions</p>
        @for($i = 0; $i < 3; $i++)
            @php $e = $item->exclusions[$i] ?? null; @endphp
            <div class="row g-2 mb-2">
                <div class="col-md-4"><input type="text" name="exclusions[{{ $i }}][title]" class="form-control" placeholder="Exclusion title" value="{{ old("exclusions.$i.title", $e?->title) }}"></div>
                <div class="col-md-8"><input type="text" name="exclusions[{{ $i }}][description]" class="form-control" placeholder="Description" value="{{ old("exclusions.$i.description", $e?->description) }}"></div>
            </div>
        @endfor
    </div></div>

    <div class="card mb-3"><div class="card-header fw-semibold">5. Pricing & publishing</div><div class="card-body row g-3">
        <div class="col-md-3"><label class="form-label">Base premium *</label><input type="number" step="0.01" name="base_premium" class="form-control" required value="{{ old('base_premium', $item->base_premium ?? $item->price) }}"></div>
        <div class="col-md-2"><label class="form-label">Currency</label><select name="premium_currency" class="form-select">@foreach($currencies as $c)<option value="{{ $c }}" @selected(old('premium_currency', $item->premium_currency) === $c)>{{ $c }}</option>@endforeach</select></div>
        <div class="col-md-3 form-check mt-4"><input type="checkbox" name="price_per_person" value="1" class="form-check-input" @checked(old('price_per_person', $item->price_per_person ?? true))><label class="form-check-label">Per person</label></div>
        <div class="col-md-2"><label class="form-label">Sort order</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $item->sort_order) }}"></div>
        <div class="col-md-2 form-check mt-4"><input type="checkbox" name="featured" value="1" class="form-check-input" @checked(old('featured', $item->featured))><label class="form-check-label">Featured</label></div>
        <div class="col-md-2 form-check mt-4"><input type="checkbox" name="status" value="1" class="form-check-input" @checked(old('status', $item->status ?? true))><label class="form-check-label">Active</label></div>
    </div></div>

    <button type="submit" class="btn btn-primary">Save insurance plan</button>
    <a href="{{ route('admin.insurances.index') }}" class="btn btn-outline-secondary">Cancel</a>
</form>
@endsection
