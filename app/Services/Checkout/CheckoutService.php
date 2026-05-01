<?php

namespace App\Services\Checkout;

use App\Enums\Size;
use App\Models\CartItem;
use App\Models\ItemColorSizeCount;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Services\Cart\AuthorizedCartService;
use App\Services\Cart\GuestCartService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class CheckoutService
{
    public function __construct(
        private readonly AuthorizedCartService $cartItemService,
        private readonly GuestCartService      $guestCartService
    ) {
    }

    public function createStripeSessionForUser(?User $user, array $address): string
    {
        $order = DB::transaction(function () use ($user, $address): Order {
            if ($user) {
                $cartData = $this->cartItemService->getCartForUser($user);
            } else {
                $cartData = $this->guestCartService->getCart();
            }

            if ($cartData['items']->isEmpty()) {
                throw ValidationException::withMessages([
                    'checkout' => 'Your cart is empty.',
                ]);
            }

            $summary = $cartData['summary'];

            $order = Order::create([
                'user_id' => $user?->id,
                'status' => 'pending_payment',
                'currency' => strtolower((string) config('services.stripe.currency', 'usd')),
                'promo_code' => $summary['promo_code'] ?? null,
                'subtotal_before_discount' => $summary['subtotal_before_discount'],
                'sales_discount_total' => $summary['sales_discount_total'],
                'promo_discount_total' => $summary['promo_discount_total'],
                'discount_total' => $summary['discount_total'],
                'subtotal' => $summary['subtotal'],
                'delivery_fee' => $summary['delivery_fee'],
                'total' => $summary['total'],
                'shipping_full_name' => $address['full_name'],
                'shipping_email' => $address['email'],
                'shipping_phone' => $address['phone'],
                'shipping_street' => $address['street'],
                'shipping_city' => $address['city'],
                'shipping_postal_code' => $address['postal_code'],
                'shipping_country' => $address['country'],
            ]);

            foreach ($cartData['items'] as $item) {
                $size = $item->size instanceof Size
                    ? $item->size->value
                    : (is_object($item->size) ? $item->size->value : (string) $item->size);

                $order->items()->create([
                    'product_id' => $item->product_id,
                    'color_id' => $item->color_id,
                    'size' => $size,
                    'quantity' => (int) $item->count,
                    'unit_price_before_discount' => $item->base_unit_price,
                    'unit_sales_discount_percent' => $item->sales_discount_percent,
                    'unit_promo_discount_percent' => $item->promo_discount_percent,
                    'unit_discount_percent' => $item->discount_percent,
                    'unit_price_after_discount' => $item->discounted_unit_price,
                    'line_total' => $item->line_subtotal,
                ]);
            }

            $order = $order->fresh('items.product');
            $this->assertOrderTotalsAreConsistent($order);

            return $order;
        });

        [$sessionId, $sessionUrl] = $this->createStripeCheckoutSession($order);
        $order->update(['stripe_checkout_session_id' => $sessionId]);

        return $sessionUrl;
    }

    private function assertOrderTotalsAreConsistent(Order $order): void
    {
        $itemsTotal = round((float) $order->items->sum('line_total'), 2);
        $expectedTotal = round($itemsTotal + (float) $order->delivery_fee, 2);
        $actualTotal = round((float) $order->total, 2);

        if (abs($expectedTotal - $actualTotal) > 0.009) {
            throw ValidationException::withMessages([
                'checkout' => 'Cart totals changed. Please review your cart and try checkout again.',
            ]);
        }
    }

    public function handleCheckoutSessionCompleted(array $session): void
    {
        $sessionId = Arr::get($session, 'id');
        if (!is_string($sessionId) || $sessionId === '') {
            return;
        }

        DB::transaction(function () use ($session, $sessionId): void {
            $order = Order::query()
                ->where('stripe_checkout_session_id', $sessionId)
                ->lockForUpdate()
                ->first();

            if (!$order || $order->status === 'paid') {
                return;
            }

            $paymentStatus = Arr::get($session, 'payment_status');
            if ($paymentStatus !== 'paid') {
                return;
            }

            $items = $order->items()->get();

            foreach ($items as $item) {
                $stockRow = ItemColorSizeCount::query()
                    ->where('item_id', $item->product_id)
                    ->where('color_id', $item->color_id)
                    ->where('size', $item->size)
                    ->lockForUpdate()
                    ->first();

                if (!$stockRow || $stockRow->count < $item->quantity) {
                    $order->update(['status' => 'failed']);

                    Payment::updateOrCreate(
                        ['order_id' => $order->id, 'provider' => 'stripe'],
                        [
                            'provider_payment_id' => Arr::get($session, 'payment_intent'),
                            'status' => 'failed',
                            'amount' => $order->total,
                            'currency' => $order->currency,
                            'payload' => $session,
                        ]
                    );

                    return;
                }

                ItemColorSizeCount::query()
                    ->where('item_id', $item->product_id)
                    ->where('color_id', $item->color_id)
                    ->where('size', $item->size)
                    ->update(['count' => $stockRow->count - $item->quantity]);
            }

            $order->update([
                'status' => 'paid',
                'stripe_payment_intent_id' => Arr::get($session, 'payment_intent'),
                'paid_at' => now(),
            ]);

            Payment::updateOrCreate(
                ['order_id' => $order->id, 'provider' => 'stripe'],
                [
                    'provider_payment_id' => Arr::get($session, 'payment_intent'),
                    'status' => 'paid',
                    'amount' => $order->total,
                    'currency' => $order->currency,
                    'payload' => $session,
                ]
            );

            if ($order->user_id) {
                CartItem::query()->where('user_id', $order->user_id)->delete();
            }
        });
    }

    public function isStripeSignatureValid(string $payload, ?string $signatureHeader): bool
    {
        $secret = (string) config('services.stripe.webhook_secret');
        if ($secret === '' || !is_string($signatureHeader) || $signatureHeader === '') {
            return false;
        }

        $parts = [];
        foreach (explode(',', $signatureHeader) as $pair) {
            [$key, $value] = array_pad(explode('=', trim($pair), 2), 2, null);
            if ($key !== null && $value !== null) {
                $parts[$key][] = $value;
            }
        }

        $timestamp = $parts['t'][0] ?? null;
        $signatures = $parts['v1'] ?? [];

        if (!$timestamp || empty($signatures) || !ctype_digit($timestamp)) {
            return false;
        }

        if (abs(time() - (int) $timestamp) > 300) {
            return false;
        }

        $expected = hash_hmac('sha256', $timestamp . '.' . $payload, $secret);

        foreach ($signatures as $signature) {
            if (hash_equals($expected, $signature)) {
                return true;
            }
        }

        return false;
    }

    private function createStripeCheckoutSession(Order $order): array
    {
        $secret = (string) config('services.stripe.secret');
        if ($secret === '') {
            throw new RuntimeException('Stripe secret key is not configured.');
        }

        $currency = strtolower((string) config('services.stripe.currency', 'usd'));
        $lineItems = [];

        foreach ($order->items as $item) {
            $unitAmount = (int) round(((float) $item->unit_price_after_discount) * 100);
            $lineItems[] = [
                'price_data' => [
                    'currency' => $currency,
                    'unit_amount' => max($unitAmount, 0),
                    'product_data' => [
                        'name' => $item->product?->name ?? ('Product #' . $item->product_id),
                    ],
                ],
                'quantity' => (int) $item->quantity,
            ];
        }

        if ((float) $order->delivery_fee > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => $currency,
                    'unit_amount' => (int) round(((float) $order->delivery_fee) * 100),
                    'product_data' => [
                        'name' => 'Delivery fee',
                    ],
                ],
                'quantity' => 1,
            ];
        }

        $response = Http::asForm()
            ->withBasicAuth($secret, '')
            ->post('https://api.stripe.com/v1/checkout/sessions', [
                'mode' => 'payment',
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel'),
                'client_reference_id' => (string) $order->id,
                'payment_method_types' => ['card'],
                'customer_email' => $order->shipping_email,
                'metadata' => [
                    'order_id' => (string) $order->id,
                    'user_id' => (string) $order->user_id,
                    'promo_code' => (string) ($order->promo_code ?? ''),
                ],
                'line_items' => $lineItems,
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Stripe checkout session creation failed: ' . Str::limit($response->body(), 500));
        }

        $id = $response->json('id');
        $url = $response->json('url');

        if (!is_string($id) || !is_string($url) || $id === '' || $url === '') {
            throw new RuntimeException('Stripe returned an invalid checkout session payload.');
        }

        return [$id, $url];
    }
}

