@extends('layouts.app')

@section('title', 'Оплата — Look of Today')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/card.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}" />
@endpush

@section('content')
    <main class="payment-main">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('cart') }}">Кошик</a>
                <span class="breadcrumb__sep">›</span>
                <a href="{{ route('checkout.personal-data') }}">Особисті дані</a>
                <span class="breadcrumb__sep">›</span>
                <span class="breadcrumb__current">Оплата</span>
            </div>

            <h1 class="payment-title heading">ОФОРМЛЕННЯ ЗАМОВЛЕННЯ</h1>

            @if ($errors->has('checkout'))
                <p class="payment-alert payment-alert--error">{{ $errors->first('checkout') }}</p>
            @endif

            <form method="POST" action="{{ route('payment.pay') }}" class="checkout-form">
                @csrf

                <div class="payment-layout">
                    <div class="payment-methods">
                        <div class="option-card option-card--section">
                            <h2 class="option-card__title">Ваше замовлення</h2>

                            <div class="checkout-grid">
                                @foreach ($cartItems as $item)
                                    <div class="checkout-field checkout-field--full" style="flex-direction: row; justify-content: space-between; align-items: center;">
                                        <div>
                                            <div>{{ $item->product->name }}</div>
                                            <small style="color: #888;">Розмір: {{ $item->size->value }} · Колір: {{ $item->color->name }} · Кількість: {{ $item->count }}</small>
                                        </div>
                                        <div>${{ number_format($item->line_subtotal, 2) }}</div>
                                    </div>
                                @endforeach
                            </div>

                            <p style="margin-top: 16px; font-size: 13px; color: #666;">
                                Оплата обробляється через Stripe. Після натискання «Оплатити» вас буде перенаправлено на захищену сторінку оплати.
                            </p>
                        </div>
                    </div>

                    {{-- Order Summary --}}
                    <div class="order-summary">
                        <h2 class="order-summary__title">Підсумок замовлення</h2>

                        <div class="order-summary__rows">
                            <div class="order-summary__row">
                                <span>Проміжна сума</span>
                                <span>${{ number_format($cartSummary['subtotal_before_discount'] ?? 0, 2) }}</span>
                            </div>
                            <div class="order-summary__row">
                                <span>Знижка розпродажу</span>
                                <span class="order-summary__discount">-${{ number_format($cartSummary['sales_discount_total'] ?? 0, 2) }}</span>
                            </div>
                            @if (!empty($cartSummary['promo_code']) && ($cartSummary['promo_discount_total'] ?? 0) >= 0.01)
                                <div class="order-summary__row">
                                    <span>Промокод ({{ $cartSummary['promo_code'] }})</span>
                                    <span class="order-summary__discount">-${{ number_format($cartSummary['promo_discount_total'], 2) }}</span>
                                </div>
                            @endif
                            @if (($cartSummary['loyalty_discount_total'] ?? 0) >= 0.01)
                                <div class="order-summary__row">
                                    <span>Знижка за лояльність</span>
                                    <span class="order-summary__discount">-${{ number_format($cartSummary['loyalty_discount_total'], 2) }}</span>
                                </div>
                            @endif
                            <div class="order-summary__row">
                                <span>Вартість доставки</span>
                                <span>${{ number_format($cartSummary['delivery_fee'] ?? 0, 2) }}</span>
                            </div>
                        </div>

                        <div class="order-summary__divider"></div>

                        <div class="order-summary__total">
                            <span>Разом</span>
                            <span>${{ number_format($cartSummary['total'] ?? 0, 2) }}</span>
                        </div>

                        <button type="submit" class="place-order-btn">
                            Оплатити
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection
