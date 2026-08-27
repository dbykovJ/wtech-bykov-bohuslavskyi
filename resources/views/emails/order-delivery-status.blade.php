<!doctype html>
<html lang="uk">
<body style="margin:0;background:#f4f4f2;color:#171717;font-family:Arial,sans-serif;padding:30px 12px;">
@php
    $labels = ['processing' => 'прийнято в обробку', 'shipped' => 'передано в доставку', 'delivered' => 'доставлено', 'completed' => 'завершено', 'cancelled' => 'скасовано'];
@endphp
<div style="max-width:600px;margin:auto;background:#fff;border-radius:16px;overflow:hidden;">
    <div style="padding:26px 30px;background:#111;color:#fff;">
        <div style="font-size:12px;letter-spacing:2px;text-transform:uppercase;">Look of Today</div>
        <h1 style="margin:10px 0 0;font-size:24px;">Замовлення {{ $labels[$order->status] ?? 'оновлено' }}</h1>
    </div>
    <div style="padding:30px;">
        <p>Вітаємо, {{ $order->shipping_full_name }}!</p>
        <p style="color:#606060;line-height:1.6;">Статус замовлення №{{ $order->id }} змінено: <strong>{{ $labels[$order->status] ?? $order->status }}</strong>.</p>
        @if ($order->tracking_number)
            <div style="margin:22px 0;padding:18px;border-radius:10px;background:#f4f4f2;">
                <div style="color:#777;font-size:12px;">Служба доставки</div>
                <strong>{{ $order->delivery_carrier ?: 'Не вказано' }}</strong>
                <div style="margin-top:12px;color:#777;font-size:12px;">Номер відстеження</div>
                <strong style="font-size:18px;letter-spacing:1px;">{{ $order->tracking_number }}</strong>
            </div>
        @endif
        <p style="margin-bottom:0;color:#777;font-size:12px;">Якщо у вас є запитання, дайте відповідь на цей лист або дочекайтеся дзвінка менеджера.</p>
    </div>
</div>
</body>
</html>
