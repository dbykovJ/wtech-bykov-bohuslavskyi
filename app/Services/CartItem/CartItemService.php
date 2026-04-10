<?php


namespace App\Services\CartItem;

    use App\Models\CartItem;
    use App\Models\ItemColorSizeCount;
    use Illuminate\Http\Client\Request;
    use Illuminate\Validation\ValidationException;
class CartItemService
{
    public function getCart()
    {
        return auth()->user()->cartItems()->with('product', 'color')->get();
    }

    public function addToCart(Request $request)
    {

        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'color_id'   => 'required|integer|exists:colors,id',
            'size'       => 'required|string|in:XS,S,M,L,XL,XXL',
            'quantity'   => 'required|integer|min:1|max:99',
        ]);

        $this->validateColorSizeCounts($validated['product_id'], $validated['color_id'], $validated['size'], $validated['count']);

        $existingItem = CartItem::where('user_id', auth()->id())
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
            'user_id'    => auth()->id(),
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
            ->where('user_id', auth()->id())
            ->delete();
    }

    public function updateQuantity($cartItemId, $count)
    {
        CartItem::where('id', $cartItemId)
            ->where('user_id', auth()->id())
            ->update(['count' => $count]);
    }

    public function clearCart()
    {
        CartItem::where('user_id', auth()->id())->delete();
    }
}
