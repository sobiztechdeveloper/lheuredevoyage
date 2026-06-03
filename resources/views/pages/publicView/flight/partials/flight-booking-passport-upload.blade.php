<div class="col-12">
    <label class="form-label">Passport Copy</label>
    <input type="file" name="passengers[{{ $index }}][passport_file]" class="form-control @error($prefix.'.passport_file') is-invalid @enderror"
        accept=".pdf,.jpg,.jpeg,.png,image/*,application/pdf">
    <small class="text-muted">PDF, JPG, JPEG or PNG — max 5MB</small>
    @error($prefix.'.passport_file')<span class="fbw-invalid-inline d-block">{{ $message }}</span>@enderror
</div>
