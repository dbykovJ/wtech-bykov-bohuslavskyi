@extends('layouts.app')

@section('title', 'Your Orders — SUPERSELL')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/account.css') }}" />
<link rel="stylesheet" href="{{ asset('css/user-orders.css') }}" />
@endpush

@section('content')
<main class="account-main">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span class="breadcrumb__sep">›</span>
            <a href="{{ route('account') }}">My Account</a>
            <span class="breadcrumb__sep">›</span>
            <span class="breadcrumb__current">Orders</span>
        </div>

        <h1 class="account-title heading">MY ORDERS</h1>

        <div class="account-layout">
            @include('partials.account-nav')

            <section class="account-content">
                <div class="account-card">
                    <h2 class="account-card__title">Recent orders</h2>
                    <div class="orders-list">
                        <article class="order-card">
                            <div class="order-card__header">
                                <div>
                                    <div class="order-card__id">Order #12345</div>
                                    <div class="order-card__date">Placed on 18 March 2025</div>
                                </div>
                                <div class="order-card__status order-card__status--delivered">Delivered</div>
                            </div>
                            <div class="order-card__meta">
                                <div><span>Items:</span> <strong>3</strong></div>
                                <div><span>Total:</span> <strong>$245.00</strong></div>
                                <div><span>Delivery:</span> <strong>Standard</strong></div>
                            </div>
                            <div class="order-card__actions">
                                <button class="account-btn account-btn--secondary" type="button" onclick="window.location.href='{{ route('order-confirm') }}'">
                                    View details
                                </button>
                                <button class="account-btn" type="button" onclick="window.location.href='{{ route('account.cart') }}'">
                                    Reorder
                                </button>
                            </div>
                        </article>

                        <article class="order-card">
                            <div class="order-card__header">
                                <div>
                                    <div class="order-card__id">Order #12344</div>
                                    <div class="order-card__date">Placed on 03 March 2025</div>
                                </div>
                                <div class="order-card__status order-card__status--processing">Processing</div>
                            </div>
                            <div class="order-card__meta">
                                <div><span>Items:</span> <strong>1</strong></div>
                                <div><span>Total:</span> <strong>$89.00</strong></div>
                                <div><span>Delivery:</span> <strong>Express</strong></div>
                            </div>
                            <div class="order-card__actions">
                                <button class="account-btn account-btn--secondary" type="button" onclick="window.location.href='{{ route('order-confirm') }}'">
                                    View details
                                </button>
                                <button class="account-btn" type="button" onclick="window.location.href='{{ route('account.cart') }}'">
                                    Reorder
                                </button>
                            </div>
                        </article>

                        <article class="order-card">
                            <div class="order-card__header">
                                <div>
                                    <div class="order-card__id">Order #12343</div>
                                    <div class="order-card__date">Placed on 20 February 2025</div>
                                </div>
                                <div class="order-card__status order-card__status--cancelled">Cancelled</div>
                            </div>
                            <div class="order-card__meta">
                                <div><span>Items:</span> <strong>2</strong></div>
                                <div><span>Total:</span> <strong>$150.00</strong></div>
                                <div><span>Delivery:</span> <strong>Standard</strong></div>
                            </div>
                            <div class="order-card__actions">
                                <button class="account-btn account-btn--secondary" type="button" onclick="window.location.href='{{ route('account.cart') }}'">
                                    Reorder items
                                </button>
                            </div>
                        </article>
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
