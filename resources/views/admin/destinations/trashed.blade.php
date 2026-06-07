@extends('layouts.admin.app')
@section('title', 'Trashed Destinations')
@section('content')
<div class="admin-page-header">
    <div>
        <h1>Trashed Destinations</h1>
        <p class="text-muted small mb-0">Restore soft-deleted destinations</p>
    </div>
    <a href="{{ route('admin.destinations.index') }}" class="btn btn-admin-outline btn-sm">Back to list</a>
</div>

<div class="admin-table-card">
    <div class="admin-table-toolbar">
        <form method="GET" class="d-flex flex-wrap gap-2 align-items-center flex-grow-1">
            <div class="search-wrap mb-0">
                <i class="far fa-search"></i>
                <input type="search" name="q" class="form-control" placeholder="Search..." value="{{ $search }}">
            </div>
            <select name="type" class="form-select form-select-sm" style="max-width:200px;">
                <option value="">All Types</option>
                @foreach($typeOptions as $value => $label)
                    <option value="{{ $value }}" @selected($filterType === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-admin-primary btn-sm">Search</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr><th>Name</th><th>Type</th><th>Code</th><th>Deleted</th><th class="text-end">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $typeOptions[$item->type] ?? $item->type }}</td>
                    <td><code>{{ $item->code ?: '—' }}</code></td>
                    <td class="small text-muted">{{ $item->deleted_at?->format(config('date.display_datetime')) }}</td>
                    <td class="text-end">
                        <form method="POST" action="{{ route('admin.destinations.restore', $item->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-admin-primary">Restore</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-5 text-muted">Trash is empty.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
    <div class="admin-table-footer">{{ $items->links() }}</div>
    @endif
</div>
@endsection
