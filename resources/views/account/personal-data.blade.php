@extends('layouts.app')

@section('title', 'Ваші дані — SUPERSELL')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/account.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/user-personal-data.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}"/>
@endpush

@section('content')
    <main class="personal-main">
        <div class="container">
            <h1 class="personal-title heading">ВАШІ ДАНІ</h1>

            @include('partials.account-nav')


            <div class="personal-layout">
                <div class="personal-left">

                    @if (session('success'))
                        <div class="success-bubble" data-success-bubble>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="personal-form-box">
                        <h2 class="personal-form-box__title">Особисті дані</h2>

                        <form class="personal-form" method="POST" action="{{ route('account.personal-data.update') }}"
                              novalidate>
                            @csrf
                            @method('PUT')


                            <div class="personal-field-wrap">
                                <input
                                    type="text"
                                    name="name"
                                    class="personal-input @error('name') is-error @enderror"
                                    placeholder="Повне ім'я"
                                    value="{{ old('name', $user->name) }}"
                                    required
                                    maxlength="64"
                                    autocomplete="name"
                                />
                                @error('name')
                                <div class="error-bubble">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="personal-field-wrap">
                                <input
                                    type="email"
                                    name="email"
                                    class="personal-input @error('email') is-error @enderror"
                                    placeholder="Електронна пошта"
                                    value="{{ old('email', $user->email) }}"
                                    required
                                    maxlength="128"
                                    autocomplete="email"
                                />
                                @error('email')
                                <div class="error-bubble">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="personal-field-wrap">
                                <input
                                    type="text"
                                    name="address"
                                    class="personal-input @error('address') is-error @enderror"
                                    placeholder="Адреса"
                                    value="{{ old('address', $user->address) }}"
                                    maxlength="255"
                                    autocomplete="street-address"
                                />
                                @error('address')
                                <div class="error-bubble">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="personal-field-wrap">
                                <input
                                    type="number"
                                    name="postcode"
                                    class="personal-input @error('postcode') is-error @enderror"
                                    placeholder="Поштовий індекс"
                                    value="{{ old('postcode', $user->postcode) }}"
                                    min="0"
                                    max="9999999"
                                    maxlength="7"
                                    autocomplete="postal-code"
                                />
                                @error('postcode')
                                <div class="error-bubble">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="personal-field-wrap">
                                <input
                                    type="text"
                                    name="city"
                                    class="personal-input @error('city') is-error @enderror"
                                    placeholder="Місто"
                                    value="{{ old('city', $user->city) }}"
                                    maxlength="128"
                                    autocomplete="address-level2"
                                />
                                @error('city')
                                <div class="error-bubble">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="personal-field-wrap">
                                <div class="personal-field">
                                    @include('partials.country-select', [
                                        'id'       => 'country',
                                        'name'     => 'country',
                                        'required' => false,
                                        'selected' => old('country', $user->country),
                                    ])
                                    @error('country')
                                    <div class="error-bubble">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="account-btn">Зберегти дані</button>
                        </form>
                    </div>

                    <div class="personal-form-box">
                        <h2 class="personal-form-box__title">Зміна пароля</h2>

                        <form class="personal-form" method="POST" action="{{ route('account.password.update') }}"
                              novalidate>
                            @csrf

                            <input
                                type="password"
                                name="current_password"
                                class="personal-input @error('current_password') is-error @enderror"
                                placeholder="Поточний пароль"
                                required
                                autocomplete="current-password"
                            />
                            @error('current_password')
                            <div class="error-bubble">{{ $message }}</div>
                            @enderror

                            <input
                                type="password"
                                name="password"
                                id="new_password"
                                class="personal-input @error('password') is-error @enderror"
                                placeholder="Новий пароль (мін. 8 символів)"
                                required
                                minlength="8"
                                autocomplete="new-password"
                            />
                            @error('password')
                            <div class="error-bubble">{{ $message }}</div>
                            @enderror

                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="personal-input @error('password_confirmation') is-error @enderror"
                                placeholder="Підтвердьте новий пароль"
                                required
                                minlength="8"
                                autocomplete="new-password"
                            />
                            @error('password_confirmation')
                            <div class="error-bubble">{{ $message }}</div>
                            @enderror

                            <button type="submit" class="account-btn">Змінити пароль</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        document.querySelector('.account-menu-toggle')?.addEventListener('click', function () {
            document.querySelector('.account-nav').classList.toggle('account-nav--open');
        });

        const newPassword = document.getElementById('new_password');
        const confirmInput = document.getElementById('password_confirmation');

        confirmInput?.addEventListener('input', () => {
            if (confirmInput.value && confirmInput.value !== newPassword.value) {
                confirmInput.setCustomValidity('Паролі не збігаються.');
            } else {
                confirmInput.setCustomValidity('');
            }
        });

        newPassword?.addEventListener('input', () => {
            if (confirmInput.value && confirmInput.value !== newPassword.value) {
                confirmInput.setCustomValidity('Паролі не збігаються.');
            } else {
                confirmInput.setCustomValidity('');
            }
        });
    </script>
@endpush
