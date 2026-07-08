<?php

namespace App\Services\Checkout;

use App\Enums\DeliveryMethod;
use App\Enums\PaymentMethod;
use App\Enums\Size;
use App\Models\CartItem;
use App\Models\ItemColorSizeCount;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Services\Cart\AuthorizedCartService;
use App\Services\Cart\GuestCartService;
use App\Services\Loyalty\LoyaltyService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public function __construct(
        private readonly AuthorizedCartService $cartItemService,
        private readonly GuestCartService      $guestCartService,
        private readonly LoyaltyService        $loyalty,
    ) {
    }

    /**
     * Create an order from the user's cart in `pending_payment` status.
     * Stock is NOT decremented and the order is NOT marked paid here —
     * that only happens once a real payment is confirmed, via confirmPayment().
     */
    public function createPendingOrder(?User $user, array $address): Order
    {
        return DB::transaction(function () use ($user, $address): Order {
            if ($user) {
                $cartData = $this->cartItemService->getCartForUser($user);
            } else {
                $cartData = $this->guestCartService->getCart();
            }

            if ($cartData['items']->isEmpty()) {
                throw ValidationException::withMessages([
                    'checkout' => 'Ваш кошик порожній.',
                ]);
            }

            $summary = $cartData['summary'];
            $deliveryMethod = $address['delivery_method'] ?? DeliveryMethod::truck->value;
            $loyaltyDiscountPercent = $user ? $this->loyalty->getDiscountPercent($user) : 0.0;

            $order = Order::create([
                'user_id' => $user?->id,
                'status' => 'pending_payment',
                'currency' => 'usd',
                'promo_code' => $summary['promo_code'] ?? null,
                'subtotal_before_discount' => $summary['subtotal_before_discount'],
                'sales_discount_total' => $summary['sales_discount_total'],
                'promo_discount_total' => $summary['promo_discount_total'],
                'loyalty_discount_percent' => $loyaltyDiscountPercent,
                'loyalty_discount_total' => $summary['loyalty_discount_total'] ?? 0,
                'discount_total' => $summary['discount_total'],
                'subtotal' => $summary['subtotal'],
                'delivery_fee' => $summary['delivery_fee'],
                'total' => $summary['total'],
                'shipping_method' => $deliveryMethod,
                'shipping_full_name' => $address['full_name'],
                'shipping_email' => $address['email'],
                'shipping_phone' => $address['phone'],
                'shipping_street' => $address['street'],
                'shipping_city' => $address['city'],
                'shipping_postal_code' => $address['postal_code'],
                'shipping_country' => $address['country'],
            ]);

            foreach ($cartData['items'] as $item) {
                $size = $item->size instanceof Size ? $item->size->value : (string) $item->size;

                $order->items()->create([
                    'product_id' => $item->product_id,
                    'color_id' => $item->color_id,
                    'size' => $size,
                    'quantity' => (int) $item->count,
                    'unit_price_before_discount' => $item->base_unit_price,
                    'unit_sales_discount_percent' => $item->sales_discount_percent,
                    'unit_promo_discount_percent' => $item->promo_discount_percent,
                    'unit_loyalty_discount_percent' => $item->loyalty_discount_percent ?? 0,
                    'unit_discount_percent' => $item->discount_percent,
                    'unit_price_after_discount' => $item->discounted_unit_price,
                    'line_total' => $item->line_subtotal,
                ]);
            }

            $order = $order->fresh('items.product');
            $this->assertOrderTotalsAreConsistent($order);

            return $order;
        });
    }

    /**
     * Confirm a real payment for a pending order: decrements stock, marks the
     * order paid, records the Payment row, and clears the buyer's cart.
     * Idempotent — safe to call more than once for the same order (gateways
     * may redeliver webhook notifications).
     */
    public function confirmPayment(Order $order, PaymentMethod $method, string $provider, string $transactionId): void
    {
        if ($order->status === 'paid') {
            return;
        }

        DB::transaction(function () use ($order, $method, $provider, $transactionId) {
            $items = $order->items()->get();

            foreach ($items as $item) {
                $stockRow = ItemColorSizeCount::query()
                    ->where('item_id', $item->product_id)
                    ->where('color_id', $item->color_id)
                    ->where('size', $item->size)
                    ->lockForUpdate()
                    ->first();

                if (!$stockRow || $stockRow->count < $item->quantity) {
                    // Payment already succeeded on the gateway's side — we can't
                    // reject it here. Log for manual follow-up (backorder/refund)
                    // instead of failing the whole confirmation.
                    Log::warning('Order paid but insufficient stock at confirmation', [
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'color_id' => $item->color_id,
                        'size' => $item->size,
                    ]);
                    continue;
                }

                ItemColorSizeCount::query()
                    ->where('item_id', $item->product_id)
                    ->where('color_id', $item->color_id)
                    ->where('size', $item->size)
                    ->update(['count' => $stockRow->count - $item->quantity]);
            }

            $order->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_method' => $method,
                'payment_transaction_id' => $transactionId,
            ]);

            Payment::updateOrCreate(
                ['order_id' => $order->id, 'provider' => $provider],
                [
                    'provider_payment_id' => $transactionId,
                    'status' => 'paid',
                    'amount' => $order->total,
                    'currency' => $order->currency,
                    'payload' => ['method' => $method->value],
                ]
            );

            if ($order->user_id) {
                CartItem::query()->where('user_id', $order->user_id)->delete();
            }
        });
    }

    private function assertOrderTotalsAreConsistent(Order $order): void
    {
        $itemsTotal = round((float) $order->items->sum('line_total'), 2);
        $expectedTotal = round($itemsTotal + (float) $order->delivery_fee, 2);
        $actualTotal = round((float) $order->total, 2);

        if (abs($expectedTotal - $actualTotal) > 0.009) {
            throw ValidationException::withMessages([
                'checkout' => 'Сума кошика змінилася. Будь ласка, перегляньте кошик і спробуйте оформити замовлення знову.',
            ]);
        }
    }

}

