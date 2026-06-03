@extends('layouts.admin.app')
@section('title', $config['label'])
@section('content')
<div class="admin-page-header">
    <div>
        <h1>{{ $config['label'] }}</h1>
        <p class="text-muted small mb-0">Filter options used across catalog and public search</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.master-data.'.$type.'.trashed') }}" class="btn btn-admin-outline btn-sm"><i class="far fa-trash-can me-1"></i> Trashed</a>
        <a href="{{ route('admin.master-data.'.$type.'.create') }}" class="btn btn-admin-primary btn-sm"><i class="far fa-plus me-1"></i> Add New</a>
    </div>
</div>

<div class="admin-table-card">
    <div class="admin-table-toolbar">
        <form method="GET" class="d-flex flex-wrap gap-2 align-items-center flex-grow-1">
            <div class="search-wrap mb-0">
                <i class="far fa-search"></i>
                <input type="search" name="q" class="form-control" placeholder="Search..." value="{{ $search }}">
            </div>
            <button type="submit" class="btn btn-admin-primary btn-sm">Search</button>
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
                        @if($item->icon)<i class="{{ $item->icon }} me-2 text-muted"></i>@endif
                        {{ $item->name }}
                    </td>
                    <td><code class="small">{{ $item->slug }}</code></td>
                    <td>{{ $item->sort_order }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.master-data.'.$type.'.toggle-status', $item) }}" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="badge-status border-0 {{ $item->is_active ? 'badge-status-active' : 'badge-status-inactive' }}">
                                {{ $item->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </td>
                    <td class="text-end">
                        <div class="dropdown admin-actions-dropdown">
                            <button class="btn btn-sm btn-admin-outline dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><a class="dropdown-item" href="{{ route('admin.master-data.'.$type.'.edit', $item) }}"><i class="far fa-pen me-2"></i>Edit</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('admin.master-data.'.$type.'.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger"><i class="far fa-trash me-2"></i>Delete</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-5 text-muted">No records yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
    <div class="admin-table-footer">{{ $items->links() }}</div>
    @endif
</div>
@endsection
