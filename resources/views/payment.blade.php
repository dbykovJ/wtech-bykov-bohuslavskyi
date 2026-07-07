@extends('layouts.app')

@section('title', 'Оплата — SUPERSELL')

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
                            <h2 class="option-card__title">Спосіб оплати</h2>

                            <label class="option-single-choice">
                                <div class="option-single-choice__left">
                                    <div class="placeholder option-single-choice__logo">
                                        <img src="{{ asset('assets/icons/payment/credit-card.svg') }}" alt="Card" class="option-single-choice__logo" />
                                    </div>
                                    <span class="option-single-choice__name">Картка</span>
                                </div>
                                <input type="radio" name="payment_method" value="Credit Card" class="option-single-choice__radio" checked />
                                <span class="option-single-choice__custom-radio"></span>
                            </label>

                            <label class="option-single-choice">
                                <div class="option-single-choice__left">
                                    <div class="placeholder option-single-choice__logo">
                                        <img src="{{ asset('assets/icons/payment/apple-pay.svg') }}" alt="Apple Pay" class="option-single-choice__logo" />
                                    </div>
                                    <span class="option-single-choice__name">Apple Pay</span>
                                </div>
                                <input type="radio" name="payment_method" value="Apple Pay" class="option-single-choice__radio" />
                                <span class="option-single-choice__custom-radio"></span>
                            </label>

                            <label class="option-single-choice">
                                <div class="option-single-choice__left">
                                    <div class="placeholder option-single-choice__logo">
                                        <img src="{{ asset('assets/icons/payment/google-pay.svg') }}" alt="Google Pay" class="option-single-choice__logo" />
                                    </div>
                                    <span class="option-single-choice__name">Google Pay</span>
                                </div>
                                <input type="radio" name="payment_method" value="Google Pay" class="option-single-choice__radio" />
                                <span class="option-single-choice__custom-radio"></span>
                            </label>

                            @error('payment_method')<span class="field-error">{{ $message }}</span>@enderror
                        </div>

                        <div class="option-card option-card--section payment-card-details" data-payment-details="card">
                            <h2 class="option-card__title">Дані картки</h2>

                            <div class="checkout-grid">
                                <div class="checkout-field checkout-field--full">
                                    <label for="cardholder_name">Ім'я власника картки</label>
                                    <input id="cardholder_name" name="cardholder_name" type="text" autocomplete="cc-name" placeholder="Іван Іваненко" />
                                </div>
                                <div class="checkout-field checkout-field--full">
                                    <label for="card_number">Номер картки</label>
                                    <input id="card_number" name="card_number" type="text" inputmode="numeric" autocomplete="cc-number"
                                           placeholder="1234 5678 9012 3456" pattern="^\d{4}( \d{4}){3}$" maxlength="19" />
                                </div>
                                <div class="checkout-field">
                                    <label for="card_expiry">Термін дії</label>
                                    <input id="card_expiry" name="card_expiry" type="text" inputmode="numeric" autocomplete="cc-exp"
                                           placeholder="ММ/РР" pattern="^(0[1-9]|1[0-2])\/\d{2}$" maxlength="5" />
                                </div>
                                <div class="checkout-field">
                                    <label for="card_cvc">CVC</label>
                                    <input id="card_cvc" name="card_cvc" type="text" inputmode="numeric" autocomplete="cc-csc"
                                           placeholder="123" pattern="^\d{3,4}$" maxlength="3" />
                                </div>
                            </div>
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

@push('scripts')
    <script>
        const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
        const cardDetails = document.querySelector('[data-payment-details="card"]');
        const cardInputs = document.querySelectorAll('#cardholder_name, #card_number, #card_expiry, #card_cvc');
        const cardNumberInput = document.querySelector('#card_number');
        const cardExpiryInput = document.querySelector('#card_expiry');
        const cardMethodValue = 'Credit Card';

        const updatePaymentDetails = () => {
            const selected = document.querySelector('input[name="payment_method"]:checked');
            const isCard = selected && selected.value === cardMethodValue;

            if (cardDetails) {
                cardDetails.classList.toggle('is-visible', isCard);
                cardDetails.setAttribute('aria-hidden', isCard ? 'false' : 'true');
            }

            cardInputs.forEach((input) => {
                if (input) {
                    input.required = Boolean(isCard);
                }
            });

            paymentRadios.forEach((radio) => {
                const option = radio.closest('.option-single-choice');
                if (option) {
                    option.classList.toggle('payment-option--active', radio.checked);
                }
            });
        };

        paymentRadios.forEach((radio) => {
            radio.addEventListener('change', updatePaymentDetails);
        });

        if (cardNumberInput) {
            cardNumberInput.addEventListener('input', (event) => {
                const digits = event.target.value.replace(/\D/g, '').slice(0, 16);
                const groups = digits.match(/.{1,4}/g) || [];
                event.target.value = groups.join(' ');
            });
        }

        if (cardExpiryInput) {
            cardExpiryInput.addEventListener('input', (event) => {
                const digits = event.target.value.replace(/\D/g, '').slice(0, 4);
                if (digits.length <= 2) {
                    event.target.value = digits;
                    return;
                }
                event.target.value = `${digits.slice(0, 2)}/${digits.slice(2)}`;
            });
        }

        updatePaymentDetails();
    </script>
@endpush
