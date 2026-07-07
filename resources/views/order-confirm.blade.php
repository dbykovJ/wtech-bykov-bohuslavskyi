@extends('layouts.app')

@section('title', 'Замовлення підтверджено — SUPERSELL')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/order-confirm.css') }}" />
@endpush

@section('content')
<main class="confirm-main">
    <div class="container">
        <div class="confirm-box">
            <h1 class="confirm-title heading">ДЯКУЄМО ЗА ПОКУПКИ В SUPERSELL</h1>

            @if (session('success'))
                <p style="margin: 12px 0; color: #0a7a3f;">{{ session('success') }}</p>
            @endif

            <div class="confirm-actions">
                <button class="confirm-btn confirm-btn--full" onclick="window.print()">
                    Роздрукувати чек
                </button>
                <div class="confirm-actions__row">
                    <button class="confirm-btn" onclick="window.location.href='{{ route('category') }}'">
                        Продовжити покупки
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </button>
                    @auth
                        <button class="confirm-btn" onclick="window.location.href='{{ route('account') }}'">
                            До мого акаунта
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </button>
                    @else
                        <button class="confirm-btn" onclick="window.location.href='{{ route('login') }}'">
                            Увійдіть, щоб отримати максимум від вашого замовлення
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
