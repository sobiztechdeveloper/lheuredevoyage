@extends('layouts.admin.app')

@section('title', 'Hero Sections')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h4 mb-0">Hero Sections</h2>
    <a href="{{ route('admin.hero-sections.create') }}" class="btn btn-primary btn-sm">Add Hero</a>
</div>

<form method="POST" action="{{ route('admin.hero-sections.reorder') }}" id="hero-sort-form">
    @csrf
    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th width="40"></th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Order</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="hero-sortable">
                    @forelse($heroSections as $hero)
                        <tr data-id="{{ $hero->id }}">
                            <td><span class="drag-handle text-muted" style="cursor:grab">☰</span></td>
                            <td>{{ $hero->title }}</td>
                            <td><span class="badge bg-{{ $hero->status ? 'success' : 'secondary' }}">{{ $hero->status ? 'Active' : 'Inactive' }}</span></td>
                            <td>{{ $hero->sort_order }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.hero-sections.edit', $hero) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('admin.hero-sections.destroy', $hero) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4">No hero sections yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($heroSections->isNotEmpty())
        <button type="submit" class="btn btn-outline-secondary btn-sm mt-2">Save Order</button>
        <p class="text-muted small mt-1">Drag rows to reorder, then click Save Order.</p>
    @endif
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tbody = document.getElementById('hero-sortable');
    if (!tbody) return;
    const form = document.getElementById('hero-sort-form');
    Sortable.create(tbody, {
        handle: '.drag-handle',
        animation: 150,
        onEnd: syncOrderInputs
    });
    function syncOrderInputs() {
        const ids = [...tbody.querySelectorAll('tr[data-id]')].map(row => row.dataset.id);
        form.querySelectorAll('input[name="order[]"]').forEach(el => el.remove());
        ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'order[]';
            input.value = id;
            form.appendChild(input);
        });
    }
    syncOrderInputs();
    form.addEventListener('submit', syncOrderInputs);
});
</script>
@endpush
