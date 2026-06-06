@php
    $unreadInquiries = \App\Models\Contact::query()->whereNull('read_at')->count();
    $user = auth('admin')->user();
    $initials = collect(explode(' ', $user->name ?? 'A'))->map(fn ($w) => strtoupper(substr($w, 0, 1)))->take(2)->join('');
@endphp
<header class="admin-topbar">
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="admin-icon-btn d-lg-none" id="adminSidebarToggle" aria-label="Open menu">
            <i class="far fa-bars"></i>
        </button>
        <button type="button" class="admin-icon-btn d-none d-lg-inline-flex" id="adminSidebarCollapse" aria-label="Collapse sidebar">
            <i class="far fa-angles-left"></i>
        </button>
        <h1 class="admin-topbar-title admin-hide-mobile">@yield('title', 'Admin')</h1>
    </div>

    <div class="admin-topbar-actions">
        <a href="{{ route('home') }}" target="_blank" class="admin-icon-btn admin-hide-mobile" title="View website">
            <i class="far fa-arrow-up-right-from-square"></i>
        </a>
        <a href="{{ route('admin.inquiries.index', ['unread' => 1]) }}" class="admin-icon-btn" title="Notifications">
            <i class="far fa-bell"></i>
            @if($unreadInquiries > 0)
                <span class="badge-dot" aria-hidden="true"></span>
            @endif
        </a>
        <div class="dropdown admin-user-menu">
            <button class="dropdown-toggle btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="admin-avatar">{{ $initials }}</span>
                <span class="admin-hide-mobile fw-semibold">{{ $user->name }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                <li><h6 class="dropdown-header">{{ $user->email }}</h6></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="far fa-gauge-high me-2"></i>Dashboard</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.settings.edit') }}"><i class="far fa-gear me-2"></i>Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i class="far fa-right-from-bracket me-2"></i>Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
