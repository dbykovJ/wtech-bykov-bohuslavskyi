@extends('layouts.app')

@section('title', 'Реєстрація — Look of Today')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
@endpush

@section('content')
<main class="auth-main">
    <div class="register-box">
        <h1 class="auth-title">Реєстрація</h1>

        <form class="auth-form" method="POST" action="#">
            @csrf
            <div class="auth-fields">
                <input class="register-input" id="name" type="text" name="name" placeholder="Повне ім'я" value="{{ old('name') }}" required autofocus />
                @error('name')
                <span>{{ $message }}</span>
                @enderror
                <input class="register-input" id="email" type="email" name="email" placeholder="Електронна пошта" value="{{ old('email') }}" required />
                @error('email')
                <span>{{ $message }}</span>
                @enderror
                <input class="register-input" id="password" type="password" name="password" placeholder="Пароль" required />
                @error('Password')
                <span>{{ $message }}</span>
                @enderror
                <input class="register-input" id="confirm-password" type="password" name="password_confirmation" placeholder="Підтвердьте пароль" required />
                @error('password_confirmation')
                <span>{{ $message }}</span>
                @enderror
            </div>

            <div class="register-actions">
                <button class="btn-register" type="submit">Зареєструватися</button>
                <button class="btn-signin" type="button" onclick="window.location.href='{{ route('login') }}'">
                    Вже є акаунт? Увійти
                </button>
            </div>
        </form>
    </div>
</main>
@endsection
