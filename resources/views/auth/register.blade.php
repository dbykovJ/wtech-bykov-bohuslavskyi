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
                <input class="register-input" id="phone" type="text" name="phone" placeholder="Phone" value="{{ old('phone') }}" required />
                <input class="register-input" id="password" type="password" name="password" placeholder="Password" required />
                <input class="register-input" id="confirm-password" type="password" name="password_confirmation" placeholder="Confirm Password" required />
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
