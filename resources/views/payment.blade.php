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
                    <div class="payment-card payment-card--section">
                        <h2 class="payment-card__title">Payment Method</h2>
                        <label class="payment-option payment-option--active">
                            <div class="payment-option__left">
                                <div class="placeholder payment-option__logo"></div>
                                <span class="payment-option__name">Stripe (Card)</span>
                            </div>
                            <input type="radio" name="payment" value="stripe" class="payment-option__radio" checked disabled />
                            <span class="payment-option__custom-radio"></span>
                        </label>
                    </div>

                    <div class="payment-card payment-card--section">
                        <h2 class="payment-card__title">Delivery Address</h2>

                        <div class="checkout-grid">
                            <div class="checkout-field checkout-field--full">
                                <label for="full_name">Full Name</label>
                                <input id="full_name" name="full_name" value="{{ old('full_name', auth()->user()->name) }}" required />
                                @error('full_name')<span class="field-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="checkout-field">
                                <label for="email">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required />
                                @error('email')<span class="field-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="checkout-field">
                                <label for="phone">Phone</label>
                                <input id="phone" name="phone" value="{{ old('phone') }}" required />
                                @error('phone')<span class="field-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="checkout-field checkout-field--full">
                                <label for="street">Street</label>
                                <input id="street" name="street" value="{{ old('street') }}" required />
                                @error('street')<span class="field-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="checkout-field">
                                <label for="city">City</label>
                                <input id="city" name="city" value="{{ old('city') }}" required />
                                @error('city')<span class="field-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="checkout-field">
                                <label for="postal_code">Postal Code</label>
                                <input id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required />
                                @error('postal_code')<span class="field-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="checkout-field">
                                <label for="country">Country (2-letter code)</label>
                                <input id="country" name="country" maxlength="2" value="{{ old('country', 'SK') }}" required />
                                @error('country')<span class="field-error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                </div>

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
                        @if (($cartSummary['promo_discount_total'] ?? 0) > 0)
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

                    <button type="submit" class="place-order-btn">Pay with Stripe</button>
                </div>
            </div>
        </form>
    </div>
</main>
@endsection
