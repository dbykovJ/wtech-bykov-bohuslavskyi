<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Етикетка замовлення №{{ $order->id }}</title>
    <style>
        *{box-sizing:border-box} body{margin:0;background:#eee;font-family:Arial,sans-serif;color:#111}.toolbar{padding:16px;text-align:center}.toolbar button{border:0;border-radius:999px;background:#111;color:#fff;padding:12px 24px;cursor:pointer}.label{width:148mm;min-height:100mm;margin:0 auto 24px;background:#fff;border:2px solid #111;padding:10mm}.label__top{display:flex;justify-content:space-between;gap:20px;border-bottom:2px solid #111;padding-bottom:7mm}.brand{font-size:20px;font-weight:800;letter-spacing:1px}.order-id{font-size:24px;font-weight:800}.section{padding:6mm 0;border-bottom:1px solid #bbb}.section h2{margin:0 0 3mm;font-size:11px;text-transform:uppercase;letter-spacing:1px}.recipient{font-size:18px;font-weight:700}.address{margin-top:2mm;font-size:14px;line-height:1.5}.meta{display:grid;grid-template-columns:1fr 1fr;gap:6mm}.tracking{font-size:20px;font-weight:800;letter-spacing:1px;overflow-wrap:anywhere}.footer{display:flex;justify-content:space-between;padding-top:5mm;font-size:11px}.items{max-width:70%}@media print{body{background:#fff}.toolbar{display:none}.label{margin:0;border:2px solid #111}}
    </style>
</head>
<body>
<div class="toolbar"><button onclick="window.print()">Друкувати / Зберегти як PDF</button></div>
<main class="label">
    <div class="label__top"><div><div class="brand">LOOK OF TODAY</div><div>{{ $order->delivery_carrier ?: 'Служба доставки' }}</div></div><div class="order-id">#{{ $order->id }}</div></div>
    <div class="section"><h2>Одержувач</h2><div class="recipient">{{ $order->shipping_full_name }}</div><div class="address">{{ $order->shipping_phone }}<br>{{ $order->shipping_street }}<br>{{ $order->shipping_city }}, {{ $order->shipping_postal_code }} · {{ $order->shipping_country }}</div></div>
    <div class="section meta"><div><h2>Спосіб доставки</h2><strong>{{ $order->shipping_method?->value ?? '—' }}</strong></div><div><h2>Номер відстеження</h2><div class="tracking">{{ $order->tracking_number ?: 'НЕ ПРИЗНАЧЕНО' }}</div></div></div>
    <div class="footer"><div class="items">{{ $order->items->sum('quantity') }} од. · {{ $order->items->pluck('product.name')->join(', ') }}</div><strong>Вага уточнюється</strong></div>
</main>
</body>
</html>
