<div class="admin-form-card">
    <div class="admin-form-card-header">
        <h2><i class="far fa-quote-left"></i> Testimonial</h2>
    </div>
    <div class="admin-form-card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="admin-field-label">Name <span class="required">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $testimonial->name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="admin-field-label">Designation</label>
                <input type="text" name="designation" class="form-control" value="{{ old('designation', $testimonial->designation) }}">
            </div>
            <div class="col-12">
                <label class="admin-field-label">Review <span class="required">*</span></label>
                <textarea name="review" class="form-control" rows="4" required>{{ old('review', $testimonial->review) }}</textarea>
            </div>
            <div class="col-md-4">
                <label class="admin-field-label">Rating <span class="required">*</span></label>
                <select name="rating" class="form-select">
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" @selected(old('rating', $testimonial->rating ?? 5) == $i)>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <div class="admin-switch w-100">
                    <input type="checkbox" name="status" value="1" class="form-check-input" id="t-status" @checked(old('status', $testimonial->status ?? true))>
                    <label for="t-status" class="form-check-label fw-semibold">Active</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="admin-form-card">
    <div class="admin-form-card-header">
        <h2><i class="far fa-image"></i> Photo</h2>
    </div>
    <div class="admin-form-card-body">
        <div class="row g-4">
            @include('admin.partials.single-image-upload', [
                'name' => 'image',
                'label' => 'Customer Photo',
                'currentUrl' => ($testimonial->image ?? null) ? $testimonial->image_url : null,
                'class' => 'col-md-6',
            ])
        </div>
    </div>
</div>
