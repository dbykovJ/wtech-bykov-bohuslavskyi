@extends('layouts.app')

@section('title', 'Payment — SUPERSELL')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}" />
@endpush

@section('content')
    <main class="payment-main">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('cart') }}">Cart</a>
                <span class="breadcrumb__sep">›</span>
                <span class="breadcrumb__current">Checkout</span>
            </div>

            <h1 class="payment-title heading">CHECKOUT</h1>

            @if ($errors->has('checkout'))
                <p class="payment-alert payment-alert--error">{{ $errors->first('checkout') }}</p>
            @endif

            <form method="POST" action="{{ route('checkout.start') }}" class="checkout-form">
                @csrf

                <div class="payment-layout">
                    <div class="payment-methods">

                        {{-- Payment Method --}}
{{--                        <div class="payment-card payment-card--section">--}}
{{--                            <h2 class="payment-card__title">Payment Method</h2>--}}

{{--                            <label class="payment-option payment-option--active">--}}
{{--                                <div class="payment-option__left">--}}
{{--                                    <svg class="payment-option__logo" viewBox="0 0 38 24" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                        <rect width="38" height="24" rx="4" fill="#635BFF"/>--}}
{{--                                        <path d="M16.5 9c-1.4 0-2.5.6-2.5 1.8 0 2.4 3.5 1.7 3.5 2.8 0 .4-.4.7-1.1.7-.9 0-1.8-.4-2.4-.9l-.7 1.4c.7.6 1.8 1 3.1 1 1.6 0 2.7-.7 2.7-1.9 0-2.4-3.5-1.7-3.5-2.8 0-.4.3-.6 1-.6.8 0 1.6.3 2.1.7l.7-1.4c-.7-.5-1.6-.8-2.9-.8z" fill="white"/>--}}
{{--                                    </svg>--}}
{{--                                    <span class="payment-option__name">Stripe (Card)</span>--}}
{{--                                </div>--}}
{{--                                <input type="radio" name="payment" value="stripe" class="payment-option__radio" checked disabled />--}}
{{--                                <span class="payment-option__custom-radio payment-option__custom-radio--checked"></span>--}}
{{--                            </label>--}}

{{--                            <label class="payment-option payment-option--disabled">--}}
{{--                                <div class="payment-option__left">--}}
{{--                                    <svg class="payment-option__logo" viewBox="0 0 38 24" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                        <rect width="38" height="24" rx="4" fill="#003087"/>--}}
{{--                                        <path d="M15 8h4c2 0 3 1 2.5 3S19 14 17 14h-1.5L15 17h-2l2-9zm2 2l-.7 3H17c1 0 1.7-.5 2-1.5.2-1-.3-1.5-1.3-1.5h-.7z" fill="white"/>--}}
{{--                                    </svg>--}}
{{--                                    <span class="payment-option__name">PayPal</span>--}}
{{--                                </div>--}}
{{--                                <span class="payment-option__coming-soon">Coming soon</span>--}}
{{--                            </label>--}}

{{--                            <label class="payment-option payment-option--disabled">--}}
{{--                                <div class="payment-option__left">--}}
{{--                                    <svg class="payment-option__logo" viewBox="0 0 38 24" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                        <rect width="38" height="24" rx="4" fill="#000"/>--}}
{{--                                        <path d="M22 8.5c.9-1 1.5-2.4 1.3-3.8-1.3.1-2.8.9-3.7 1.9-.8.9-1.5 2.3-1.3 3.7 1.4.1 2.8-.7 3.7-1.8z" fill="white"/>--}}
{{--                                        <path d="M23.3 10.3c-2-.1-3.7 1.1-4.7 1.1-1 0-2.5-1-4.1-1-2.1 0-4 1.2-5.1 3.1-2.2 3.8-.6 9.4 1.5 12.5 1 1.5 2.2 3.1 3.8 3 1.5-.1 2.1-.9 4-1 1.8 0 2.4.9 4 .9 1.6 0 2.7-1.5 3.7-3 1.2-1.7 1.6-3.3 1.7-3.4-.1 0-3.2-1.2-3.2-4.8 0-3 2.5-4.4 2.6-4.5-1.4-2.1-3.6-2.8-4.2-2.9z" fill="white"/>--}}
{{--                                    </svg>--}}
{{--                                    <span class="payment-option__name">Apple Pay</span>--}}
{{--                                </div>--}}
{{--                                <span class="payment-option__coming-soon">Coming soon</span>--}}
{{--                            </label>--}}

