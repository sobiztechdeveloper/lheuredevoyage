<div class="row g-3">
    <div class="col-md-6"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" value="{{ old('name', $testimonial->name) }}" required></div>
    <div class="col-md-6"><label class="form-label">Designation</label><input type="text" name="designation" class="form-control" value="{{ old('designation', $testimonial->designation) }}"></div>
    <div class="col-12"><label class="form-label">Review *</label><textarea name="review" class="form-control" rows="4" required>{{ old('review', $testimonial->review) }}</textarea></div>
    <div class="col-md-4"><label class="form-label">Rating *</label><select name="rating" class="form-control">@for($i=1;$i<=5;$i++)<option value="{{ $i }}" @selected(old('rating', $testimonial->rating ?? 5)==$i)>{{ $i }}</option>@endfor</select></div>
    <div class="col-md-4">
        <label class="form-label">Photo</label>
        @if($testimonial->image ?? null)
            <img src="{{ $testimonial->image_url }}" height="60" class="d-block mb-2 rounded border" alt="{{ $testimonial->name }}">
        @endif
        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp,image/gif">
        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 form-check mt-4"><input type="checkbox" name="status" value="1" class="form-check-input" id="t-status" @checked(old('status', $testimonial->status ?? true))><label for="t-status" class="form-check-label">Active</label></div>
</div>
