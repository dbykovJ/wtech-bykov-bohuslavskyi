<?php

namespace App\Services\CartItem;

use App\Models\CartItem;
use App\Models\ItemColorSizeCount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CartItemService
{
    private const DELIVERY_FEE = 15.00;

    public function getCart(): array
    {
        /** @var User $user */
        $user = Auth::user();
        $now = now();

        $activeSalesScope = function ($query) use ($now) {
            $query->where('sales.valid_to', '>', $now)
                ->where('sales.valid_from', '<', $now)
                ->whereNull('sales.promo_code');
        };

        $cartItems = $user->cartItems()
            ->with([
                'product.images',
                'product.sales' => $activeSalesScope,
                'color',
            ])
            ->get()
            ->map(function (CartItem $item) {
                $baseUnitPrice = (float) $item->product->price;
                $discountPercent = min((float) $item->product->sales->sum('discount'), 100.0);
                $discountedUnitPrice = round($baseUnitPrice * (1 - ($discountPercent / 100)), 2);
                $lineBaseSubtotal = round($baseUnitPrice * $item->count, 2);
                $lineSubtotal = round($discountedUnitPrice * $item->count, 2);

                $item->setAttribute('discount_percent', $discountPercent);
                $item->setAttribute('base_unit_price', $baseUnitPrice);
                $item->setAttribute('discounted_unit_price', $discountedUnitPrice);
                $item->setAttribute('line_base_subtotal', $lineBaseSubtotal);
                $item->setAttribute('line_subtotal', $lineSubtotal);

                return $item;
            });

        $subtotalBeforeDiscount = round((float) $cartItems->sum('line_base_subtotal'), 2);
        $subtotal = round((float) $cartItems->sum('line_subtotal'), 2);
        $discountTotal = round($subtotalBeforeDiscount - $subtotal, 2);
        $deliveryFee = $cartItems->isEmpty() ? 0.0 : self::DELIVERY_FEE;
        $total = round($subtotal + $deliveryFee, 2);

        return [
            'items' => $cartItems,
            'summary' => [
                'subtotal_before_discount' => $subtotalBeforeDiscount,
                'subtotal' => $subtotal,
                'discount_total' => $discountTotal,
                'delivery_fee' => $deliveryFee,
                'total' => $total,
            ],
        ];
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'color_id'   => 'required|integer|exists:colors,id',
            'size'       => 'required|string|in:XS,S,M,L,XL,XXL',
            'count'      => 'required|integer|min:1|max:99',
        ]);

        $this->validateColorSizeCounts($validated['product_id'], $validated['color_id'], $validated['size'], $validated['count']);

        $existingItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->where('color_id', $validated['color_id'])
            ->where('size', $validated['size'])
            ->first();

        if ($existingItem) {
            $this->validateColorSizeCounts(
                $validated['product_id'],
                $validated['color_id'],
                $validated['size'],
                $existingItem->count + $validated['count']
            );
            $existingItem->increment('count', $validated['count']);

            return $existingItem;
        }

        return CartItem::create([
            'user_id'    => Auth::id(),
            'product_id' => $validated['product_id'],
            'color_id'   => $validated['color_id'],
            'size'       => $validated['size'],
            'count'   => $validated['count'],
        ]);
    }

    private function validateColorSizeCounts($productId, $colorId, $size, $count)
    {
        $stock = ItemColorSizeCount::where('item_id', $productId)
            ->where('color_id', $colorId)
            ->where('size', $size)
            ->value('count');

        if (!$stock) {
            throw ValidationException::withMessages([
                'size' => 'The selected color and size combination is not available.'
            ]);
        }

        if ($count < 1) {
            throw ValidationException::withMessages([
                'count' => 'Count must be at least 1.'
            ]);
        }

        if ($count > $stock) {
            throw ValidationException::withMessages([
                'count' => "Only {$stock} items available for this combination."
            ]);
        }
    }


    public function removeFromCart($cartItemId)
    {
        CartItem::where('id', $cartItemId)
            ->where('user_id', Auth::id())
            ->delete();
    }

    public function updateQuantity($cartItemId, $count)
    {
        CartItem::where('id', $cartItemId)
            ->where('user_id', Auth::id())
            ->update(['count' => $count]);
    }

    public function clearCart()
    {
        CartItem::where('user_id', Auth::id())->delete();
    }
}
