@extends('layouts.app')
@section('body-class', 'dashboard-page')
@section('content')

<div class="login-area py-120">
    <div class="container">
        <div class="col-md-5 mx-auto">
            <div class="login-form">

                <div class="login-header">
                    <img src="{{ asset('assets/img/logo/logo-dark.png') }}" alt="">
                    <p>Create your account</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-group">
                        <label>Full Name</label>
                        <div class="form-group-icon">
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ old('name') }}"
                                   placeholder="Your Name"
                                   required>
                            <i class="far fa-user"></i>
                        </div>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

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

                    <div class="form-group">
                        <label>Confirm Password</label>
                        <div class="form-group-icon">
                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control"
                                   placeholder="Confirm Password"
                                   required>
                            <i class="far fa-lock"></i>
                        </div>
                    </div>

                    <div class="form-check form-group">
                        <input class="form-check-input"
                               type="checkbox"
                               id="agree"
                               required>

                        <label class="form-check-label" for="agree">
                            I agree with the Terms Of Service.
                        </label>
                    </div>

                    <button type="submit" class="theme-btn">
                        <i class="far fa-paper-plane"></i> Sign Up
                    </button>

                </form>

                <div class="login-footer">
                    <p>
                        Already have an account?
                        <a href="{{ route('login') }}">Login</a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection