

@extends('layouts.app')
@section('body-class', 'dashboard-page')
@section('content')

<div class="login-area py-120">
    <div class="container">
        <div class="col-md-5 mx-auto">
            <div class="login-form">

                <div class="login-header">
                    <img src="{{ asset('assets/img/logo/logo-dark.png') }}" alt="">
                    <p>Login with your account</p>
                </div>

                @if(session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label>Email Address</label>
                        <div class="form-group-icon">
                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   value="{{ old('email') }}"
                                   placeholder="Your Email"
                                   required>

                            <i class="far fa-envelope"></i>
                        </div>

                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Password</label>

                        <div class="form-group-icon">
                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   placeholder="Your Password"
                                   required>

                            <i class="far fa-lock"></i>
                        </div>

                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between mb-3">

                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="remember"
                                   id="remember">

                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="forgot-pass">
                                Forgot Password?
                            </a>
                        @endif

                    </div>

                    <button type="submit" class="theme-btn">
                        <i class="far fa-sign-in"></i> Login
                    </button>

                </form>

                <div class="login-footer">
                    <p>
                        Don't have an account?
                        <a href="{{ route('register') }}">Sign Up</a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
