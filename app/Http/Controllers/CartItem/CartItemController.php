<?php

namespace App\Http\Controllers\CartItem;

use App\Http\Controllers\Controller;
use App\Services\CartItem\CartItemService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartItemController extends Controller
{
    public function __construct(protected CartItemService $cartItemService) {}

    public function accountCart()
    {
        $cartData = $this->cartItemService->getCart();

        $cartItems = $cartData['items'];
        $cartSummary = $cartData['summary'];

        return view('account.cart', compact('cartItems', 'cartSummary'));
    }


    public function add(Request $request)
    {
        try {
            $this->cartItemService->addToCart($request);
            return redirect()->route('cart')->with('success', 'Item added to cart!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        }
    }

    public function remove($cartItemId)
    {
        $this->cartItemService->removeFromCart($cartItemId);
        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    public function update(Request $request, $cartItemId)
    {
        $validated = $request->validate([
            'count' => 'required|integer|min:1|max:99',
        ]);

        $this->cartItemService->updateQuantity($cartItemId, $validated['count']);

        return redirect()->back()->with('success', 'Cart updated!');
    }

    public function clear()
    {
        $this->cartItemService->clearCart();
        return redirect()->back()->with('success', 'Cart cleared!');
    }

    public function applyPromo(Request $request)
    {
        $validated = $request->validate([
            'promo_code' => 'required|string|max:64',
        ]);

        try {
            $this->cartItemService->applyPromoCode($validated['promo_code']);
            return redirect()->back()->with('success', 'Promo code applied.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        }
    }

    public function removePromo()
    {
        $this->cartItemService->removePromoCode();
        return redirect()->back()->with('success', 'Promo code removed.');
    }
}
