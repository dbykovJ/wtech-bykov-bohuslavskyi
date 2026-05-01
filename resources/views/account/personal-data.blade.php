@extends('layouts.app')

@section('title', 'Your Data — SUPERSELL')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/account.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/user-personal-data.css') }}" />
@endpush

@section('content')
    <main class="personal-main">
        <div class="container">
            <h1 class="personal-title heading">YOUR DATA</h1>

            @include('partials.account-nav')

            @if (session('success'))
                <div class="promo-success-bubble" data-promo-success-bubble>
                    {{ session('success') }}
                </div>
            @endif

            <div class="personal-layout">
                <div class="personal-left">

                    {{-- Personal details form --}}
                    <div class="personal-form-box">
                        <form class="personal-form" method="POST" action="{{ route('account.personal-data.update') }}">
                            @csrf
                            @method('PUT')

                            <input
                                type="text"
                                name="name"
                                class="personal-input @error('name') is-error @enderror"
                                placeholder="Full Name"
                                value="{{ old('name', $user->name) }}"
                            />
                            @error('name')
                            <div class="promo-error-bubble">{{ $message }}</div>
                            @enderror

                            <input
                                type="email"
                                name="email"
                                class="personal-input @error('email') is-error @enderror"
                                placeholder="E-mail"
                                value="{{ old('email', $user->email) }}"
                            />
                            @error('email')
                            <div class="promo-error-bubble">{{ $message }}</div>
                            @enderror

                            <input
                                type="text"
                                name="address"
                                class="personal-input @error('address') is-error @enderror"
                                placeholder="Address"
                                value="{{ old('address', $user->address) }}"
                            />
                            @error('address')
                            <div class="promo-error-bubble">{{ $message }}</div>
                            @enderror

                            <input
                                type="text"
                                name="postcode"
                                class="personal-input @error('postcode') is-error @enderror"
                                placeholder="Post Code"
                                value="{{ old('postcode', $user->postcode) }}"
                            />
                            @error('postcode')
                            <div class="promo-error-bubble">{{ $message }}</div>
                            @enderror

                            <input
                                type="text"
                                name="city"
                                class="personal-input @error('city') is-error @enderror"
                                placeholder="City"
                                value="{{ old('city', $user->city) }}"
                            />
                            @error('city')
                            <div class="promo-error-bubble">{{ $message }}</div>
                            @enderror

                            <div class="personal-field">
                                @include('partials.country-select', [
                                    'id'       => 'country',
                                    'name'     => 'country',
                                    'required' => false,
                                    'selected' => old('country', $user->country),
                                ])
                                @error('country')<span class="field-error">{{ $message }}</span>@enderror
                            </div>

                            <button type="submit" class="account-btn">Save details</button>
                        </form>
                    </div>

                    {{-- Password form --}}
                    <div class="personal-form-box">
                        <form class="personal-form" method="POST" action="{{ route('account.password.update') }}">
                            @csrf

                            <input
                                type="password"
                                name="current_password"
                                class="personal-input @error('current_password') is-error @enderror"
                                placeholder="Current password"
                                required
                            />
                            @error('current_password')
                            <div class="promo-error-bubble">{{ $message }}</div>
                            @enderror

                            <input
                                type="password"
                                name="password"
                                class="personal-input @error('password') is-error @enderror"
                                placeholder="New password"
                                required
                            />
                            @error('password')
                            <div class="promo-error-bubble">{{ $message }}</div>
                            @enderror

                            <input
                                type="password"
                                name="password_confirmation"
                                class="personal-input"
                                placeholder="Confirm new password"
                                required
                            />

                            <button type="submit" class="account-btn">Change password</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        document.querySelector('.account-menu-toggle').addEventListener('click', function () {
            document.querySelector('.account-nav').classList.toggle('account-nav--open');
        });
    </script>
@endpush
