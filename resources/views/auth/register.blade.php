@extends('layouts.app')

@section('title', 'Register — SUPERSELL')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
@endpush

@section('content')
<main class="auth-main">
    <div class="register-box">
        <h1 class="auth-title">Register</h1>

        <form class="auth-form" method="POST" action="#">
            @csrf
            <div class="auth-fields">
                <input class="register-input" id="name" type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" required autofocus />
                @error('name')
                <span>{{ $message }}</span>
                @enderror
                <input class="register-input" id="email" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required />
                @error('email')
                <span>{{ $message }}</span>
                @enderror
                <input class="register-input" id="password" type="password" name="password" placeholder="Password" required />
                @error('Password')
                <span>{{ $message }}</span>
                @enderror
                <input class="register-input" id="confirm-password" type="password" name="password_confirmation" placeholder="Confirm Password" required />
                @error('password_confirmation')
                <span>{{ $message }}</span>
                @enderror
            </div>

            <div class="register-actions">
                <button class="btn-register" type="submit">Register</button>
                <button class="btn-signin" type="button" onclick="window.location.href='{{ route('login') }}'">
                    Have an account? Sign In
                </button>
            </div>
        </form>
    </div>
</main>
@endsection
