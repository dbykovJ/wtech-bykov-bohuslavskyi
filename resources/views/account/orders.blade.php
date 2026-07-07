@extends('layouts.app')

@section('title', 'Ваші замовлення — SUPERSELL')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/account.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/user-orders.css') }}" />
@endpush

@section('content')
    <main class="account-main">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Головна</a>
                <span class="breadcrumb__sep">›</span>
                <a href="{{ route('account') }}">Мій акаунт</a>
                <span class="breadcrumb__sep">›</span>
                <span class="breadcrumb__current">Замовлення</span>
            </div>

            <h1 class="account-title heading">МОЇ ЗАМОВЛЕННЯ</h1>

            <div class="account-layout">
                @include('partials.account-nav')

                <section class="account-content">
                    <div class="account-card">
                        <h2 class="account-card__title">Останні замовлення</h2>
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
                                    $statusLabels = [
                                        'pending_payment' => 'Очікує оплати',
                                        'paid'            => 'Оплачено',
                                        'processing'      => 'В обробці',
                                        'shipped'         => 'В дорозі',
                                        'delivered'       => 'Доставлено',
                                        'completed'       => 'Завершено',
                                        'cancelled'       => 'Скасовано',
                                    ];
                                    $statusLabel = $statusLabels[$order->status] ?? ucfirst(str_replace('_', ' ', $order->status));
                                @endphp
                                <article class="order-card">
                                    <div class="order-card__header">
                                        <div>
                                            <div class="order-card__id">Замовлення №{{ $orderId }}</div>
                                            <div class="order-card__date">Оформлено {{ $order->created_at->format('d.m.Y') }}</div>
                                        </div>
                                        <div class="order-card__status {{ $statusClass }}">{{ $statusLabel }}</div>
                                    </div>
                                    <div class="order-card__meta">
                                        <div><span>Товарів:</span> <strong>{{ $itemsCount }}</strong></div>
                                        <div><span>Разом:</span> <strong>${{ number_format($order->total, 2) }}</strong></div>
                                        <div><span>Доставка:</span> <strong>${{ number_format($order->delivery_fee, 2) }}</strong></div>
                                    </div>
                                    <div class="order-card__actions">
                                        <button class="account-btn account-btn--secondary" type="button" data-order-open="{{ $order->id }}">
                                            Детальніше
                                        </button>
                                        <form method="POST" action="{{ route('account.orders.reorder', $order) }}">
                                            @csrf
                                            <button class="account-btn" type="submit">Замовити знову</button>
                                        </form>
                                    </div>
                                </article>

                                <div class="order-modal" id="order-modal-{{ $order->id }}" aria-hidden="true">
                                    <div class="order-modal__backdrop" data-order-close></div>
                                    <div class="order-modal__content" role="dialog" aria-modal="true">
                                        <div class="order-modal__header">
                                            <div>
                                                <div class="order-modal__title">Замовлення №{{ $orderId }}</div>
                                                <div class="order-modal__subtitle">Оформлено {{ $order->created_at->format('d.m.Y') }}</div>
                                            </div>
                                            <button type="button" class="order-modal__close" data-order-close>×</button>
                                        </div>
                                        <div class="order-modal__body">
                                            <div class="order-modal__section">
                                                <div class="order-modal__section-title">Товари</div>
                                                <div class="order-modal__items">
                                                    @foreach ($order->items as $item)
                                                        <div class="order-modal__item">
                                                            <div style="width: 100%;">
                                                                <div class="order-modal__item-name">{{ $item->product?->name ?? 'Товар' }}</div>
                                                                <div class="order-modal__item-meta">К-сть: {{ $item->quantity }} · Розмір: {{ $item->size }}</div>
                                                                <div class="order-modal__item-price">${{ number_format($item->line_total, 2) }}</div>

                                                                @if (Auth::check() && in_array($order->status, ['delivered', 'completed']))
                                                                    @if ($item->review)
                                                                        <div style="margin-top: 12px; padding: 12px; border-radius: 4px; background-color: #f5f5f5;">
                                                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                                                                <span class="star-rating" style="--rating: {{ $item->review->rating }}">★★★★★</span>
                                                                                <form method="POST" action="{{ route('reviews.destroy', $item->review) }}" style="display: inline;">
                                                                                    @csrf @method('DELETE')
                                                                                    <button type="submit" class="account-btn account-btn--secondary" style="padding: 4px 12px; font-size: 12px;">Видалити</button>
                                                                                </form>
                                                                            </div>
                                                                            <p style="margin: 0; font-size: 14px; color: #666;">{{ $item->review->body }}</p>
                                                                            <small style="color: #999;">{{ $item->review->created_at->format('d.m.Y') }}</small>
                                                                        </div>
                                                                    @else
                                                                        <form method="POST" action="{{ route('reviews.store') }}" style="margin-top: 12px; padding: 12px; border-radius: 4px; background-color: #f5f5f5;">
                                                                            @csrf
                                                                            <input type="hidden" name="order_item_id" value="{{ $item->id }}">
                                                                            <div style="margin-bottom: 10px;">
                                                                                <label style="display: block; font-size: 12px; margin-bottom: 6px; font-weight: 500;">Оцінка:</label>
                                                                                <div style="display: flex; gap: 8px;">
                                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                                        <label style="display: flex; align-items: center; gap: 4px; cursor: pointer;">
                                                                                            <input type="radio" name="rating" value="{{ $i }}" style="cursor: pointer;">
                                                                                            <span style="font-size: 18px;">★</span>
                                                                                        </label>
                                                                                    @endfor
                                                                                </div>
                                                                                @error('rating')
                                                                                <div class="error-bubble">{{ $message }}</div>
                                                                                @enderror
                                                                            </div>
                                                                            <div style="margin-bottom: 10px;">
                                                                                <label style="display: block; font-size: 12px; margin-bottom: 6px; font-weight: 500;">Коментар (необов'язково):</label>
                                                                                <textarea name="body" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 12px;" rows="2" placeholder="Поділіться своїми думками..."></textarea>
                                                                                @error('body')
                                                                                <div class="error-bubble">{{ $message }}</div>
                                                                                @enderror
                                                                            </div>
                                                                            <button type="submit" class="account-btn" style="padding: 6px 16px; font-size: 12px;">Надіслати відгук</button>
                                                                        </form>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="order-modal__section">
                                                <div class="order-modal__section-title">Підсумок</div>
                                                <div class="order-modal__summary">
                                                    <div><span>Проміжна сума</span><strong>${{ number_format($order->subtotal_before_discount, 2) }}</strong></div>
                                                    <div><span>Знижка</span><strong>-${{ number_format($order->discount_total, 2) }}</strong></div>
                                                    <div><span>Доставка</span><strong>${{ number_format($order->delivery_fee, 2) }}</strong></div>
                                                    <div class="order-modal__summary-total"><span>Разом</span><strong>${{ number_format($order->total, 2) }}</strong></div>
                                                </div>
                                            </div>
                                            <div class="order-modal__section">
                                                <div class="order-modal__section-title">Адреса доставки</div>
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
                                <p>Історія ваших замовлень порожня.</p>
                            @endforelse
                        </div>
                        @if ($orders->hasPages())
                            <div style="margin-top: 24px;">
                                {{ $orders->links() }}
                            </div>
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
