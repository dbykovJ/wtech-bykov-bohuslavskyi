@extends('layouts.app')

@section('title', 'Ваші дані — Look of Today')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/card.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/personal-data.css') }}"/>
@endpush

@section('content')
    <main class="personal-main">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('cart') }}">Кошик</a>
                <span class="breadcrumb__sep">›</span>
                <span class="breadcrumb__current">Особисті дані</span>
            </div>

            <h1 class="personal-title heading">ВАШІ ДАНІ</h1>

            <div class="personal-layout">
                <div class="personal-left">
                    <form class="personal-form" method="POST" action="{{ route('checkout.personal-data.store') }}" novalidate>

                        <div class="option-card option-card--section">
                            <h2 class="option-card__title">Спосіб доставки</h2>

                            <label class="option-single-choice">
                                <div class="option-single-choice__left">
                                    <div class="placeholder option-single-choice__logo">
                                        <img src="{{ asset('assets/icons/delivery/truck.svg') }}" alt="Іконка вантажівки" class="option-single-choice__logo"/>
                                    </div>
                                    <span class="option-single-choice__name">Вантажівка</span>
                                </div>
                                <input type="radio" name="delivery_method" value="truck"
                                       class="option-single-choice__radio" checked/>
                                <span class="option-single-choice__custom-radio"></span>
                            </label>

                            <label class="option-single-choice">
                                <div class="option-single-choice__left">
                                    <div class="placeholder option-single-choice__logo">
                                        <img src="{{ asset('assets/icons/delivery/mail.svg') }}" alt="Іконка поштового кур'єра" class="option-single-choice__logo"/>
                                    </div>
                                    <span class="option-single-choice__name">Поштовий кур'єр</span>
                                </div>
                                <input type="radio" name="delivery_method" value="post"
                                       class="option-single-choice__radio"/>
                                <span class="option-single-choice__custom-radio"></span>
                            </label>

                            <label class="option-single-choice">
                                <div class="option-single-choice__left">
                                    <div class="placeholder option-single-choice__logo">
                                        <img src="{{ asset('assets/icons/delivery/box.svg') }}" alt="Іконка поштомату" class="option-single-choice__logo"/>
                                    </div>
                                    <span class="option-single-choice__name">Поштомат поруч із вами</span>
                                </div>
                                <input type="radio" name="delivery_method" value="drop box"
                                       class="option-single-choice__radio"/>
                                <span class="option-single-choice__custom-radio"></span>
                            </label>

                            @error('delivery_method')
                            <div class="error-bubble">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="personal-form-box">
                            @csrf

                            <input
                                type="text"
                                name="full_name"
                                class="personal-input @error('full_name') is-error @enderror"
                                placeholder="Повне ім'я"
                                value="{{ old('full_name', $address['full_name'] ?? $lastOrder?->shipping_full_name ?? auth()->user()?->name) }}"
                                required
                                maxlength="120"
                                autocomplete="name"
                            />
                            @error('full_name')
                            <div class="error-bubble">{{ $message }}</div>
                            @enderror

                            <input
                                type="email"
                                name="email"
                                class="personal-input @error('email') is-error @enderror"
                                placeholder="Електронна пошта"
                                value="{{ old('email', $address['email'] ?? $lastOrder?->shipping_email ?? auth()->user()?->email) }}"
                                required
                                maxlength="120"
                                autocomplete="email"
                            />
                            @error('email')
                            <div class="error-bubble">{{ $message }}</div>
                            @enderror

                            <input
                                type="text"
                                name="street"
                                class="personal-input @error('street') is-error @enderror"
                                placeholder="Адреса"
                                value="{{ old('street', $address['street'] ?? $lastOrder?->shipping_street ?? auth()->user()?->address) }}"
                                required
                                maxlength="150"
                                autocomplete="street-address"
                            />
                            @error('street')
                            <div class="error-bubble">{{ $message }}</div>
                            @enderror

                            <div class="personal-row">
                                <div class="personal-row__item">
                                    <input
                                        type="number"
                                        name="postal_code"
                                        class="personal-input @error('postal_code') is-error @enderror"
                                        placeholder="Поштовий індекс"
                                        value="{{ old('postal_code', $address['postal_code'] ?? $lastOrder?->shipping_postal_code ?? auth()->user()?->postcode) }}"
                                        required
                                        min="0"
                                        max="9999999"
                                        autocomplete="postal-code"
                                    />
                                    @error('postal_code')
                                    <div class="error-bubble">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="personal-row__item">
                                    <input
                                        type="text"
                                        name="city"
                                        class="personal-input @error('city') is-error @enderror"
                                        placeholder="Місто"
                                        value="{{ old('city', $address['city'] ?? $lastOrder?->shipping_city ?? auth()->user()?->city) }}"
                                        required
                                        maxlength="100"
                                        autocomplete="address-level2"
                                    />
                                    @error('city')
                                    <div class="error-bubble">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="personal-row">
                                <div>
                                    @include('partials.country-select', [
                                        'id'       => 'country',
                                        'name'     => 'country',
                                        'required' => true,
                                        'selected' => old('country', $address['country'] ?? $lastOrder?->shipping_country ?? auth()->user()?->country ?? 'SK'),
                                    ])
                                    @error('country')
                                    <div class="error-bubble">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <input
                                        type="tel"
                                        name="phone"
                                        class="personal-input @error('phone') is-error @enderror"
                                        placeholder="Телефон (з кодом країни)"
                                        inputmode="tel"
                                        pattern="^\+[0-9]{3}( [0-9]{3}){3}$"
                                        maxlength="16"
                                        title="Формат: +123 456 789 012"
                                        value="{{ old('phone', $address['phone'] ?? $lastOrder?->shipping_phone) }}"
                                        required
                                        autocomplete="tel"
                                    />
                                    @error('phone')
                                    <div class="error-bubble">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="finish-order-btn">
                                Перейти до оплати
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="order-summary">
                    <h2 class="order-summary__title">Підсумок замовлення</h2>

                    <div class="order-summary__rows">
                        <div class="order-summary__row">
                            <span>Проміжна сума</span>
                            <span>${{ number_format($cartSummary['subtotal_before_discount'] ?? 0, 2) }}</span>
                        </div>
                        <div class="order-summary__row">
                            <span>Знижка</span>
                            <span class="order-summary__discount">
                                -${{ number_format($cartSummary['discount_total'] ?? 0, 2) }}
                            </span>
                        </div>
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
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        const phoneInput = document.querySelector('input[name="phone"]');

        const formatPhone = (value) => {
            const digits = value.replace(/\D/g, '').slice(0, 12);
            if (!digits.length) return '';

            const groups = [];
            for (let i = 0; i < digits.length; i += 3) {
                groups.push(digits.slice(i, i + 3));
            }

            return `+${groups.join(' ')}`
                .replace(/(\+\d{3})(\d)/, '$1 $2')
                .replace(/(\+\d{3} \d{3})(\d)/, '$1 $2')
                .slice(0, 16);
        };

        const getCaretFromDigits = (formattedValue, digitsBeforeCaret) => {
            if (!digitsBeforeCaret) return 1;

            let digitsSeen = 0;
            for (let i = 0; i < formattedValue.length; i++) {
                if (/\d/.test(formattedValue[i])) digitsSeen++;
                if (digitsSeen >= digitsBeforeCaret) return i + 1;
            }

            return formattedValue.length;
        };

        if (phoneInput) {
            phoneInput.addEventListener('input', (e) => {
                const raw            = e.target.value;
                const selStart       = e.target.selectionStart ?? raw.length;
                const digitsBeforeCaret = raw.slice(0, selStart).replace(/\D/g, '').length;
                const formatted      = formatPhone(raw);

                e.target.value = formatted;
                const nextCaret = getCaretFromDigits(formatted, digitsBeforeCaret);
                e.target.setSelectionRange(nextCaret, nextCaret);
            });

            phoneInput.value = formatPhone(phoneInput.value);
        }
    </script>
@endpush
