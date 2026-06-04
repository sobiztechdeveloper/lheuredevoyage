@extends('layouts.admin.app')
@section('title', 'Legal Pages')
@section('content')
<div class="admin-page-header mb-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div>
        <h1 class="h4 mb-0">Legal Pages</h1>
        <p class="text-muted small mb-0">Manage terms, privacy, cookies and compliance content.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.legal-pages.trashed') }}" class="btn btn-admin-outline btn-sm">Trash</a>
        <a href="{{ route('admin.legal-pages.create') }}" class="btn btn-admin-primary btn-sm"><i class="far fa-plus me-1"></i> Add Page</a>
    </div>
</div>

<form method="GET" class="admin-panel-card mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-md-5">
            <label class="form-label small">Search</label>
            <input type="text" name="q" class="form-control form-control-sm" value="{{ $search }}" placeholder="Title or slug">
        </div>
        <div class="col-md-3">
            <label class="form-label small">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All</option>
                <option value="active" @selected($filterStatus === 'active')>Active</option>
                <option value="inactive" @selected($filterStatus === 'inactive')>Inactive</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-admin-primary btn-sm w-100">Filter</button>
        </div>
    </div>
</form>

<div class="admin-panel-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th>Updated</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                <tr>
                    <td><strong>{{ $page->title }}</strong></td>
                    <td><code class="small">{{ $page->slug }}</code></td>
                    <td>
                        @if($page->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td class="small">{{ $page->published_at?->format('M d, Y') ?? '—' }}</td>
                    <td class="small">{{ $page->updated_at->format('M d, Y H:i') }}</td>
                    <td class="text-end">
                        <a href="{{ $page->publicUrl() }}" class="btn btn-sm btn-admin-outline" target="_blank" rel="noopener">View</a>
                        <a href="{{ route('admin.legal-pages.edit', $page) }}" class="btn btn-sm btn-admin-primary">Edit</a>
                        <form action="{{ route('admin.legal-pages.destroy', $page) }}" method="POST" class="d-inline" onsubmit="return confirm('Move this page to trash?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No legal pages found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $pages->links() }}</div>
</div>
@endsection
