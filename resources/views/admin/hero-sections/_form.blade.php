<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Title *</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $heroSection->title) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Subtitle</label>
        <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $heroSection->subtitle) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Button Text</label>
        <input type="text" name="button_text" class="form-control" value="{{ old('button_text', $heroSection->button_text) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Button URL</label>
        <input type="text" name="button_url" class="form-control" value="{{ old('button_url', $heroSection->button_url) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $heroSection->sort_order) }}" min="0">
    </div>
    <div class="col-md-6">
        <label class="form-label">Background Image</label>
        @if($heroSection->image)<img src="{{ $heroSection->image_url }}" class="d-block mb-2 rounded" height="80" alt="">@endif
        <input type="file" name="image" class="form-control" accept="image/*">
    </div>
    <div class="col-md-6 form-check mt-4">
        <input type="checkbox" name="status" value="1" class="form-check-input" id="hero-status" @checked(old('status', $heroSection->status ?? true))>
        <label class="form-check-label" for="hero-status">Active</label>
    </div>
</div>
