@extends('layouts.admin.app')
@section('title', ($item->exists ? 'Edit' : 'Create').' Cruise')
@section('content')
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<form method="POST" action="{{ $item->exists ? route('admin.cruises.update', $item) : route('admin.cruises.store') }}" enctype="multipart/form-data">
    @csrf @if($item->exists) @method('PUT') @endif

    <div class="card mb-3"><div class="card-header fw-semibold">1. Cruise information</div><div class="card-body row g-3">
        <div class="col-md-4"><label class="form-label">Cruise name *</label><input type="text" name="name" class="form-control" required value="{{ old('name', $item->name) }}"></div>
        <div class="col-md-4"><label class="form-label">Cruise code</label><input type="text" name="cruise_code" class="form-control" value="{{ old('cruise_code', $item->cruise_code) }}"></div>
        <div class="col-md-4"><label class="form-label">Slug</label><input type="text" name="slug" class="form-control" value="{{ old('slug', $item->slug) }}" placeholder="auto-generated if empty"></div>
        <div class="col-md-4"><label class="form-label">Cruise line</label><input type="text" name="cruise_line" class="form-control" value="{{ old('cruise_line', $item->cruise_line) }}"></div>
        <div class="col-md-4"><label class="form-label">Ship name</label><input type="text" name="ship_name" class="form-control" value="{{ old('ship_name', $item->ship_name) }}"></div>
        <div class="col-md-4"><label class="form-label">Ship class</label><select name="ship_class" class="form-select"><option value="">—</option>@foreach($shipClasses as $k => $l)<option value="{{ $k }}" @selected(old('ship_class', $item->ship_class) === $k)>{{ $l }}</option>@endforeach</select></div>
        <div class="col-md-3"><label class="form-label">Ship capacity</label><input type="number" name="ship_capacity" class="form-control" min="0" value="{{ old('ship_capacity', $item->ship_capacity) }}"></div>
        <div class="col-md-3"><label class="form-label">Launch year</label><input type="number" name="launch_year" class="form-control" min="1900" max="2100" value="{{ old('launch_year', $item->launch_year) }}"></div>
        <div class="col-md-3"><label class="form-label">Cruise region</label><select name="cruise_region" class="form-select"><option value="">—</option>@foreach($regions as $k => $l)<option value="{{ $k }}" @selected(old('cruise_region', $item->cruise_region) === $k)>{{ $l }}</option>@endforeach</select></div>
        <div class="col-md-3"><label class="form-label">Duration (nights)</label><input type="number" name="duration_nights" class="form-control" min="1" value="{{ old('duration_nights', $item->duration_nights) }}"></div>
        <div class="col-md-4"><label class="form-label">Departure port</label><input type="text" name="departure_port" class="form-control" value="{{ old('departure_port', $item->departure_port) }}"></div>
        <div class="col-md-4"><label class="form-label">Arrival port</label><input type="text" name="arrival_port" class="form-control" value="{{ old('arrival_port', $item->arrival_port) }}"></div>
        <div class="col-md-4"><label class="form-label">Duration (days)</label><input type="number" name="duration_days" class="form-control" min="1" value="{{ old('duration_days', $item->duration_days) }}"></div>
        <div class="col-12"><label class="form-label">Short description</label><textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $item->short_description) }}</textarea></div>
        <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4">{{ old('description', $item->description) }}</textarea></div>
        <div class="col-md-4"><label class="form-label">Featured image</label>@if($item->featured_image || $item->image)<img src="{{ $item->image_url }}" height="40" class="d-block mb-2">@endif<input type="file" name="featured_image" class="form-control" accept="image/*"></div>
        <div class="col-md-4"><label class="form-label">Gallery images</label>
            @if($item->relationLoaded('galleryImages') && $item->galleryImages->isNotEmpty())
                <div class="d-flex flex-wrap gap-2 mb-2">@foreach($item->galleryImages as $img)<img src="{{ $item->mediaUrl($img->path) }}" height="40" alt="">@endforeach</div>
            @endif
            <input type="file" name="gallery_images[]" class="form-control" multiple accept="image/*">
        </div>
        <div class="col-md-4"><label class="form-label">Brochure PDF</label>@if($item->brochure_pdf)<a href="{{ $item->brochureUrl() }}" target="_blank" class="d-block small mb-2">Current brochure</a>@endif<input type="file" name="brochure_pdf" class="form-control" accept=".pdf"></div>
        <div class="col-md-4"><label class="form-label">Deck plan PDF</label>@if($item->deck_plan_pdf)<a href="{{ $item->deckPlanUrl() }}" target="_blank" class="d-block small mb-2">Current deck plan</a>@endif<input type="file" name="deck_plan_pdf" class="form-control" accept=".pdf"></div>
        <div class="col-md-4"><label class="form-label">Terms PDF</label>@if($item->terms_pdf)<a href="{{ $item->termsPdfUrl() }}" target="_blank" class="d-block small mb-2">Current terms</a>@endif<input type="file" name="terms_pdf" class="form-control" accept=".pdf"></div>
    </div></div>

    <div class="card mb-3"><div class="card-header fw-semibold">2. Itinerary</div><div class="card-body">
        @for($i = 0; $i < 7; $i++)
            @php $day = $item->itineraryDays[$i] ?? null; @endphp
            <div class="row g-2 mb-2 align-items-end">
                <div class="col-md-1"><label class="form-label small">Day</label><input type="number" name="itinerary[{{ $i }}][day_number]" class="form-control" min="1" value="{{ old("itinerary.$i.day_number", $day?->day_number ?? ($i + 1)) }}"></div>
                <div class="col-md-3"><label class="form-label small">Port</label><input type="text" name="itinerary[{{ $i }}][port_name]" class="form-control" placeholder="Port name" value="{{ old("itinerary.$i.port_name", $day?->port_name) }}"></div>
                <div class="col-md-2"><label class="form-label small">Arrival</label><input type="time" name="itinerary[{{ $i }}][arrival_time]" class="form-control" value="{{ old("itinerary.$i.arrival_time", $day?->arrival_time ? \Illuminate\Support\Str::of($day->arrival_time)->substr(0, 5) : '') }}"></div>
                <div class="col-md-2"><label class="form-label small">Departure</label><input type="time" name="itinerary[{{ $i }}][departure_time]" class="form-control" value="{{ old("itinerary.$i.departure_time", $day?->departure_time ? \Illuminate\Support\Str::of($day->departure_time)->substr(0, 5) : '') }}"></div>
                <div class="col-md-4"><label class="form-label small">Description</label><input type="text" name="itinerary[{{ $i }}][description]" class="form-control" placeholder="Day description" value="{{ old("itinerary.$i.description", $day?->description) }}"></div>
            </div>
        @endfor
    </div></div>

    <div class="card mb-3"><div class="card-header fw-semibold">3. Cabins</div><div class="card-body">
        @for($i = 0; $i < 6; $i++)
            @php $cabin = $item->cabins[$i] ?? null; @endphp
            <div class="row g-2 mb-3 pb-3 border-bottom">
                <div class="col-md-2"><label class="form-label small">Cabin type</label><select name="cabins[{{ $i }}][cabin_type]" class="form-select"><option value="">—</option>@foreach($cabinTypes as $k => $l)<option value="{{ $k }}" @selected(old("cabins.$i.cabin_type", $cabin?->cabin_type) === $k)>{{ $l }}</option>@endforeach</select></div>
                <div class="col-md-2"><label class="form-label small">Name</label><input type="text" name="cabins[{{ $i }}][name]" class="form-control" placeholder="Cabin name" value="{{ old("cabins.$i.name", $cabin?->name) }}"></div>
                <div class="col-md-3"><label class="form-label small">Description</label><input type="text" name="cabins[{{ $i }}][description]" class="form-control" value="{{ old("cabins.$i.description", $cabin?->description) }}"></div>
                <div class="col-md-1"><label class="form-label small">Adults</label><input type="number" name="cabins[{{ $i }}][max_adults]" class="form-control" min="0" value="{{ old("cabins.$i.max_adults", $cabin?->max_adults ?? 2) }}"></div>
                <div class="col-md-1"><label class="form-label small">Children</label><input type="number" name="cabins[{{ $i }}][max_children]" class="form-control" min="0" value="{{ old("cabins.$i.max_children", $cabin?->max_children ?? 0) }}"></div>
                <div class="col-md-1"><label class="form-label small">Max occ.</label><input type="number" name="cabins[{{ $i }}][max_occupancy]" class="form-control" min="1" value="{{ old("cabins.$i.max_occupancy", $cabin?->max_occupancy ?? 2) }}"></div>
                <div class="col-md-1"><label class="form-label small">Size</label><input type="text" name="cabins[{{ $i }}][size]" class="form-control" placeholder="m²" value="{{ old("cabins.$i.size", $cabin?->size) }}"></div>
                <div class="col-md-1"><label class="form-label small">From CHF</label><input type="number" step="0.01" name="cabins[{{ $i }}][starting_price]" class="form-control" value="{{ old("cabins.$i.starting_price", $cabin?->starting_price) }}"></div>
                <div class="col-md-1 form-check mt-4"><input type="checkbox" name="cabins[{{ $i }}][featured]" value="1" class="form-check-input" id="cabin_featured_{{ $i }}" @checked(old("cabins.$i.featured", $cabin?->featured))><label class="form-check-label small" for="cabin_featured_{{ $i }}">Featured</label></div>
            </div>
        @endfor
    </div></div>

    <div class="card mb-3"><div class="card-header fw-semibold">4. Included services</div><div class="card-body row g-2">
        @php $includedSelected = old('included_services', $item->included_services ?? []); @endphp
        @foreach($includedOptions as $key => $label)
            <div class="col-md-3 form-check"><input type="checkbox" name="included_services[]" value="{{ $key }}" class="form-check-input" id="included_{{ $key }}" @checked(in_array($key, $includedSelected, true))><label class="form-check-label" for="included_{{ $key }}">{{ $label }}</label></div>
        @endforeach
    </div></div>

    <div class="card mb-3"><div class="card-header fw-semibold">5. Not included</div><div class="card-body row g-2">
        @php $notIncludedSelected = old('not_included_services', $item->not_included_services ?? []); @endphp
        @foreach($notIncludedOptions as $key => $label)
            <div class="col-md-3 form-check"><input type="checkbox" name="not_included_services[]" value="{{ $key }}" class="form-check-input" id="not_included_{{ $key }}" @checked(in_array($key, $notIncludedSelected, true))><label class="form-check-label" for="not_included_{{ $key }}">{{ $label }}</label></div>
        @endforeach
    </div></div>

    <div class="card mb-3"><div class="card-header fw-semibold">6. Master data</div><div class="card-body">
        @foreach($masterDataOptions as $relation => $options)
            @php
                $cfg = config('master_data.catalog.cruise.'.$relation, []);
                $param = $cfg['param'] ?? $relation;
                $sectionLabel = $cfg['label'] ?? ucfirst($relation);
                $selected = old($param, $masterDataSelected[$relation] ?? []);
            @endphp
            <p class="fw-semibold mb-2">{{ $sectionLabel }}</p>
            <div class="row g-2 mb-3">
                @foreach($options as $option)
                    <div class="col-md-3 form-check">
                        <input type="checkbox" name="{{ $param }}[]" value="{{ $option->id }}" class="form-check-input" id="{{ $param }}_{{ $option->id }}" @checked(in_array($option->id, (array) $selected))>
                        <label class="form-check-label" for="{{ $param }}_{{ $option->id }}">{{ $option->name }}</label>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div></div>

    <div class="card mb-3"><div class="card-header fw-semibold">7. Publishing</div><div class="card-body row g-3">
        <div class="col-md-3"><label class="form-label">Price (from) *</label><input type="number" step="0.01" name="price" class="form-control" required value="{{ old('price', $item->price) }}"></div>
        <div class="col-md-2"><label class="form-label">Sort order</label><input type="number" name="sort_order" class="form-control" min="0" value="{{ old('sort_order', $item->sort_order) }}"></div>
        <div class="col-md-2 form-check mt-4"><input type="checkbox" name="featured" value="1" class="form-check-input" id="featured" @checked(old('featured', $item->featured ?? $item->is_featured))><label class="form-check-label" for="featured">Featured</label></div>
        <div class="col-md-2 form-check mt-4"><input type="checkbox" name="status" value="1" class="form-check-input" id="status" @checked(old('status', $item->status ?? $item->is_active ?? true))><label class="form-check-label" for="status">Active</label></div>
    </div></div>

    <button type="submit" class="btn btn-primary">Save cruise</button>
    <a href="{{ route('admin.cruises.index') }}" class="btn btn-outline-secondary">Cancel</a>
</form>
@endsection
