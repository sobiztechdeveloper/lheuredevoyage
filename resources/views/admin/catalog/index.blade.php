@extends('layouts.admin.app')

@section('title', $label.'s')

@section('content')
<div class="admin-page-header">
    <div>
        <h1>{{ $label }}s</h1>
        <p class="text-muted small mb-0">Manage catalog listings and availability</p>
    </div>
    <a href="{{ route('admin.'.$resource.'.create') }}" class="btn btn-admin-primary">
        <i class="far fa-plus me-1"></i> Add {{ $label }}
    </a>
</div>

<div class="admin-table-card">
    <div class="admin-table-toolbar">
        <form method="GET" class="d-flex flex-wrap gap-2 align-items-center flex-grow-1">
            <div class="search-wrap mb-0">
                <i class="far fa-search"></i>
                <input type="search" name="q" class="form-control" placeholder="Search name, slug, location..." value="{{ $search }}">
            </div>
            <button type="submit" class="btn btn-admin-primary btn-sm">Search</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    @if($resource === 'flights')
                    <th>Airline</th>
                    <th>Route</th>
                    @endif
                    <th>Slug</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Featured</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td class="fw-semibold">{{ $item->name }}</td>
                    @if($resource === 'flights')
                    <td>{{ $item->airline ?: '—' }}</td>
                    <td class="small">{{ $item->routeLabel() }}</td>
                    @endif
                    <td><code class="small">{{ $item->slug }}</code></td>
                    <td>{{ $item->formatted_price }}</td>
                    <td>
                        <span class="badge-status {{ $item->is_active ? 'badge-status-active' : 'badge-status-inactive' }}">
                            {{ $item->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        @if($item->is_featured)
                            <span class="badge-status badge-status-featured">Featured</span>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="dropdown admin-actions-dropdown">
                            <button class="btn btn-sm btn-admin-outline dropdown-toggle" type="button" data-bs-toggle="dropdown">Actions</button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><a class="dropdown-item" href="{{ route('admin.'.$resource.'.edit', $item) }}"><i class="far fa-pen me-2"></i>Edit</a></li>
                                @if($resource === 'hotels')
                                <li><a class="dropdown-item" href="{{ route('admin.hotels.rooms.index', $item) }}"><i class="far fa-bed me-2"></i>Manage Rooms</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('admin.'.$resource.'.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this item?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger"><i class="far fa-trash me-2"></i>Delete</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $resource === 'flights' ? 8 : 6 }}" class="text-center py-5 text-muted">
                        <i class="far fa-folder-open fa-2x mb-2 d-block"></i>
                        No records yet. <a href="{{ route('admin.'.$resource.'.create') }}">Create your first {{ strtolower($label) }}</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
    <div class="admin-table-footer">
        {{ $items->links() }}
    </div>
    @endif
</div>
@endsection
