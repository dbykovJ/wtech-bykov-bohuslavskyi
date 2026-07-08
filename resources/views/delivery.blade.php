@extends('layouts.app')

@section('title', 'Доставка — Look of Today')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/payment.css') }}" />
@endpush

@section('content')
<main class="payment-main">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('cart') }}">Оплата</a>
            <span class="breadcrumb__sep">›</span>
            <a href="{{ route('payment') }}">Оберіть спосіб оплати</a>
            <span class="breadcrumb__sep">›</span>
            <span class="breadcrumb__current">Оберіть спосіб доставки</span>
        </div>

        <h1 class="payment-title heading">ОБЕРІТЬ СПОСІБ ДОСТАВКИ</h1>

        <div class="payment-layout">
            <div class="payment-methods">
                <label class="option-single-choice">
                    <div class="option-single-choice__left">
                        <div class="placeholder option-single-choice__logo"></div>
                        <span class="option-single-choice__name">Доставка за адресою</span>
                    </div>
                    <input type="radio" name="delivery" value="address" class="option-single-choice__radio" checked />
                    <span class="option-single-choice__custom-radio"></span>
                </label>

                <label class="option-single-choice">
                    <div class="option-single-choice__left">
                        <div class="placeholder option-single-choice__logo"></div>
                        <span class="option-single-choice__name">Доставка в поштомат</span>
                    </div>
                    <input type="radio" name="delivery" value="pickup" class="option-single-choice__radio" />
                    <span class="option-single-choice__custom-radio"></span>
                </label>

                <label class="option-single-choice">
                    <div class="option-single-choice__left">
                        <div class="placeholder option-single-choice__logo"></div>
                        <span class="option-single-choice__name">Кур'єр UPC</span>
                    </div>
                    <input type="radio" name="delivery" value="upc" class="option-single-choice__radio" />
                    <span class="option-single-choice__custom-radio"></span>
                </label>

                <label class="option-single-choice">
                    <div class="option-single-choice__left">
                        <div class="placeholder option-single-choice__logo"></div>
                        <span class="option-single-choice__name">AlzaBox</span>
                    </div>
                    <input type="radio" name="delivery" value="alzabox" class="option-single-choice__radio" />
                    <span class="option-single-choice__custom-radio"></span>
                </label>
            </div>

            <div class="order-summary">
                <h2 class="order-summary__title">Підсумок замовлення</h2>

                <div class="order-summary__rows">
                    <div class="order-summary__row">
                        <span>Проміжна сума</span>
                        <span>$565</span>
                    </div>
                    <div class="order-summary__row">
                        <span>Знижка (-20%)</span>
                        <span class="order-summary__discount">-$113</span>
                    </div>
                    <div class="order-summary__row">
                        <span>Вартість доставки</span>
                        <span>$15</span>
                    </div>
                </div>

                <div class="order-summary__divider"></div>

                <div class="order-summary__total">
                    <span>Разом</span>
                    <span>$467</span>
                </div>
            </div>
        </div>

        <button class="place-order-btn" onclick="window.location.href='{{ route('checkout.personal-data') }}'">Підтвердити замовлення</button>
    </div>
</main>
@endsection
