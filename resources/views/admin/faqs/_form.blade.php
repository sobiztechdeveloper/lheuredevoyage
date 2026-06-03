<div class="row g-3">
    <div class="col-12"><label class="form-label">Question *</label><input type="text" name="question" class="form-control" value="{{ old('question', $faq->question) }}" required></div>
    <div class="col-12"><label class="form-label">Answer *</label><textarea name="answer" class="form-control" rows="5" required>{{ old('answer', $faq->answer) }}</textarea></div>
    <div class="col-md-4"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $faq->sort_order) }}" min="0"></div>
    <div class="col-md-4 form-check mt-4"><input type="checkbox" name="status" value="1" class="form-check-input" id="faq-status" @checked(old('status', $faq->status ?? true))><label for="faq-status" class="form-check-label">Active</label></div>
</div>
