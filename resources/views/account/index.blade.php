@extends('layouts.app')

@section('title', 'My Account — SUPERSELL')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/account.css') }}" />
@endpush

@section('content')
<main class="account-main">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span class="breadcrumb__sep">›</span>
            <span class="breadcrumb__current">My Account</span>
        </div>

        <h1 class="account-title heading">MY ACCOUNT</h1>

        <div class="account-layout">
            @include('partials.account-nav')

            <section class="account-content">
                <div class="account-card">
                    <h2 class="account-card__title">Profile</h2>
                    <div class="account-profile__header">
                        <div>
                            <div class="account-profile__name">John Doe</div>
                            <div class="account-profile__email">john.doe@example.com</div>
                        </div>
                    </div>
                    <div class="account-profile__meta">
                        <div>
                            <span>Member since</span>
                            <strong>Jan 2025</strong>
                        </div>
                        <div>
                            <span>Total orders</span>
                            <strong>12</strong>
                        </div>
                    </div>
                </div>

                <div class="account-card">
                    <h2 class="account-card__title">Security &amp; preferences</h2>
                    <div class="account-security__list">
                        <div class="account-security__item">
                            <strong>Password:</strong> Last changed 3 months ago
                        </div>
                        <div class="account-security__item">
                            <strong>Email notifications:</strong> Enabled for orders and offers
                        </div>
                    </div>
                    <div class="account-security__actions">
                        <button class="account-btn" type="button" onclick="window.location.href='{{ route('login') }}'">
                            Change password
                        </button>
                        <button class="account-btn account-btn--secondary" type="button" onclick="window.location.href='{{ route('register') }}'">
                            Manage email preferences
                        </button>
                    </div>
                </div>
            </section>
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
