<!doctype html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Замовлення підтверджено</title>
</head>
<body style="margin:0;background:#f4f4f2;color:#171717;font-family:Arial,sans-serif;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f2;padding:32px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:620px;background:#fff;border-radius:16px;overflow:hidden;">
                <tr>
                    <td style="padding:28px 32px;background:#111;color:#fff;">
                        <div style="font-size:12px;letter-spacing:2px;text-transform:uppercase;">Look of Today</div>
                        <h1 style="margin:10px 0 0;font-size:25px;line-height:1.25;">Оплату підтверджено</h1>
                    </td>
                </tr>
                <tr>
                    <td style="padding:30px 32px;">
                        <p style="margin:0 0 10px;font-size:16px;">Вітаємо, {{ $order->shipping_full_name }}!</p>
                        <p style="margin:0 0 24px;color:#606060;font-size:14px;line-height:1.6;">Ми отримали оплату за замовлення №{{ $order->id }}. Наш менеджер зв’яжеться з вами протягом 15 хвилин, щоб уточнити деталі.</p>

                        <h2 style="margin:0 0 12px;font-size:18px;">Придбані товари</h2>
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                            @foreach ($order->items as $item)
                                <tr>
                                    <td style="padding:14px 0;border-bottom:1px solid #e8e8e8;">
                                        <strong style="display:block;font-size:14px;">{{ $item->product->name }}</strong>
                                        <span style="color:#777;font-size:12px;">{{ $item->size->value }} · {{ $item->color?->name }} · {{ $item->quantity }} шт.</span>
                                    </td>
                                    <td align="right" style="padding:14px 0;border-bottom:1px solid #e8e8e8;font-size:14px;font-weight:bold;white-space:nowrap;">${{ number_format($item->line_total, 2) }}</td>
                                </tr>
                            @endforeach
                        </table>

                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:20px;">
                            <tr>
                                <td style="font-size:17px;font-weight:bold;">Сплачено</td>
                                <td align="right" style="font-size:22px;font-weight:bold;">${{ number_format($order->total, 2) }}</td>
                            </tr>
                        </table>

                        <p style="margin:26px 0 0;color:#777;font-size:12px;line-height:1.5;">Лист надіслано на {{ $order->shipping_email }} автоматично після підтвердження оплати.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
