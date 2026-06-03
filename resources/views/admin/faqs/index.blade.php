@extends('layouts.admin.app')
@section('title', 'FAQs')
@section('content')
<div class="admin-page-header">
    <div><h1>FAQs</h1><p class="text-muted small mb-0">Manage frequently asked questions</p></div>
    <a href="{{ route('admin.faqs.create') }}" class="btn btn-admin-primary btn-sm"><i class="far fa-plus me-1"></i> Add FAQ</a>
</div>
<div class="admin-table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Question</th><th>Order</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @foreach($faqs as $faq)
                <tr>
                    <td class="fw-semibold">{{ Str::limit($faq->question, 60) }}</td>
                    <td>{{ $faq->sort_order }}</td>
                    <td><span class="badge-status {{ $faq->status ? 'badge-status-active' : 'badge-status-inactive' }}">{{ $faq->status ? 'Active' : 'Inactive' }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-sm btn-admin-outline">Edit</a>
                        <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger ms-1">Delete</button></form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($faqs->hasPages())<div class="admin-table-footer">{{ $faqs->links() }}</div>@endif
</div>
@endsection
