<div class="admin-form-card">
    <div class="admin-form-card-header">
        <h2><i class="far fa-circle-question"></i> FAQ Details</h2>
    </div>
    <div class="admin-form-card-body">
        <div class="row g-3">
            <div class="col-12">
                <label class="admin-field-label">Question <span class="required">*</span></label>
                <input type="text" name="question" class="form-control @error('question') is-invalid @enderror" value="{{ old('question', $faq->question) }}" required>
                @error('question')<div class="admin-field-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="admin-field-label">Answer <span class="required">*</span></label>
                <textarea name="answer" class="form-control @error('answer') is-invalid @enderror" rows="5" required>{{ old('answer', $faq->answer) }}</textarea>
                @error('answer')<div class="admin-field-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="admin-field-label">Sort Order</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $faq->sort_order) }}" min="0">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <div class="admin-switch w-100">
                    <input type="checkbox" name="status" value="1" class="form-check-input" id="faq-status" @checked(old('status', $faq->status ?? true))>
                    <label for="faq-status" class="form-check-label fw-semibold">Active</label>
                </div>
            </div>
        </div>
    </div>
</div>
