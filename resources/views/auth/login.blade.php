@extends('layouts.app')

@section('title', 'Вхід — SUPERSELL')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
@endpush

@section('content')
<main class="auth-main">
    <div class="login-box">
        <h1 class="auth-title">Вхід</h1>

        <form class="auth-form" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="auth-fields">
                <input class="auth-input" id="email" type="email" name="email" placeholder="Електронна пошта" value="{{ old('email') }}" required autofocus />

                @error('email')
                <div class="error-bubble">{{ $message }}</div>
                @enderror

                <input class="auth-input" id="Password" type="Password" name="Password" placeholder="Пароль" required />

                @error('Password')
                <div class="error-bubble">{{ $message }}</div>
                @enderror
            </div>


            <div>
                <input type="checkbox" name="remember" id="remember" />
                <label for="remember">Запам'ятати мене</label>
            </div>

            <button class="auth-submit" type="submit">Увійти</button>
        </form>
    </div>

    <div class="auth-footer-text">
        Немає акаунта? <a href="{{ route('register') }}">Зареєструйтеся.</a>
    </div>
</main>
@endsection
