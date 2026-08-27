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
                    <section class="payment-panel" aria-labelledby="payment-instructions-title">
                        <div class="payment-panel__heading">
                            <div>
                                <p class="payment-panel__eyebrow">Оплата переказом</p>
                                <h2 id="payment-instructions-title">Переказ на картку</h2>
                            </div>
                            <div class="card-brands" aria-label="Підтримувані картки"><span>VISA</span><span>MC</span></div>
                        </div>
                        <p class="payment-panel__hint">Скопіюйте номер картки, відкрийте застосунок свого банку та переказуйте точну суму замовлення.</p>

                        @if (config('services.manual_payment.card_number'))
                            <div class="recipient-card">
                                <div class="recipient-card__label">Картка для оплати</div>
                                <div class="recipient-card__number" id="recipient-card-number">{{ config('services.manual_payment.card_number') }}</div>
                                @if (config('services.manual_payment.card_holder'))
                                    <div class="recipient-card__holder">{{ config('services.manual_payment.card_holder') }}</div>
                                @endif
                                <button type="button" class="copy-card-btn" data-copy-card>
                                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><rect x="8" y="8" width="11" height="11" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M16 8V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2" stroke="currentColor" stroke-width="1.6"/></svg>
                                    <span>Скопіювати номер</span>
                                </button>
                            </div>
                        @else
                            <p class="payment-alert payment-alert--error">Номер картки для оплати ще не налаштовано. Будь ласка, зверніться до менеджера.</p>
                        @endif

                        <div class="payment-return-note">
                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M12 8v4l2.5 1.5M20 12a8 8 0 1 1-2.34-5.66" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M17 3v4h4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <p><strong>Після переказу поверніться сюди та підтвердьте оплату.</strong> На вашу електронну пошту надійде список товарів, а менеджер зв’яжеться з вами протягом 15 хвилин.</p>
                        </div>

                        <label class="payment-confirmation">
                            <input type="checkbox" name="payment_confirmed" value="1" required />
                            <span>Я переказав(-ла) ${{ number_format($cartSummary['total'] ?? 0, 2) }} на вказану картку</span>
                        </label>
                        @error('payment_confirmed') <span class="field-error">Підтвердьте, що ви виконали переказ.</span> @enderror
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
                        <button type="submit" class="place-order-btn" @disabled(!config('services.manual_payment.card_number'))>
                            <span>Підтвердити оплату</span>
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
        const copyButton = document.querySelector('[data-copy-card]');
        copyButton?.addEventListener('click', async () => {
            const cardNumber = document.querySelector('#recipient-card-number')?.textContent.trim();
            if (!cardNumber) return;

            await navigator.clipboard.writeText(cardNumber.replace(/\s/g, ''));
            const label = copyButton.querySelector('span');
            label.textContent = 'Скопійовано';
            setTimeout(() => label.textContent = 'Скопіювати номер', 1800);
        });
    </script>
@endpush
