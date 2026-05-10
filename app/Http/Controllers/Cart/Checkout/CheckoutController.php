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
use App\Enums\PaymentMethod;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly AuthorizedCartService $cartItemService,
        private readonly GuestCartService      $guestCartService,
        private readonly CheckoutService       $checkoutService,
    ) {
    }

    public function personalData()
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
                'checkout' => 'Your cart is empty.',
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
                'checkout' => 'Your cart is empty.',
            ]);
        }

        $address = session('checkout_address');
        if (!$address || !is_array($address)) {
            return redirect()->route('checkout.personal-data')->withErrors([
                'checkout' => 'Please confirm your personal data first.',
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
        $request->merge([
            'cardholder_name' => trim((string) $request->input('cardholder_name')),
            'card_number' => preg_replace('/\s+/', '', (string) $request->input('card_number')),
            'card_expiry' => str_replace(' ', '', (string) $request->input('card_expiry')),
            'card_cvc' => preg_replace('/\s+/', '', (string) $request->input('card_cvc')),
        ]);

        $data = $request->validate([
            'payment_method' => ['required', Rule::enum(PaymentMethod::class)],
            'cardholder_name' => ['required_if:payment_method,' . PaymentMethod::card->value, 'string', 'max:120'],
            'card_number' => ['required_if:payment_method,' . PaymentMethod::card->value, 'regex:/^\d{16}$/'],
            'card_expiry' => ['required_if:payment_method,' . PaymentMethod::card->value, 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'card_cvc' => ['required_if:payment_method,' . PaymentMethod::card->value, 'regex:/^\d{3,4}$/'],
        ]);

        $address = $request->session()->get('checkout_address');
        if (!$address || !is_array($address)) {
            return redirect()->route('checkout.personal-data')->withErrors([
                'checkout' => 'Please confirm your personal data first.',
            ]);
        }

        try {
            $this->checkoutService->processPaymentForUser(Auth::user(), $address, $data['payment_method']);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        if (Auth::check()) {
            $this->cartItemService->clear();
            $this->cartItemService->removePromoCode();
        } else {
            $this->guestCartService->clear();
        }

        $request->session()->forget('checkout_address');

        return redirect()->route('order-confirm')->with('success', 'Payment accepted. Your order is confirmed.');
    }
}
