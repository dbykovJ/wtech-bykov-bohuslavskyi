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
                        @forelse ($orders as $order)
                            @php
                                $statusClass = match ($order->status) {
                                    'delivered', 'completed' => 'order-card__status--delivered',
                                    'cancelled' => 'order-card__status--cancelled',
                                    default => 'order-card__status--processing',
                                };
                                $itemsCount = $order->items->sum('quantity');
                                $orderId = str_pad($order->id, 5, '0', STR_PAD_LEFT);
                            @endphp
                            <article class="order-card">
                                <div class="order-card__header">
                                    <div>
                                        <div class="order-card__id">Order #{{ $orderId }}</div>
                                        <div class="order-card__date">Placed on {{ $order->created_at->format('d M Y') }}</div>
                                    </div>
                                    <div class="order-card__status {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</div>
                                </div>
                                <div class="order-card__meta">
                                    <div><span>Items:</span> <strong>{{ $itemsCount }}</strong></div>
                                    <div><span>Total:</span> <strong>${{ number_format($order->total, 2) }}</strong></div>
                                    <div><span>Delivery:</span> <strong>${{ number_format($order->delivery_fee, 2) }}</strong></div>
                                </div>
                                <div class="order-card__actions">
                                    <button class="account-btn account-btn--secondary" type="button" data-order-open="{{ $order->id }}">
                                        View details
                                    </button>
                                    <form method="POST" action="{{ route('account.orders.reorder', $order) }}">
                                        @csrf
                                        <button class="account-btn" type="submit">Reorder</button>
                                    </form>
                                </div>
                            </article>

                            <div class="order-modal" id="order-modal-{{ $order->id }}" aria-hidden="true">
                                <div class="order-modal__backdrop" data-order-close></div>
                                <div class="order-modal__content" role="dialog" aria-modal="true">
                                    <div class="order-modal__header">
                                        <div>
                                            <div class="order-modal__title">Order #{{ $orderId }}</div>
                                            <div class="order-modal__subtitle">Placed on {{ $order->created_at->format('d M Y') }}</div>
                                        </div>
                                        <button type="button" class="order-modal__close" data-order-close>×</button>
                                    </div>
                                    <div class="order-modal__body">
                                        <div class="order-modal__section">
                                            <div class="order-modal__section-title">Items</div>
                                            <div class="order-modal__items">
                                                @foreach ($order->items as $item)
                                                    <div class="order-modal__item">
                                                        <div>
                                                            <div class="order-modal__item-name">{{ $item->product?->name ?? 'Product' }}</div>
                                                            <div class="order-modal__item-meta">Qty: {{ $item->quantity }} · Size: {{ $item->size }}</div>
                                                        </div>
                                                        <div class="order-modal__item-price">${{ number_format($item->line_total, 2) }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="order-modal__section">
                                            <div class="order-modal__section-title">Summary</div>
                                            <div class="order-modal__summary">
                                                <div><span>Subtotal</span><strong>${{ number_format($order->subtotal_before_discount, 2) }}</strong></div>
                                                <div><span>Discount</span><strong>-${{ number_format($order->discount_total, 2) }}</strong></div>
                                                <div><span>Delivery</span><strong>${{ number_format($order->delivery_fee, 2) }}</strong></div>
                                                <div class="order-modal__summary-total"><span>Total</span><strong>${{ number_format($order->total, 2) }}</strong></div>
                                            </div>
                                        </div>
                                        <div class="order-modal__section">
                                            <div class="order-modal__section-title">Shipping</div>
                                            <div class="order-modal__shipping">
                                                <div>{{ $order->shipping_full_name }}</div>
                                                <div>{{ $order->shipping_street }}</div>
                                                <div>{{ $order->shipping_city }} {{ $order->shipping_postal_code }}</div>
                                                <div>{{ $order->shipping_country }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p>Your order history is empty.</p>
                        @endforelse
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

    document.querySelectorAll('[data-order-open]').forEach(button => {
        button.addEventListener('click', () => {
            const modal = document.getElementById(`order-modal-${button.dataset.orderOpen}`);
            if (modal) {
                modal.classList.add('order-modal--open');
                modal.setAttribute('aria-hidden', 'false');
            }
        });
    });

    document.querySelectorAll('[data-order_close]').forEach(button => {
        button.addEventListener('click', () => {
            const modal = button.closest('.order-modal');
            if (modal) {
                modal.classList.remove('order-modal--open');
                modal.setAttribute('aria-hidden', 'true');
            }
        });
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.order-modal.order-modal--open').forEach(modal => {
                modal.classList.remove('order-modal--open');
                modal.setAttribute('aria-hidden', 'true');
            });
        }
    });
</script>
@endpush
