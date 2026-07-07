@extends('layouts.app')

@section('title', 'Мій акаунт — SUPERSELL')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/account.css') }}" />
@endpush

@section('content')
<main class="account-main">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Головна</a>
            <span class="breadcrumb__sep">›</span>
            <span class="breadcrumb__current">Мій акаунт</span>
        </div>

        <h1 class="account-title heading">МІЙ АКАУНТ</h1>

        <div class="account-layout">
            @include('partials.account-nav')

            <section class="account-content">
                <div class="account-card">
                    <h2 class="account-card__title">Профіль</h2>
                    <div class="account-profile__header">
                        <div>
                            <div class="account-profile__name">{{ Auth::user()->name  }}</div>
                            <div class="account-profile__email">{{ Auth::user()->email  }}</div>
                        </div>
                    </div>
                    <div class="account-profile__meta">
                        <div>
                            <span>Учасник з:</span>
                            <strong>{{ Auth::user()->created_at->format('m.Y') }}</strong>
                        </div>
                        <div>
                            <span>Всього замовлень</span>
                            <strong>{{ Auth::user()->orders()->count()  }}</strong>
                        </div>
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
