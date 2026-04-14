@extends('layouts.app')

@section('title', 'Your Cart — SUPERSELL')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/account.css') }}" />
<link rel="stylesheet" href="{{ asset('css/cart.css') }}" />
<link rel="stylesheet" href="{{ asset('css/user-cart.css') }}" />
@endpush

@section('content')
<main class="cart-main">
    <div class="container">
        <h1 class="cart-title heading">YOUR CART</h1>

        @include('partials.account-nav')

        <div class="cart-layout">
            <div class="cart-items">
                @forelse ($cartItems as $item)
                <div class="cart-item">
                    @php
                        $imageUrl = $item->product->getFirstImage()?->public_url;
                    @endphp
                    @if($imageUrl)
                        <img src="{{ $imageUrl }}" alt="{{ $item->product->name }}" class="cart-item__image" />
                    @else
                        <div class="placeholder cart-item__image"></div>
                    @endif
                    <div class="cart-item__info">
                        <div class="cart-item__top">
                            <div class="cart-item__name">{{ $item->product->name }}</div>
                            <form method="POST" action="/cart/{{ $item->id }}">
                                @csrf @method('DELETE')
                                <button class="cart-item__delete" aria-label="Remove item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                        <path d="M10 11v6M14 11v6"/>
                                        <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                        <div class="cart-item__meta">
                            <span>Size: <strong>{{ $item->size->value }}</strong></span>
                            <span>Color: <strong>{{ $item->color->name }}</strong></span>
                        </div>
                        <div class="cart-item__bottom">
                            @if ($item->discount_percent > 0)
                                <span class="cart-item__price">${{ number_format($item->discounted_unit_price, 2) }}</span>
                                <span class="cart-item__price" style="text-decoration: line-through; color: #999;">${{ number_format($item->base_unit_price, 2) }}</span>
                                <span class="badge-red">-{{ number_format($item->discount_percent) }}%</span>
                            @else
                                <span class="cart-item__price">${{ number_format($item->base_unit_price, 2) }}</span>
                            @endif
                            <div class="quantity">
                                <button class="quantity__btn">−</button>
                                <span class="quantity__val">{{ $item->count }}</span>
                                <button class="quantity__btn">+</button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <p>Your cart is empty.</p>
                @endforelse
            </div>

            <div class="order-summary">
                <h2 class="order-summary__title">Order Summary</h2>

                <div class="order-summary__rows">
                    <div class="order-summary__row">
                        <span>Subtotal</span>
                        <span>${{ number_format($cartSummary['subtotal_before_discount'] ?? 0, 2) }}</span>
                    </div>
                    <div class="order-summary__row">
                        <span>Discount</span>
                        <span class="order-summary__discount">-${{ number_format($cartSummary['discount_total'] ?? 0, 2) }}</span>
                    </div>
                    <div class="order-summary__row">
                        <span>Delivery Fee</span>
                        <span>${{ number_format($cartSummary['delivery_fee'] ?? 0, 2) }}</span>
                    </div>
                </div>

                <div class="order-summary__divider"></div>

                <div class="order-summary__total">
                    <span>Total</span>
                    <span>${{ number_format($cartSummary['total'] ?? 0, 2) }}</span>
                </div>

                <div class="order-summary__promo">
                    <div class="promo-input">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="1.8">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/>
                            <circle cx="7" cy="7" r="1" fill="#aaa" stroke="none"/>
                        </svg>
                        <input type="text" placeholder="Add promo code" />
                    </div>
                    <button class="promo-apply-btn">Apply</button>
                </div>

                <button class="checkout-btn" onclick="window.location.href='{{ route('payment') }}'">
                    Go to Checkout
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    document.querySelector('.account-menu-toggle')?.addEventListener('click', function () {
        document.querySelector('.account-nav')?.classList.toggle('account-nav--open');
    });
</script>
@endpush