{{--                            <label class="payment-option payment-option--disabled">--}}
{{--                                <div class="payment-option__left">--}}
{{--                                    <svg class="payment-option__logo" viewBox="0 0 38 24" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                        <rect width="38" height="24" rx="4" fill="#F5F5F5"/>--}}
{{--                                        <circle cx="15" cy="12" r="5" fill="#EB001B"/>--}}
{{--                                        <circle cx="23" cy="12" r="5" fill="#F79E1B"/>--}}
{{--                                        <path d="M19 8.5a5 5 0 010 7 5 5 0 010-7z" fill="#FF5F00"/>--}}
{{--                                    </svg>--}}
{{--                                    <span class="payment-option__name">Mastercard</span>--}}
{{--                                </div>--}}
{{--                                <span class="payment-option__coming-soon">Coming soon</span>--}}
{{--                            </label>--}}
{{--                        </div>--}}
                        {{-- Delivery Address --}}
                        <div class="payment-card payment-card--section">
                            <h2 class="payment-card__title">Delivery Address</h2>

                            <div class="checkout-grid">
                                <div class="checkout-field checkout-field--full">
                                    <label for="full_name">Full Name</label>
                                    <input id="full_name" name="full_name"
                                           value="{{ old('full_name', auth()->user()?->name) }}" required />
                                    @error('full_name')<span class="field-error">{{ $message }}</span>@enderror
                                </div>

                                <div class="checkout-field">
                                    <label for="email">Email</label>
                                    <input id="email" name="email" type="email"
                                           value="{{ old('email', auth()->user()?->email) }}" required />
                                    @error('email')<span class="field-error">{{ $message }}</span>@enderror
                                </div>

                                <div class="checkout-field">
                                    <label for="phone">Phone</label>
                                    <input id="phone" name="phone"
                                           value="{{ old('phone', $lastOrder?->shipping_phone) }}" required />
                                    @error('phone')<span class="field-error">{{ $message }}</span>@enderror
                                </div>

                                <div class="checkout-field checkout-field--full">
                                    <label for="street">Street</label>
                                    <input id="street" name="street"
                                           value="{{ old('street', $lastOrder?->shipping_street ?? auth()->user()?->address) }}" required />
                                    @error('street')<span class="field-error">{{ $message }}</span>@enderror
                                </div>

                                <div class="checkout-field">
                                    <label for="city">City</label>
                                    <input id="city" name="city"
                                           value="{{ old('city', $lastOrder?->shipping_city ?? auth()->user()?->city) }}" required />
                                    @error('city')<span class="field-error">{{ $message }}</span>@enderror
                                </div>

                                <div class="checkout-field">
                                    <label for="postal_code">Postal Code</label>
                                    <input id="postal_code" name="postal_code"
                                           value="{{ old('postal_code', $lastOrder?->shipping_postal_code ?? auth()->user()?->postcode) }}" required />
                                    @error('postal_code')<span class="field-error">{{ $message }}</span>@enderror
                                </div>

                                <div class="checkout-field">
                                    <label for="country">Country</label>
                                    @include('partials.country-select', [
                                        'id'       => 'country',
                                        'name'     => 'country',
                                        'required' => true,
                                        'selected' => old('country', $lastOrder?->shipping_country ?? auth()->user()?->country ?? 'SK'),
                                    ])
                                    @error('country')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Order Summary --}}
                    <div class="order-summary">
                        <h2 class="order-summary__title">Order Summary</h2>

                        <div class="order-summary__rows">
                            <div class="order-summary__row">
                                <span>Subtotal</span>
                                <span>${{ number_format($cartSummary['subtotal_before_discount'] ?? 0, 2) }}</span>
                            </div>
                            <div class="order-summary__row">
                                <span>Sale Discount</span>
                                <span class="order-summary__discount">-${{ number_format($cartSummary['sales_discount_total'] ?? 0, 2) }}</span>
                            </div>
                            @if (!empty($cartSummary['promo_code']) && ($cartSummary['promo_discount_total'] ?? 0) >= 0.01)
                                <div class="order-summary__row">
                                    <span>Promo ({{ $cartSummary['promo_code'] }})</span>
                                    <span class="order-summary__discount">-${{ number_format($cartSummary['promo_discount_total'], 2) }}</span>
                                </div>
                            @endif
                            <div class="order-summary__row">
                                <span>Delivery Fee</span>
                                <span>${{ number_format($cartSummary['delivery_fee'] ?? 0, 2) }}</span>
                            </div>
                        </div>

                        <div class="order-summary__divider"></div>

                        <div class="order-summary__total">
                            <span>Total</span>
                            <span>${{ number_format($cartSummary['total'] ?? 0, 2) }}</span>
                        </div>

                        <button type="submit" class="place-order-btn">
                            Pay with Stripe
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
