<?php

namespace App\Http\Controllers\Cart\Checkout;

use App\Http\Controllers\Controller;
use App\Http\Requests\Checkout\StartCheckoutRequest;
use App\Models\Order;
use App\Services\Cart\AuthorizedCartService;
use App\Services\Cart\GuestCartService;
use App\Services\Checkout\CheckoutService;
use App\Services\Payment\LiqPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly AuthorizedCartService $cartItemService,
        private readonly GuestCartService      $guestCartService,
        private readonly CheckoutService       $checkoutService,
        private readonly LiqPayService         $liqPayService,
    ) {
    }

    public function personalData()
    {
        $cartData = Auth::check()
            ? $this->cartItemService->getCart()
            : $this->guestCartService->getCart();

        if ($cartData['items']->isEmpty()) {
            return redirect()->route('cart')->withErrors([
                'checkout' => 'Ваш кошик порожній.',
            ]);
        }

        $lastOrder = null;
        if (Auth::check()) {
            $lastOrder = Order::where('user_id', Auth::id())
                ->where('status', 'paid')
                ->latest('paid_at')
                ->first();
        }

        return view('personal-data', [
            'cartItems'   => $cartData['items'],
            'cartSummary' => $cartData['summary'],
            'lastOrder'   => $lastOrder,
            'address'     => session('checkout_address', []),
        ]);
    }

    public function storePersonalData(StartCheckoutRequest $request)
    {
        $cartData = Auth::check()
            ? $this->cartItemService->getCart()
            : $this->guestCartService->getCart();

        if ($cartData['items']->isEmpty()) {
            return redirect()->route('cart')->withErrors([
                'checkout' => 'Ваш кошик порожній.',
            ]);
        }

        $request->session()->put('checkout_address', $request->validated());

        return redirect()->route('payment');
    }

    public function payment()
    {
        $cartData = Auth::check()
            ? $this->cartItemService->getCart()
            : $this->guestCartService->getCart();

        if ($cartData['items']->isEmpty()) {
            return redirect()->route('cart')->withErrors([
                'checkout' => 'Ваш кошик порожній.',
            ]);
        }

        $address = session('checkout_address');
        if (!$address || !is_array($address)) {
            return redirect()->route('checkout.personal-data')->withErrors([
                'checkout' => 'Будь ласка, спочатку підтвердьте свої особисті дані.',
            ]);
        }

        return view('payment', [
            'cartItems'   => $cartData['items'],
            'cartSummary' => $cartData['summary'],
            'address'     => $address,
        ]);
    }

    public function pay(Request $request)
    {
        $address = $request->session()->get('checkout_address');
        if (!$address || !is_array($address)) {
            return redirect()->route('checkout.personal-data')->withErrors([
                'checkout' => 'Будь ласка, спочатку підтвердьте свої особисті дані.',
            ]);
        }

        try {
            $order = $this->checkoutService->createPendingOrder(Auth::user(), $address);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        }

        $checkout = $this->liqPayService->buildCheckoutPayload($order);

        return view('liqpay-redirect', $checkout);
    }

    public function confirm(Request $request)
    {
        $order = null;
        $orderId = $request->query('order_id');

        if ($orderId) {
            $order = Order::find($orderId);
        }

        return view('order-confirm', [
            'order' => $order,
        ]);
    }
}
