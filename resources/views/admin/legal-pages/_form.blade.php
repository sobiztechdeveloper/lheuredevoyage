@php $page = $page ?? null; @endphp
<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" name="title" id="legal-title" class="form-control" value="{{ old('title', $page->title ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" id="legal-slug" class="form-control" value="{{ old('slug', $page->slug ?? '') }}" placeholder="auto-from-title">
        <div class="form-text">URL: /legal/<span id="slug-preview">{{ old('slug', $page->slug ?? 'your-slug') }}</span></div>
    </div>
    <div class="col-12">
        <label class="form-label">Summary</label>
        <textarea name="summary" class="form-control" rows="2">{{ old('summary', $page->summary ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Content <span class="text-danger">*</span></label>
        <textarea name="content" id="legal-content" class="form-control">{{ old('content', $page->content ?? '') }}</textarea>
        <div class="form-text">Placeholders: [COMPANY NAME], [COMPANY ADDRESS], [COMPANY EMAIL], [COMPANY PHONE], [VAT NUMBER], [REGISTRATION NUMBER], [BUSINESS HOURS]</div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Meta Title</label>
        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $page->meta_title ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Meta Description</label>
        <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description', $page->meta_description ?? '') }}" maxlength="500">
    </div>
    <div class="col-md-3">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" min="0" value="{{ old('sort_order', $page->sort_order ?? 0) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Publish Date</label>
        <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', optional($page->published_at)->format('Y-m-d\TH:i')) }}">
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <div class="form-check mb-2">
            <input type="hidden" name="is_active" value="0">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $page->is_active ?? true))>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tinymce@7.6.0/skins/ui/oxide/skin.min.css">
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@7.6.0/tinymce.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#legal-content',
            height: 480,
            menubar: false,
            plugins: 'lists link table code autolink',
            toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright | bullist numlist | link table | removeformat code',
            content_style: 'body{font-family:Inter,Arial,sans-serif;font-size:15px;line-height:1.6;}',
            branding: false,
            promotion: false,
        });
    }
    var titleInput = document.getElementById('legal-title');
    var slugInput = document.getElementById('legal-slug');
    var slugPreview = document.getElementById('slug-preview');
    if (titleInput && slugInput) {
        titleInput.addEventListener('blur', function () {
            if (!slugInput.value.trim()) {
                slugInput.value = titleInput.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
            }
            if (slugPreview) slugPreview.textContent = slugInput.value || 'your-slug';
        });
        slugInput.addEventListener('input', function () {
            if (slugPreview) slugPreview.textContent = slugInput.value || 'your-slug';
        });
    }
    document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function () {
            if (typeof tinymce !== 'undefined') {
                tinymce.triggerSave();
            }
        });
    });
});
</script>
@endpush
