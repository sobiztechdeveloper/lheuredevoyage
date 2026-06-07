@extends('layouts.admin.app')
@section('title', 'Trashed Legal Pages')
@section('content')
<div class="admin-page-header mb-4 d-flex justify-content-between align-items-center">
    <h1 class="h4 mb-0">Trashed Legal Pages</h1>
    <a href="{{ route('admin.legal-pages.index') }}" class="btn btn-admin-outline btn-sm">Back to list</a>
</div>
<form method="GET" class="mb-3">
    <input type="text" name="q" class="form-control form-control-sm" value="{{ $search }}" placeholder="Search title or slug" style="max-width:320px;">
</form>
<div class="admin-panel-card">
    <table class="table mb-0">
        <thead><tr><th>Title</th><th>Slug</th><th>Deleted</th><th></th></tr></thead>
        <tbody>
            @forelse($pages as $page)
            <tr>
                <td>{{ $page->title }}</td>
                <td><code>{{ $page->slug }}</code></td>
                <td>{{ $page->deleted_at->format(config('date.display_datetime')) }}</td>
                <td class="text-end">
                    <form action="{{ route('admin.legal-pages.restore', $page->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-admin-primary">Restore</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-muted text-center py-4">Trash is empty.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-3">{{ $pages->links() }}</div>
</div>
@endsection
