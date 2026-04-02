@extends('layouts.app')

@section('title', 'Your Data — SUPERSELL')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/personal-data.css') }}" />
@endpush

@section('content')
<main class="personal-main">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('cart') }}">Payment</a>
            <span class="breadcrumb__sep">›</span>
            <a href="{{ route('payment') }}">Choose your payment method</a>
            <span class="breadcrumb__sep">›</span>
            <a href="{{ route('delivery') }}">Choose your delivery method</a>
            <span class="breadcrumb__sep">›</span>
            <span class="breadcrumb__current">Fill in your personal data</span>
        </div>

        <h1 class="personal-title heading">YOUR DATA</h1>

        <div class="personal-layout">
            <div class="personal-left">
                <div class="personal-form-box">
                    <form class="personal-form" onsubmit="return false;">
                        <input type="text" class="personal-input" placeholder="Full Name" />
                        <input type="email" class="personal-input" placeholder="E-mail" />
                        <input type="text" class="personal-input" placeholder="Address" />
                        <input type="text" class="personal-input" placeholder="Post Code" />
                        <input type="text" class="personal-input" placeholder="City" />
                        <input type="text" class="personal-input" placeholder="Country" />
                    </form>
                </div>

                <div class="selected-methods">
                    <div class="selected-method">
                        <div class="placeholder selected-method__logo"></div>
                        <span class="selected-method__name">Credit or Debit Card</span>
                    </div>
                    <div class="selected-method">
                        <div class="placeholder selected-method__logo"></div>
                        <span class="selected-method__name">Delivery by truck</span>
                    </div>
                </div>
            </div>

            <div class="order-summary">
                <h2 class="order-summary__title">Order Summary</h2>

                <div class="order-summary__rows">
                    <div class="order-summary__row">
                        <span>Subtotal</span>
                        <span>$565</span>
                    </div>
                    <div class="order-summary__row">
                        <span>Discount (-20%)</span>
                        <span class="order-summary__discount">-$113</span>
                    </div>
                    <div class="order-summary__row">
                        <span>Delivery Fee</span>
                        <span>$15</span>
                    </div>
                </div>

                <div class="order-summary__divider"></div>

                <div class="order-summary__total">
                    <span>Total</span>
                    <span>$467</span>
                </div>

                <button class="finish-order-btn" onclick="window.location.href='{{ route('order-confirm') }}'">
                    Finish order
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</main>
@endsection
