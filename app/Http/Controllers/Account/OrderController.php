<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Cart\AuthorizedCartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $orders = $user->orders()
            ->with(['items.product.images', 'items.review'])
            ->latest()
            ->paginate(10);

        return view('account.orders', [
            'orders' => $orders,
        ]);
    }

    public function reorder(Order $order, AuthorizedCartService $cartService)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $cartService->addOrderItems($order->load('items'));
        } catch (ValidationException $e) {
            return redirect()->route('account.orders')->withErrors($e->errors());
        }

        return redirect()->route('account.cart')->with('success', 'Товари додано до кошика.');
    }
}
