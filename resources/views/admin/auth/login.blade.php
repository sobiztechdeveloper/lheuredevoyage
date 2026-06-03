<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login — L'Heure De Voyage</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <style>
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #0f172a; }
        .admin-login-card { max-width: 420px; width: 100%; background: #fff; border-radius: 12px; padding: 2rem; box-shadow: 0 20px 50px rgba(0,0,0,.3); }
        .admin-login-brand { font-weight: 700; color: #0f172a; font-size: 1.25rem; }
        .admin-login-sub { color: #64748b; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="admin-login-card">
        <div class="text-center mb-4">
            <div class="admin-login-brand">L'Heure De Voyage</div>
            <div class="admin-login-sub">Administration Panel</div>
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="remember" value="1" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">Sign in to Admin</button>
        </form>

        <p class="text-center text-muted small mt-4 mb-0">
            <a href="{{ route('home') }}">← Back to website</a>
            <span class="mx-1">·</span>
            Customer? <a href="{{ route('login') }}">Customer login</a>
        </p>
    </div>
</body>
</html>
