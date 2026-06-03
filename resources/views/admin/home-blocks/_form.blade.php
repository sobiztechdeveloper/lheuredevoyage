<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Section *</label>
        <select name="section" class="form-select" required>
            @foreach($sections as $key => $label)
                <option value="{{ $key }}" @selected(old('section', $block->section) === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Sort order</label>
        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $block->sort_order) }}" min="0">
    </div>
    <div class="col-md-4 form-check mt-4">
        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', $block->is_active ?? true))>
        <label class="form-check-label" for="is_active">Active</label>
    </div>
    <div class="col-md-6">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $block->title) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Subtitle</label>
        <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $block->subtitle) }}">
    </div>
    <div class="col-12">
        <label class="form-label">Content</label>
        <textarea name="content" class="form-control" rows="3">{{ old('content', $block->content) }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Image</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        <input type="text" name="image_path" class="form-control mt-2" placeholder="Or path: assets/img/..." value="{{ old('image_path', str_starts_with($block->image ?? '', 'assets/') ? $block->image : '') }}">
        @if($block->image_url)<img src="{{ $block->image_url }}" class="mt-2 rounded" style="max-height:60px" alt="">@endif
    </div>
    <div class="col-md-6">
        <label class="form-label">Icon</label>
        <input type="file" name="icon" class="form-control" accept="image/*">
        <input type="text" name="icon_path" class="form-control mt-2" placeholder="Or path: assets/img/icon/..." value="{{ old('icon_path', str_starts_with($block->icon ?? '', 'assets/') ? $block->icon : '') }}">
        @if($block->icon_url)<img src="{{ $block->icon_url }}" class="mt-2 rounded" style="max-height:40px" alt="">@endif
    </div>
    <div class="col-md-4">
        <label class="form-label">Link</label>
        <input type="text" name="link" class="form-control" value="{{ old('link', $block->link) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Value (counters)</label>
        <input type="text" name="value" class="form-control" value="{{ old('value', $block->value) }}">
    </div>
</div>
