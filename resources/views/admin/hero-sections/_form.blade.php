<div class="admin-form-card">
    <div class="admin-form-card-header">
        <h2><i class="far fa-panorama"></i> Hero Content</h2>
    </div>
    <div class="admin-form-card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="admin-field-label">Title <span class="required">*</span></label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $heroSection->title) }}" required>
            </div>
            <div class="col-md-6">
                <label class="admin-field-label">Subtitle</label>
                <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $heroSection->subtitle) }}">
            </div>
            <div class="col-md-4">
                <label class="admin-field-label">Button Text</label>
                <input type="text" name="button_text" class="form-control" value="{{ old('button_text', $heroSection->button_text) }}">
            </div>
            <div class="col-md-4">
                <label class="admin-field-label">Button URL</label>
                <input type="text" name="button_url" class="form-control" value="{{ old('button_url', $heroSection->button_url) }}">
            </div>
            <div class="col-md-4">
                <label class="admin-field-label">Sort Order</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $heroSection->sort_order) }}" min="0">
            </div>
        </div>
    </div>
</div>

<div class="admin-form-card">
    <div class="admin-form-card-header">
        <h2><i class="far fa-image"></i> Background & Publishing</h2>
    </div>
    <div class="admin-form-card-body">
        <div class="row g-4">
            @include('admin.partials.single-image-upload', [
                'name' => 'image',
                'label' => 'Background Image',
                'currentUrl' => $heroSection->image ? $heroSection->image_url : null,
                'class' => 'col-md-6',
            ])
            <div class="col-md-6 d-flex align-items-end">
                <div class="admin-switch w-100">
                    <input type="checkbox" name="status" value="1" class="form-check-input" id="hero-status" @checked(old('status', $heroSection->status ?? true))>
                    <label class="form-check-label fw-semibold" for="hero-status">Active</label>
                </div>
            </div>
        </div>
    </div>
</div>
