<?php

namespace App\Services\Payment;

use App\Models\Order;

class LiqPayService
{
    private const CHECKOUT_URL = 'https://www.liqpay.ua/api/3/checkout';

    /**
     * Build the signed `data`/`signature` pair for LiqPay's hosted checkout form.
     *
     * @return array{data: string, signature: string, url: string}
     */
    public function buildCheckoutPayload(Order $order): array
    {
        $params = [
            'version' => 3,
            'public_key' => config('services.liqpay.public_key'),
            'action' => 'pay',
            'amount' => number_format((float) $order->total, 2, '.', ''),
            'currency' => strtoupper($order->currency),
            'description' => "Замовлення №{$order->id}",
            'order_id' => (string) $order->id,
            'result_url' => route('order-confirm', ['order_id' => $order->id]),
            'server_url' => route('liqpay.webhook'),
        ];

        $data = base64_encode(json_encode($params));
        $signature = $this->sign($data);

        return [
            'data' => $data,
            'signature' => $signature,
            'url' => self::CHECKOUT_URL,
        ];
    }

    public function verifySignature(string $data, string $signature): bool
    {
        return hash_equals($this->sign($data), $signature);
    }

    private function sign(string $data): string
    {
        $privateKey = (string) config('services.liqpay.private_key');

        return base64_encode(sha1($privateKey . $data . $privateKey, true));
    }
}
