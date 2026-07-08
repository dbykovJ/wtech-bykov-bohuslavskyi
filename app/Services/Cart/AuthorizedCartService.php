<?php

namespace App\Services\Cart;

use App\Models\CartItem;
use App\Models\ItemColorSizeCount;
use App\Models\Sale;
use App\Models\User;
use App\Services\Loyalty\LoyaltyService;
use App\Services\PromoCode\PromoCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthorizedCartService
{
    private const DELIVERY_FEE = 15.00;

    public function __construct(
        private PromoCodeService $promo,
        private LoyaltyService $loyalty,
    ) {}

    public function getCart(): array
    {
        /** @var User $user */
        $user = Auth::user();

        return $this->getCartForUser($user);
    }

    public function getCartForUser(User $user): array
    {
        $appliedPromoSale = $this->getAppliedPromoSale();
        $loyaltyDiscountPercent = $this->loyalty->getDiscountPercent($user);

        $cartItems = $user->cartItems()
            ->with([
                'product.images',
                'product.allSales',
                'color',
            ])
            ->get()
            ->map(function (CartItem $item) use ($appliedPromoSale, $loyaltyDiscountPercent) {
                $baseUnitPrice = (float) $item->product->price;
                $activeSales = $item->product->allSales;
                $salesDiscountPercent = max(min((float) $activeSales->whereNull('promo_code')->sum('discount'), 100.0), 0.0);

                $promoDiscountPercent = 0.0;
                if ($appliedPromoSale && $activeSales->contains('id', $appliedPromoSale->id)) {
                    $promoDiscountPercent = max(min((float) $appliedPromoSale->discount, 100.0), 0.0);
                }

                // Each discount source stacks non-additively on the previous step's price.
                $unitPriceAfterSales = round($baseUnitPrice * (1 - ($salesDiscountPercent / 100)), 2);
                $unitPriceAfterPromo = round(max($unitPriceAfterSales * (1 - ($promoDiscountPercent / 100)), 0.0), 2);
                $discountedUnitPrice = round(max($unitPriceAfterPromo * (1 - ($loyaltyDiscountPercent / 100)), 0.0), 2);

                $lineBaseSubtotal = round($baseUnitPrice * $item->count, 2);
                $lineSubtotal = round($discountedUnitPrice * $item->count, 2);
                $lineSaleDiscount = round(($baseUnitPrice - $unitPriceAfterSales) * $item->count, 2);
                $linePromoDiscount = round(($unitPriceAfterSales - $unitPriceAfterPromo) * $item->count, 2);
                $lineLoyaltyDiscount = round(($unitPriceAfterPromo - $discountedUnitPrice) * $item->count, 2);
                $discountPercent = $baseUnitPrice > 0
                    ? min(round((1 - ($discountedUnitPrice / $baseUnitPrice)) * 100, 2), 100.0)
                    : 0.0;

                $item->setAttribute('discount_percent', $discountPercent);
                $item->setAttribute('sales_discount_percent', $salesDiscountPercent);
                $item->setAttribute('promo_discount_percent', $promoDiscountPercent);
                $item->setAttribute('loyalty_discount_percent', $loyaltyDiscountPercent);
                $item->setAttribute('base_unit_price', $baseUnitPrice);
                $item->setAttribute('discounted_unit_price', $discountedUnitPrice);
                $item->setAttribute('line_base_subtotal', $lineBaseSubtotal);
                $item->setAttribute('line_subtotal', $lineSubtotal);
                $item->setAttribute('line_sales_discount', $lineSaleDiscount);
                $item->setAttribute('line_promo_discount', $linePromoDiscount);
                $item->setAttribute('line_loyalty_discount', $lineLoyaltyDiscount);

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
        $loyaltyDiscountTotal = round((float) $cartItems->sum('line_loyalty_discount'), 2);
        $discountTotal = round($subtotalBeforeDiscount - $subtotal, 2);
        $deliveryFee = $cartItems->isEmpty() ? 0.0 : self::DELIVERY_FEE;
        $total = round($subtotal + $deliveryFee, 2);

        return [
            'promo_code' => $appliedPromoSale?->promo_code,
            'subtotal_before_discount' => $subtotalBeforeDiscount,
            'subtotal' => $subtotal,
            'sales_discount_total' => $salesDiscountTotal,
            'promo_discount_total' => $promoDiscountTotal,
            'loyalty_discount_total' => $loyaltyDiscountTotal,
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
                'size' => 'Обрана комбінація кольору та розміру недоступна.'
            ]);
        }

        if ($count < 1) {
            throw ValidationException::withMessages([
                'count' => "Кількість має бути не менше 1."
            ]);
        }

        if ($count > $stock) {
            throw ValidationException::withMessages([
                'count' => "Доступно лише {$stock} шт. для цієї комбінації."
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
        $this->promo->remove();
    }


    public function applyPromoCode(string $promoCode): Sale
    {
        $productIds = $this->getProductIds();
        return $this->promo->apply($promoCode, $productIds);
    }

    public function removePromoCode(): void
    {
        $this->promo->remove();
    }

    private function getAppliedPromoSale(): ?Sale
    {
        return $this->promo->getApplied();
    }

    private function getProductIds(): Collection
    {
        return CartItem::query()
            ->where('user_id', Auth::id())
            ->pluck('product_id')
            ->unique();
    }

    public function addOrderItems(\App\Models\Order $order): void
    {
        foreach ($order->items as $item) {
            $sizeValue = $item->size instanceof \App\Enums\Size ? $item->size->value : (string) $item->size;

            $existingItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $item->product_id)
                ->where('color_id', $item->color_id)
                ->where('size', $sizeValue)
                ->first();

            $newCount = $item->quantity + ($existingItem?->count ?? 0);
            $this->validateColorSizeCounts($item->product_id, $item->color_id, $sizeValue, $newCount);

            if ($existingItem) {
                $existingItem->update(['count' => $newCount]);
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $item->product_id,
                    'color_id' => $item->color_id,
                    'size' => $sizeValue,
                    'count' => $item->quantity,
                ]);
            }
        }
    }
}
