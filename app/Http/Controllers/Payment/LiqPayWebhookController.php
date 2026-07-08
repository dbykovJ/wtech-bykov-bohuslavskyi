<?php

namespace App\Http\Controllers\Payment;

use App\Enums\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentWebhookEvent;
use App\Services\Checkout\CheckoutService;
use App\Services\Payment\LiqPayService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LiqPayWebhookController extends Controller
{
    public function __construct(
        private readonly LiqPayService $liqPayService,
        private readonly CheckoutService $checkoutService,
    ) {
    }

    public function handle(Request $request): Response
    {
        $data = (string) $request->input('data');
        $signature = (string) $request->input('signature');

        if ($data === '' || $signature === '' || !$this->liqPayService->verifySignature($data, $signature)) {
            return response('invalid signature', 400);
        }

        $payload = json_decode(base64_decode($data), true) ?? [];
        $paymentId = (string) ($payload['payment_id'] ?? '');
        $orderId = $payload['order_id'] ?? null;
        $status = $payload['status'] ?? null;

        if ($paymentId === '' || !$orderId) {
            return response('missing fields', 400);
        }

        $event = PaymentWebhookEvent::firstOrCreate(
            ['event_id' => $paymentId],
            ['type' => $status, 'payload' => $payload]
        );

        if ($event->wasRecentlyCreated === false && $event->processed_at !== null) {
            return response('ok', 200);
        }

        if (in_array($status, ['success', 'sandbox'], true)) {
            $order = Order::find($orderId);

            if ($order) {
                $this->checkoutService->confirmPayment(
                    $order,
                    $this->mapPaymentMethod($payload['paytype'] ?? null),
                    'liqpay',
                    $paymentId,
                );
            }
        }

        $event->update(['processed_at' => now()]);

        return response('ok', 200);
    }

    private function mapPaymentMethod(?string $paytype): PaymentMethod
    {
        return match ($paytype) {
            'apay' => PaymentMethod::apple_pay,
            'gpay' => PaymentMethod::google_pay,
            default => PaymentMethod::card,
        };
    }
}
