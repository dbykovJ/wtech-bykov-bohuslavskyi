@extends('layouts.app')

@section('title', 'Оплата — Look of Today')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}" />
@endpush

@section('content')
    <main class="payment-main">
        <div class="container">
            <nav class="breadcrumb" aria-label="Навігація оформлення замовлення">
                <a href="{{ route('cart') }}">Кошик</a><span class="breadcrumb__sep">›</span>
                <a href="{{ route('checkout.personal-data') }}">Особисті дані</a><span class="breadcrumb__sep">›</span>
                <span class="breadcrumb__current">Оплата</span>
            </nav>

            <h1 class="payment-title heading">ОПЛАТА ЗАМОВЛЕННЯ</h1>

            @if ($errors->has('checkout'))
                <p class="payment-alert payment-alert--error">{{ $errors->first('checkout') }}</p>
            @endif

            <form method="POST" action="{{ route('payment.pay') }}" class="checkout-form" novalidate>
                @csrf
                <div class="payment-layout">
                    <section class="payment-panel" aria-labelledby="card-details-title">
                        <div class="payment-panel__heading">
                            <div>
                                <p class="payment-panel__eyebrow">Банківська картка</p>
                                <h2 id="card-details-title">Платіжні дані</h2>
                            </div>
                            <div class="card-brands" aria-label="Підтримувані картки"><span>VISA</span><span>MC</span></div>
                        </div>
                        <p class="payment-panel__hint">Введіть дані картки, щоб завершити оформлення. Дані картки не зберігаються.</p>

                        <div class="payment-return-note">
                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M12 8v4l2.5 1.5M20 12a8 8 0 1 1-2.34-5.66" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M17 3v4h4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <p><strong>Після оплати поверніться на цю сторінку та підтвердьте платіж.</strong> Ми надішлемо на вашу електронну пошту список придбаних товарів. Менеджер зв’яжеться з вами протягом 15 хвилин.</p>
                        </div>

                        <div class="card-form">
                            <div class="card-field card-field--full">
                                <label for="cardholder_name">Ім’я власника картки</label>
                                <input id="cardholder_name" name="cardholder_name" type="text" value="{{ old('cardholder_name') }}"
                                       placeholder="IVAN PETRENKO" autocomplete="cc-name" maxlength="120" required
                                       class="@error('cardholder_name') is-error @enderror" />
                                @error('cardholder_name') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="card-field card-field--full">
                                <label for="card_number">Номер картки</label>
                                <div class="card-number-wrap">
                                    <input id="card_number" name="card_number" type="text" placeholder="0000 0000 0000 0000"
                                           inputmode="numeric" autocomplete="cc-number" maxlength="23" required
                                           class="@error('card_number') is-error @enderror" />
                                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><rect x="2.5" y="5" width="19" height="14" rx="2.5" stroke="currentColor" stroke-width="1.5"/><path d="M3 9h18" stroke="currentColor" stroke-width="1.5"/></svg>
                                </div>
                                @error('card_number') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="card-field">
                                <label for="expiry">Термін дії</label>
                                <input id="expiry" name="expiry" type="text" placeholder="MM/YY" inputmode="numeric"
                                       autocomplete="cc-exp" maxlength="5" required class="@error('expiry') is-error @enderror" />
                                @error('expiry') <span class="field-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="card-field">
                                <label for="cvv">CVV</label>
                                <input id="cvv" name="cvv" type="password" placeholder="•••" inputmode="numeric"
                                       autocomplete="cc-csc" maxlength="4" required class="@error('cvv') is-error @enderror" />
                                @error('cvv') <span class="field-error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="payment-security">
                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M7 10V8a5 5 0 0 1 10 0v2M6 10h12v10H6V10Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span>Безпечна оплата без збереження реквізитів</span>
                        </div>
                    </section>

                    <aside class="order-summary">
                        <h2 class="order-summary__title">Підсумок замовлення</h2>
                        <div class="order-items">
                            @foreach ($cartItems as $item)
                                <div class="order-item">
                                    <div class="order-item__info">
                                        <strong>{{ $item->product->name }}</strong>
                                        <span>{{ $item->size->value }} · {{ $item->color->name }} · {{ $item->count }} шт.</span>
                                    </div>
                                    <span class="order-item__price">${{ number_format($item->line_subtotal, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="order-summary__rows">
                            <div class="order-summary__row"><span>Проміжна сума</span><span>${{ number_format($cartSummary['subtotal_before_discount'] ?? 0, 2) }}</span></div>
                            @if (($cartSummary['discount_total'] ?? 0) >= 0.01)
                                <div class="order-summary__row"><span>Знижка</span><span class="order-summary__discount">-${{ number_format($cartSummary['discount_total'], 2) }}</span></div>
                            @endif
                            <div class="order-summary__row"><span>Доставка</span><span>${{ number_format($cartSummary['delivery_fee'] ?? 0, 2) }}</span></div>
                        </div>
                        <div class="order-summary__divider"></div>
                        <div class="order-summary__total"><span>До сплати</span><span>${{ number_format($cartSummary['total'] ?? 0, 2) }}</span></div>
                        <button type="submit" class="place-order-btn">
                            <span>Сплатити ${{ number_format($cartSummary['total'] ?? 0, 2) }}</span>
                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </aside>
                </div>
            </form>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        const digitsOnly = (value, maxLength) => value.replace(/\D/g, '').slice(0, maxLength);
        const cardNumber = document.querySelector('#card_number');
        const expiry = document.querySelector('#expiry');
        const cvv = document.querySelector('#cvv');

        cardNumber?.addEventListener('input', (event) => {
            event.target.value = digitsOnly(event.target.value, 19).replace(/(.{4})/g, '$1 ').trim();
        });
        expiry?.addEventListener('input', (event) => {
            const digits = digitsOnly(event.target.value, 4);
            event.target.value = digits.length > 2 ? `${digits.slice(0, 2)}/${digits.slice(2)}` : digits;
        });
        cvv?.addEventListener('input', (event) => event.target.value = digitsOnly(event.target.value, 4));
    </script>
@endpush
