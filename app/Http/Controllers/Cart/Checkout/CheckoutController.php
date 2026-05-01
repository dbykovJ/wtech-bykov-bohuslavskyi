<?php

namespace App\Http\Controllers\Cart\Checkout;

use App\Http\Controllers\Controller;
use App\Http\Requests\Checkout\StartCheckoutRequest;
use App\Models\Order;
use App\Services\Cart\AuthorizedCartService;
use App\Services\Cart\GuestCartService;
use App\Services\Checkout\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly AuthorizedCartService $cartItemService,
        private readonly GuestCartService      $guestCartService,
        private readonly CheckoutService       $checkoutService,
    ) {
    }

    public function payment()
    {
        $cartData = Auth::check()
            ? $this->cartItemService->getCart()
            : $this->guestCartService->getCart();

        if ($cartData['items']->isEmpty()) {
            return redirect()->route('cart')->withErrors([
                'checkout' => 'Your cart is empty.',
            ]);
        }

        $lastOrder = null;
        if (Auth::check()) {
            $lastOrder = Order::where('user_id', Auth::id())
                ->where('status', 'paid')
                ->latest('paid_at')
                ->first();
        }

        return view('payment', [
            'cartItems'   => $cartData['items'],
            'cartSummary' => $cartData['summary'],
            'lastOrder'   => $lastOrder,
        ]);
    }

    public function start(StartCheckoutRequest $request)
    {
        try {
            $checkoutUrl = $this->checkoutService->createStripeSessionForUser(
                Auth::user(),
                $request->validated()
            );

            return redirect()->away($checkoutUrl);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (RuntimeException $e) {
            return redirect()->back()->withErrors([
                'checkout' => $e->getMessage(),
            ])->withInput();
        }
    }

    public function success(Request $request)
    {
        $sessionId = (string) $request->query('session_id', '');

        $query = Order::where('stripe_checkout_session_id', $sessionId);

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        }

        $order = $query->first();

        if (!$order) {
            return redirect()->route('payment')->withErrors([
                'checkout' => 'We could not verify your Stripe session.',
            ]);
        }

        if ($order->status !== 'paid') {
            return redirect()->route('payment')->withErrors([
                'checkout' => 'Payment is still processing. Please refresh in a few seconds.',
            ]);
        }

        if (Auth::check()) {
            $this->cartItemService->removePromoCode();
        } else {
            $this->guestCartService->clear();
        }

        if (!session('success')) {
            return redirect()->route('cart');
        }

        return view('order-confirm');    }

    public function cancel()
    {
        return redirect()->route('payment')->withErrors([
            'checkout' => 'Payment was cancelled.',
        ]);
    }
}
