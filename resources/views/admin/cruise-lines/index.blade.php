@extends('layouts.admin.app')
@section('title', 'Cruise Lines')
@section('content')
<div class="admin-page-header">
    <div>
        <h1>Cruise Lines</h1>
        <p class="text-muted small mb-0">Lines shown in cruise search dropdown</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.cruise-lines.trashed') }}" class="btn btn-admin-outline btn-sm"><i class="far fa-trash-can me-1"></i> Trashed</a>
        <a href="{{ route('admin.cruise-lines.create') }}" class="btn btn-admin-primary btn-sm"><i class="far fa-plus me-1"></i> Add New</a>
    </div>
</div>

<div class="admin-table-card">
    <div class="admin-table-toolbar">
        <form method="GET" class="d-flex flex-wrap gap-2 align-items-center flex-grow-1">
            <div class="search-wrap mb-0">
                <i class="far fa-search"></i>
                <input type="search" name="q" class="form-control" placeholder="Search..." value="{{ $search }}">
            </div>
            <select name="status" class="form-select form-select-sm" style="max-width:140px;">
                <option value="">All Status</option>
                <option value="active" @selected($filterStatus === 'active')>Active</option>
                <option value="inactive" @selected($filterStatus === 'inactive')>Inactive</option>
            </select>
            <button type="submit" class="btn btn-admin-primary btn-sm">Filter</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr><th>Name</th><th>Slug</th><th>Order</th><th>Status</th><th class="text-end">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td class="fw-semibold">
                        @if($item->logo_url)
                            <img src="{{ $item->logo_url }}" alt="" width="28" height="28" class="rounded me-2 object-fit-contain">
                        @endif
                        {{ $item->name }}
                    </td>
                    <td><code class="small">{{ $item->slug }}</code></td>
                    <td>{{ $item->sort_order }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.cruise-lines.toggle-status', $item) }}" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="badge-status border-0 {{ $item->is_active ? 'badge-status-active' : 'badge-status-inactive' }}">
                                {{ $item->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.cruise-lines.edit', $item) }}" class="btn btn-sm btn-admin-outline">Edit</a>
                        <form action="{{ route('admin.cruise-lines.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this cruise line?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-admin-outline text-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-5 text-muted">No cruise lines yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
    <div class="admin-table-footer">{{ $items->links() }}</div>
    @endif
</div>
@endsection
