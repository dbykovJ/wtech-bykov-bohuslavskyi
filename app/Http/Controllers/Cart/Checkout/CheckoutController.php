<?php

namespace App\Http\Controllers\Cart\Checkout;

use App\Http\Controllers\Controller;
use App\Enums\PaymentMethod;
use App\Http\Requests\Checkout\StartCheckoutRequest;
use App\Mail\OrderPaidMail;
use App\Models\Order;
use App\Services\Cart\AuthorizedCartService;
use App\Services\Cart\GuestCartService;
use App\Services\Checkout\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
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

        $paymentCards = config('services.manual_payment.cards', []);

        if ($paymentCards === []) {
            return redirect()->back()->withErrors([
                'checkout' => 'Номер картки для оплати ще не налаштовано.',
            ]);
        }

        $validatedPayment = $request->validate([
            'payment_bank' => ['required', Rule::in(array_keys($paymentCards))],
            'payment_confirmed' => ['accepted'],
        ]);

        try {
            $order = $this->checkoutService->createPendingOrder(Auth::user(), $address);
            $this->checkoutService->confirmPayment(
                $order,
                PaymentMethod::card,
                'manual-'.$validatedPayment['payment_bank'],
                'manual-'.Str::uuid(),
            );
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput(
                $request->except('payment_confirmed')
            );
        }

        if (! Auth::check()) {
            $this->guestCartService->clear();
        }

        $request->session()->forget('checkout_address');

        try {
            Mail::to($order->shipping_email)->send(
                new OrderPaidMail($order->loadMissing(['items.product', 'items.color']))
            );
            $request->session()->flash('confirmation_email_sent', true);
        } catch (\Throwable $exception) {
            Log::error('Could not send the order confirmation email', [
                'order_id' => $order->id,
                'email' => $order->shipping_email,
                'exception' => $exception,
            ]);
            $request->session()->flash('confirmation_email_sent', false);
        }

        return redirect()->route('order-confirm', ['order_id' => $order->id]);
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
