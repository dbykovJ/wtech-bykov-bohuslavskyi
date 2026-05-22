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
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public function __construct(
        private readonly AuthorizedCartService $cartItemService,
        private readonly GuestCartService      $guestCartService
    ) {
    }

    public function processPaymentForUser(?User $user, array $address, string $paymentMethod): Order
    {
        return DB::transaction(function () use ($user, $address, $paymentMethod): Order {
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
            $deliveryMethod = $address['delivery_method'] ?? DeliveryMethod::truck->value;

            $order = Order::create([
                'user_id' => $user?->id,
                'status' => 'pending_payment',
                'currency' => 'usd',
                'promo_code' => $summary['promo_code'] ?? null,
                'subtotal_before_discount' => $summary['subtotal_before_discount'],
                'sales_discount_total' => $summary['sales_discount_total'],
                'promo_discount_total' => $summary['promo_discount_total'],
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
                    'unit_discount_percent' => $item->discount_percent,
                    'unit_price_after_discount' => $item->discounted_unit_price,
                    'line_total' => $item->line_subtotal,
                ]);
            }

            $order = $order->fresh('items.product');
            $this->assertOrderTotalsAreConsistent($order);

            $items = $order->items()->get();
            foreach ($items as $item) {
                $stockRow = ItemColorSizeCount::query()
                    ->where('item_id', $item->product_id)
                    ->where('color_id', $item->color_id)
                    ->where('size', $item->size)
                    ->lockForUpdate()
                    ->first();

                if (!$stockRow || $stockRow->count < $item->quantity) {
                    throw ValidationException::withMessages([
                        'checkout' => 'Some items are out of stock. Please review your cart.',
                    ]);
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
                'payment_method' => $paymentMethod,
            ]);

            Payment::updateOrCreate(
                ['order_id' => $order->id, 'provider' => $paymentMethod],
                [
                    'provider_payment_id' => null,
                    'status' => 'paid',
                    'amount' => $order->total,
                    'currency' => $order->currency,
                    'payload' => ['method' => $paymentMethod],
                ]
            );

            if ($order->user_id) {
                CartItem::query()->where('user_id', $order->user_id)->delete();
            }

            return $order;
        });
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

}

