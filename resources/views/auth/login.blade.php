@extends('layouts.app')

@section('title', 'Login — SUPERSELL')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
@endpush

@section('content')
<main class="auth-main">
    <div class="login-box">
        <h1 class="auth-title">Login</h1>

        <form class="auth-form" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="auth-fields">
                <input class="auth-input" id="email" type="email" name="email" placeholder="Username" value="{{ old('email') }}" required autofocus />

                @error('email')
                <div class="error-bubble">{{ $message }}</div>
                @enderror

                <input class="auth-input" id="Password" type="Password" name="Password" placeholder="Password" required />

                @error('Password')
                <div class="error-bubble">{{ $message }}</div>
                @enderror
            </div>


            <div>
                <input type="checkbox" name="remember" id="remember" />
                <label for="remember">Remember me</label>
            </div>

            <button class="auth-submit" type="submit">Login</button>
        </form>
    </div>

    <div class="auth-footer-text">
        Don't have an account? <a href="{{ route('register') }}">Register.</a>
    </div>
</main>
@endsection
