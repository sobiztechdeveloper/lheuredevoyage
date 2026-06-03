<div class="fbw-passenger-block fbw-passenger-block--extra" data-passenger-index="INDEX" data-passenger-label="Additional Passenger" data-extra-passenger="1">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 fbw-extra-passenger-label">Additional Passenger</h5>
        <button type="button" class="btn btn-sm btn-outline-danger fbw-remove-passenger" aria-label="Remove passenger">
            <i class="far fa-times"></i> Remove
        </button>
    </div>
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label class="form-label">Passenger Type <span class="text-danger">*</span></label>
            <select name="passengers[INDEX][passenger_type]" class="form-select" required>
                <option value="adult">Adult</option>
                <option value="child">Child</option>
                <option value="infant">Infant</option>
            </select>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Title <span class="text-danger">*</span></label>
            <select name="passengers[INDEX][title]" class="form-select" required>
                <option value="">Select</option>
                <option value="Mr">Mr</option>
                <option value="Mrs">Mrs</option>
                <option value="Ms">Ms</option>
                <option value="Master">Master</option>
                <option value="Miss">Miss</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">First Name <span class="text-danger">*</span></label>
            <input type="text" name="passengers[INDEX][first_name]" class="form-control" required>
        </div>
        <div class="col-md-5">
            <label class="form-label">Last Name <span class="text-danger">*</span></label>
            <input type="text" name="passengers[INDEX][last_name]" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
            <input type="date" name="passengers[INDEX][date_of_birth]" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Gender <span class="text-danger">*</span></label>
            <select name="passengers[INDEX][gender]" class="form-select" required>
                <option value="">Select</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Nationality <span class="text-danger">*</span></label>
            <input type="text" name="passengers[INDEX][nationality]" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Passport Number <span class="text-danger">*</span></label>
            <input type="text" name="passengers[INDEX][passport_number]" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Passport Expiry <span class="text-danger">*</span></label>
            <input type="date" name="passengers[INDEX][passport_expiry]" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Passport Issuing Country</label>
            <input type="text" name="passengers[INDEX][passport_country]" class="form-control">
        </div>
        <div class="col-12">
            <label class="form-label">Passport Copy</label>
            <input type="file" name="passengers[INDEX][passport_file]" class="form-control" accept=".pdf,.jpg,.jpeg,.png,image/*,application/pdf">
            <small class="text-muted">PDF, JPG, JPEG or PNG — max 5MB</small>
        </div>
    </div>
</div>
