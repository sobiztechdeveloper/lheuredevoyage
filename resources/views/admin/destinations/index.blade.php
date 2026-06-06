@extends('layouts.admin.app')
@section('title', 'Destinations')
@section('content')
<div class="admin-page-header">
    <div>
        <h1>Destinations</h1>
        <p class="text-muted small mb-0">Airports, cities, ports and regions for all search forms</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.destinations.import') }}" class="btn btn-admin-outline btn-sm"><i class="far fa-file-import me-1"></i> Import CSV</a>
        <a href="{{ route('admin.destinations.trashed') }}" class="btn btn-admin-outline btn-sm"><i class="far fa-trash-can me-1"></i> Trashed</a>
        <a href="{{ route('admin.destinations.create') }}" class="btn btn-admin-primary btn-sm"><i class="far fa-plus me-1"></i> Add New</a>
    </div>
</div>

@if(session('import_errors'))
<div class="alert alert-warning">
    <strong>Import warnings:</strong>
    <ul class="mb-0 small">
        @foreach(session('import_errors') as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="admin-table-card">
    <div class="admin-table-toolbar">
        <form method="GET" class="d-flex flex-wrap gap-2 align-items-center flex-grow-1">
            <div class="search-wrap mb-0">
                <i class="far fa-search"></i>
                <input type="search" name="q" class="form-control" placeholder="Search name, code, city..." value="{{ $search }}">
            </div>
            <select name="type" class="form-select form-select-sm" style="max-width:200px;">
                <option value="">All Types</option>
                @foreach($typeOptions as $value => $label)
                    <option value="{{ $value }}" @selected($filterType === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="status" class="form-select form-select-sm" style="max-width:140px;">
                <option value="">All Status</option>
                <option value="active" @selected($filterStatus === 'active')>Active</option>
                <option value="inactive" @selected($filterStatus === 'inactive')>Inactive</option>
            </select>
            <button type="submit" class="btn btn-admin-primary btn-sm">Filter</button>
        </form>
    </div>

    <form method="POST" action="{{ route('admin.destinations.bulk') }}" id="destinations-bulk-form">
        @csrf
        <div class="d-flex flex-wrap gap-2 px-3 py-2 border-bottom align-items-center">
            <select name="action" class="form-select form-select-sm" style="max-width:180px;" required>
                <option value="">Bulk action</option>
                <option value="activate">Activate</option>
                <option value="deactivate">Deactivate</option>
                <option value="delete">Delete</option>
            </select>
            <button type="submit" class="btn btn-admin-outline btn-sm" onclick="return confirm('Apply bulk action to selected destinations?')">Apply</button>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th style="width:36px;"><input type="checkbox" id="select-all-destinations"></th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Code</th>
                        <th>Location</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td><input type="checkbox" name="ids[]" value="{{ $item->id }}" class="destination-row-check"></td>
                        <td class="fw-semibold">{{ $item->displayLabel() }}</td>
                        <td><span class="badge bg-light text-dark">{{ $typeOptions[$item->type] ?? $item->type }}</span></td>
                        <td><code class="small">{{ $item->code ?: '—' }}</code></td>
                        <td class="small text-muted">
                            @if($item->city){{ $item->city }}, @endif{{ $item->country ?: '—' }}
                        </td>
                        <td>{{ $item->sort_order }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.destinations.toggle-status', $item) }}" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="badge-status border-0 {{ $item->is_active ? 'badge-status-active' : 'badge-status-inactive' }}">
                                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.destinations.edit', $item) }}" class="btn btn-sm btn-admin-outline">Edit</a>
                            <form action="{{ route('admin.destinations.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this destination?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-admin-outline text-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-5 text-muted">No destinations yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    @if($items->hasPages())
    <div class="admin-table-footer">{{ $items->links() }}</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.getElementById('select-all-destinations')?.addEventListener('change', function () {
    document.querySelectorAll('.destination-row-check').forEach(function (el) {
        el.checked = this.checked;
    }, this);
});
</script>
@endpush
