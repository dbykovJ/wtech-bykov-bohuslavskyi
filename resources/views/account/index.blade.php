@extends('layouts.app')

@section('title', 'Мій акаунт — Look of Today')

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

                <div class="account-card">
                    <h2 class="account-card__title">Картка лояльності</h2>

                    @if (session('success'))
                        <div class="success-bubble" data-success-bubble>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (!$loyaltyProgress['has_card'])
                        <p class="loyalty-intro">
                            Купуйте більше — отримуйте знижку до 25%! Кожні 10 придбаних товарів дають +2.5% знижки на майбутні покупки. Знижка працює лише онлайн, у фізичних магазинах вона не застосовується.
                        </p>
                        <form method="POST" action="{{ route('account.loyalty.create') }}">
                            @csrf
                            <button type="submit" class="account-btn">Створити картку</button>
                        </form>
                    @else
                        <div class="loyalty-progress">
                            @foreach ($loyaltyProgress['segments'] as $segmentFill)
                                <div class="loyalty-progress__segment">
                                    <div class="loyalty-progress__fill" style="width: {{ $segmentFill }}%"></div>
                                </div>
                            @endforeach
                        </div>

                        <div class="loyalty-progress__meta">
                            <div>
                                <span>Ваша знижка</span>
                                <strong>{{ number_format($loyaltyProgress['discount_percent'], 1) }}%</strong>
                            </div>
                            <div>
                                <span>Товарів придбано</span>
                                <strong>{{ $loyaltyProgress['items_count'] }}</strong>
                            </div>
                        </div>

                        @if ($loyaltyProgress['tier'] >= 10)
                            <p class="loyalty-progress__note">Максимальну знижку 25% досягнуто!</p>
                        @else
                            <p class="loyalty-progress__note">До наступного рівня: ще {{ $loyaltyProgress['items_until_next_tier'] }} товар(ів)</p>
                        @endif
                    @endif
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
