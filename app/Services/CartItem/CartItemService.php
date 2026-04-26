<?php

namespace App\Services\CartItem;

use App\Models\CartItem;
use App\Models\ItemColorSizeCount;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CartItemService
{
    private const DELIVERY_FEE = 15.00;
    private const PROMO_SESSION_KEY = 'cart.promo_code';

    public function getCart(): array
    {
        /** @var User $user */
        $user = Auth::user();

        return $this->getCartForUser($user);
    }

    public function getCartForUser(User $user): array
    {
        $appliedPromoSale = $this->getAppliedPromoSale();

        $cartItems = $user->cartItems()
            ->with([
                'product.images',
                'product.allSales',
                'color',
            ])
            ->get()
            ->map(function (CartItem $item) use ($appliedPromoSale) {
                $baseUnitPrice = (float) $item->product->price;
                $activeSales = $item->product->allSales;
                $salesDiscountPercent = max(min((float) $activeSales->whereNull('promo_code')->sum('discount'), 100.0), 0.0);

                $promoDiscountPercent = 0.0;
                if ($appliedPromoSale && $activeSales->contains('id', $appliedPromoSale->id)) {
                    $promoDiscountPercent = max(min((float) $appliedPromoSale->discount, 100.0), 0.0);
                }

                // Apply promo discount to the already discounted sale price (non-additive).
                $unitPriceAfterSales = round($baseUnitPrice * (1 - ($salesDiscountPercent / 100)), 2);
                $discountedUnitPrice = round(max($unitPriceAfterSales * (1 - ($promoDiscountPercent / 100)), 0.0), 2);
                $lineBaseSubtotal = round($baseUnitPrice * $item->count, 2);
                $lineSubtotal = round($discountedUnitPrice * $item->count, 2);
                $lineSaleDiscount = round(($baseUnitPrice - $unitPriceAfterSales) * $item->count, 2);
                $linePromoDiscount = round(($unitPriceAfterSales - $discountedUnitPrice) * $item->count, 2);
                $discountPercent = $baseUnitPrice > 0
                    ? min(round((1 - ($discountedUnitPrice / $baseUnitPrice)) * 100, 2), 100.0)
                    : 0.0;

                $item->setAttribute('discount_percent', $discountPercent);
                $item->setAttribute('sales_discount_percent', $salesDiscountPercent);
                $item->setAttribute('promo_discount_percent', $promoDiscountPercent);
                $item->setAttribute('base_unit_price', $baseUnitPrice);
                $item->setAttribute('discounted_unit_price', $discountedUnitPrice);
                $item->setAttribute('line_base_subtotal', $lineBaseSubtotal);
                $item->setAttribute('line_subtotal', $lineSubtotal);
                $item->setAttribute('line_sales_discount', $lineSaleDiscount);
                $item->setAttribute('line_promo_discount', $linePromoDiscount);

                return $item;
            });

        return [
            'items' => $cartItems,
            'summary' => $this->buildSummary($cartItems, $appliedPromoSale),
        ];
    }

    public function buildSummary(Collection $cartItems, ?Sale $appliedPromoSale): array
    {
        $subtotalBeforeDiscount = round((float) $cartItems->sum('line_base_subtotal'), 2);
        $subtotal = round((float) $cartItems->sum('line_subtotal'), 2);
        $salesDiscountTotal = round((float) $cartItems->sum('line_sales_discount'), 2);
        $promoDiscountTotal = round((float) $cartItems->sum('line_promo_discount'), 2);
        $discountTotal = round($subtotalBeforeDiscount - $subtotal, 2);
        $deliveryFee = $cartItems->isEmpty() ? 0.0 : self::DELIVERY_FEE;
        $total = round($subtotal + $deliveryFee, 2);

        return [
            'promo_code' => $appliedPromoSale?->promo_code,
            'subtotal_before_discount' => $subtotalBeforeDiscount,
            'subtotal' => $subtotal,
            'sales_discount_total' => $salesDiscountTotal,
            'promo_discount_total' => $promoDiscountTotal,
            'discount_total' => $discountTotal,
            'delivery_fee' => $deliveryFee,
            'total' => $total,
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
        session()->forget(self::PROMO_SESSION_KEY);
    }

    public function applyPromoCode(string $promoCode): Sale
    {
        $normalizedPromoCode = strtoupper(trim($promoCode));

        if ($normalizedPromoCode === '') {
            throw ValidationException::withMessages([
                'promo_code' => 'Please enter a promo code.',
            ]);
        }

        $promoSales = $this->findPromoSalesByCode($normalizedPromoCode);

        if ($promoSales->isEmpty()) {
            throw ValidationException::withMessages([
                'promo_code' => 'Promo code is invalid.',
            ]);
        }

        if ($promoSales->count() > 1) {
            throw ValidationException::withMessages([
                'promo_code' => 'Promo code is duplicated in the system. Please contact support.',
            ]);
        }

        /** @var Sale $promoSale */
        $promoSale = $promoSales->first();

        $hasEligibleProduct = CartItem::query()
            ->where('user_id', Auth::id())
            ->whereHas('product.allSales', function ($query) use ($promoSale) {
                $query->where('sales.id', $promoSale->id);
            })
            ->exists();

        if (!$hasEligibleProduct) {
            throw ValidationException::withMessages([
                'promo_code' => 'This promo code does not apply to products in your cart.',
            ]);
        }

        session([self::PROMO_SESSION_KEY => $promoSale->promo_code]);

        return $promoSale;
    }

    public function removePromoCode(): void
    {
        session()->forget(self::PROMO_SESSION_KEY);
    }

    private function getAppliedPromoSale(): ?Sale
    {
        $promoCode = session(self::PROMO_SESSION_KEY);

        if (!is_string($promoCode) || trim($promoCode) === '') {
            return null;
        }

        $promoSales = $this->findPromoSalesByCode($promoCode);

        if ($promoSales->count() !== 1) {
            session()->forget(self::PROMO_SESSION_KEY);

            return null;
        }

        /** @var Sale $sale */
        $sale = $promoSales->first();

        return $sale;
    }

    private function findPromoSalesByCode(string $promoCode): Collection
    {
        $normalizedPromoCode = strtoupper(trim($promoCode));

        return Sale::query()
            ->whereNotNull('promo_code')
            ->whereRaw('UPPER(TRIM(promo_code)) = ?', [$normalizedPromoCode])
            ->get();
    }
}
