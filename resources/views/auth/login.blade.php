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
                <input class="auth-input" id="password" type="password" name="password" placeholder="Password" required />
            </div>

            <button class="auth-submit" type="submit">Login</button>
        </form>
    </div>

    <div class="auth-footer-text">
        Don't have an account? <a href="{{ route('register') }}">Register.</a>
    </div>
</main>
@endsection
