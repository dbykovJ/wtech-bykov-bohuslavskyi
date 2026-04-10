<?php

namespace App\Http\Controllers\CartItem;

use App\Http\Controllers\Controller;
use App\Services\CartItem\CartItemService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartItemController extends Controller
{
    public function __construct(protected CartItemService $cartService) {}

    public function index()
    {
        $cartItems = $this->cartService->getCart();
        return view('cart', ['cartItems' => $cartItems]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'color_id'   => 'required|integer|exists:colors,id',
            'size'       => 'required|string|in:XS,S,M,L,XL,XXL',
            'quantity'   => 'required|integer|min:1|max:99',
        ]);

        try {
            $this->cartService->addToCart($validated);
            return redirect()->back()->with('success', 'Item added to cart!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        }
    }

    public function remove($cartItemId)
    {
        $this->cartService->removeFromCart($cartItemId);
        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    public function update(Request $request, $cartItemId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $this->cartService->updateQuantity($cartItemId, $validated['quantity']);

        return redirect()->back()->with('success', 'Cart updated!');
    }

    public function clear()
    {
        $this->cartService->clearCart();
        return redirect()->back()->with('success', 'Cart cleared!');
    }
}
