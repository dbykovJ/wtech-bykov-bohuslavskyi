<?php

namespace App\Services\CartItem;

use App\Models\CartItem;
use App\Models\Color;
use App\Models\ItemColorSizeCount;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class GuestCartService
{
    private const SESSION_KEY = 'guest_cart';
    private const DELIVERY_FEE = 15.00;

    public function getCart(): array
    {
        $sessionItems = $this->all();

        if (empty($sessionItems)) {
            return [
                'items'   => collect(),
                'summary' => [
                    'subtotal_before_discount' => 0.0,
                    'subtotal'                 => 0.0,
                    'discount_total'           => 0.0,
                    'delivery_fee'             => 0.0,
                    'total'                    => 0.0,
                ],
            ];
        }

        $now = now();
        $activeSalesScope = fn ($q) => $q->where('sales.valid_to', '>', $now)
            ->where('sales.valid_from', '<', $now)
            ->whereNull('sales.promo_code');

        $productIds = array_unique(array_column($sessionItems, 'product_id'));
        $colorIds   = array_unique(array_column($sessionItems, 'color_id'));

        $products = Product::with(['images', 'sales' => $activeSalesScope])
            ->whereIn('id', $productIds)->get()->keyBy('id');
        $colors   = Color::whereIn('id', $colorIds)->get()->keyBy('id');

        $cartItems = collect();

        foreach ($sessionItems as $key => $data) {
            $product = $products[$data['product_id']] ?? null;
            $color   = $colors[$data['color_id']] ?? null;

            if (! $product || ! $color) {
                continue;
            }

            $baseUnitPrice      = (float) $product->price;
            $discountPercent    = min((float) $product->sales->sum('discount'), 100.0);
            $discountedPrice    = round($baseUnitPrice * (1 - $discountPercent / 100), 2);
            $count              = (int) $data['count'];

            $cartItems->push((object) [
                'id'                   => $key,
                'product'              => $product,
                'color'                => $color,
                'size'                 => (object) ['value' => $data['size']],
                'count'                => $count,
                'discount_percent'     => $discountPercent,
                'base_unit_price'      => $baseUnitPrice,
                'discounted_unit_price'=> $discountedPrice,
                'line_base_subtotal'   => round($baseUnitPrice * $count, 2),
                'line_subtotal'        => round($discountedPrice * $count, 2),
            ]);
        }

        $subtotalBeforeDiscount = round((float) $cartItems->sum('line_base_subtotal'), 2);
        $subtotal               = round((float) $cartItems->sum('line_subtotal'), 2);
        $discountTotal          = round($subtotalBeforeDiscount - $subtotal, 2);
        $deliveryFee            = $cartItems->isEmpty() ? 0.0 : self::DELIVERY_FEE;

        return [
            'items'   => $cartItems,
            'summary' => [
                'subtotal_before_discount' => $subtotalBeforeDiscount,
                'subtotal'                 => $subtotal,
                'discount_total'           => $discountTotal,
                'delivery_fee'             => $deliveryFee,
                'total'                    => round($subtotal + $deliveryFee, 2),
            ],
        ];
    }

    public function add(array $validated): void
    {
        $this->validateStock(
            $validated['product_id'],
            $validated['color_id'],
            $validated['size'],
            $validated['count']
        );

        $cart = $this->all();

        foreach ($cart as $key => &$item) {
            if ($item['product_id'] == $validated['product_id']
                && $item['color_id'] == $validated['color_id']
                && $item['size'] === $validated['size']
            ) {
                $this->validateStock(
                    $validated['product_id'],
                    $validated['color_id'],
                    $validated['size'],
                    $item['count'] + $validated['count']
                );
                $item['count'] += $validated['count'];
                session([self::SESSION_KEY => $cart]);
                return;
            }
        }

        $cart[Str::uuid()->toString()] = [
            'product_id' => $validated['product_id'],
            'color_id'   => $validated['color_id'],
            'size'       => $validated['size'],
            'count'      => $validated['count'],
        ];

        session([self::SESSION_KEY => $cart]);
    }

    public function remove(string $key): void
    {
        $cart = $this->all();
        unset($cart[$key]);
        session([self::SESSION_KEY => $cart]);
    }

    public function updateQuantity(string $key, int $count): void
    {
        $cart = $this->all();

        if (isset($cart[$key])) {
            $cart[$key]['count'] = $count;
            session([self::SESSION_KEY => $cart]);
        }
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function mergeIntoDb(int $userId): void
    {
        foreach ($this->all() as $item) {
            CartItem::updateOrCreate(
                [
                    'user_id'    => $userId,
                    'product_id' => $item['product_id'],
                    'color_id'   => $item['color_id'],
                    'size'       => $item['size'],
                ],
                ['count' => $item['count']]
            );
        }

        $this->clear();
    }

    public function all(): array
    {
        return session(self::SESSION_KEY, []);
    }

    private function validateStock(int $productId, int $colorId, string $size, int $count): void
    {
        $stock = ItemColorSizeCount::where('item_id', $productId)
            ->where('color_id', $colorId)
            ->where('size', $size)
            ->value('count');

        if (! $stock) {
            throw ValidationException::withMessages([
                'size' => 'The selected color and size combination is not available.',
            ]);
        }

        if ($count > $stock) {
            throw ValidationException::withMessages([
                'count' => "Only {$stock} items available for this combination.",
            ]);
        }
    }
}
