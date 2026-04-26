<?php

namespace App\Http\Controllers\Cart\CartItem;

use App\Http\Controllers\Controller;
use App\Services\CartItem\CartItemService;
use App\Services\CartItem\GuestCartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CartItemController extends Controller
{
    public function __construct(
        private CartItemService $cartItemService,
        private GuestCartService $guestCartService,
    ) {}

    public function accountCart()
    {
        $cartData = Auth::check()
            ? $this->cartItemService->getCart()
            : $this->guestCartService->getCart();

        return view('account.cart', [
            'cartItems'   => $cartData['items'],
            'cartSummary' => $cartData['summary'],
        ]);
    }

    public function add(Request $request)
    {
        try {
            if (Auth::check()) {
                $this->cartItemService->addToCart($request);
            } else {
                $validated = $request->validate([
                    'product_id' => 'required|integer|exists:products,id',
                    'color_id'   => 'required|integer|exists:colors,id',
                    'size'       => 'required|string|in:XS,S,M,L,XL,XXL',
                    'count'      => 'required|integer|min:1|max:99',
                ]);
                $this->guestCartService->add($validated);
            }

            return redirect()->route('cart')->with('success', 'Item added to cart!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        }
    }

    public function remove(string $cartItemId)
    {
        if (Auth::check()) {
            $this->cartItemService->removeFromCart($cartItemId);
        } else {
            $this->guestCartService->remove($cartItemId);
        }

        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    public function update(Request $request, string $cartItemId)
    {
        $validated = $request->validate([
            'count' => 'required|integer|min:1|max:99',
        ]);

        if (Auth::check()) {
            $this->cartItemService->updateQuantity($cartItemId, $validated['count']);
        } else {
            $this->guestCartService->updateQuantity($cartItemId, $validated['count']);
        }

        return redirect()->back()->with('success', 'Cart updated!');
    }

    public function clear()
    {
        if (Auth::check()) {
            $this->cartItemService->clearCart();
        } else {
            $this->guestCartService->clear();
        }

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
