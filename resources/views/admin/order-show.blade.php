@extends('layouts.admin')

@section('title', 'Замовлення №'.$order->id)

@section('content')
<div class="admin-order-heading">
    <div>
        <a href="{{ route('admin.orders') }}" class="admin-back-link">← Усі замовлення</a>
        <h1 class="admin-page-title">Замовлення №{{ $order->id }}</h1>
    </div>
    <a href="{{ route('admin.orders.shipping-label', $order) }}" target="_blank" class="admin-secondary-btn">Друкувати етикетку</a>
</div>

@if (session('success')) <div class="admin-success">{{ session('success') }}</div> @endif

<div class="admin-order-grid">
    <section class="admin-detail-card">
        <h2>Клієнт і доставка</h2>
        <dl class="admin-detail-list">
            <div><dt>Ім’я</dt><dd>{{ $order->shipping_full_name }}</dd></div>
            <div><dt>Email</dt><dd>{{ $order->shipping_email }}</dd></div>
            <div><dt>Телефон</dt><dd>{{ $order->shipping_phone }}</dd></div>
            <div><dt>Адреса</dt><dd>{{ $order->shipping_street }}, {{ $order->shipping_city }}, {{ $order->shipping_postal_code }}, {{ $order->shipping_country }}</dd></div>
            <div><dt>Спосіб</dt><dd>{{ $order->shipping_method?->value ?? '—' }}</dd></div>
        </dl>
    </section>

    <section class="admin-detail-card">
        <h2>Керування доставкою</h2>
        <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="admin-status-form">
            @csrf @method('PATCH')
            <label>Статус
                <select name="status" required>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $order->status) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label>Служба доставки
                <input name="delivery_carrier" value="{{ old('delivery_carrier', $order->delivery_carrier) }}" placeholder="Наприклад: Nova Poshta" maxlength="80">
            </label>
            <label>Номер відстеження
                <input name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="Номер накладної" maxlength="120">
            </label>
            @if ($errors->any()) <div class="field-error">{{ $errors->first() }}</div> @endif
            <button class="admin-primary-btn" type="submit">Зберегти та повідомити клієнта</button>
        </form>
    </section>
</div>

<section class="admin-detail-card admin-items-card">
    <h2>Товари</h2>
    @foreach ($order->items as $item)
        <div class="admin-order-item">
            <div><strong>{{ $item->product->name }}</strong><span>{{ $item->size->value }} · {{ $item->color?->name }} · {{ $item->quantity }} шт.</span></div>
            <strong>${{ number_format($item->line_total, 2) }}</strong>
        </div>
    @endforeach
    <div class="admin-order-total"><span>Разом</span><strong>${{ number_format($order->total, 2) }}</strong></div>
</section>
@endsection
