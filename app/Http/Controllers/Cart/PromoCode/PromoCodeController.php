<?php

namespace App\Http\Controllers\Cart\PromoCode;

use App\Http\Controllers\Controller;
use App\Services\Cart\AuthorizedCartService;
use App\Services\Cart\GuestCartService;
use App\Services\PromoCode\PromoCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromoCodeController extends Controller
{
    public function apply(Request $request, AuthorizedCartService $cartService, GuestCartService $guestService): RedirectResponse
    {
        $request->validate(['promo_code' => 'required|string']);

        $service = Auth::check() ? $cartService : $guestService;
        $service->applyPromoCode($request->input('promo_code'));

        return redirect()->route('cart')->with('success', 'Промокод застосовано!');
    }

    public function remove(AuthorizedCartService $cartService, GuestCartService $guestService): RedirectResponse
    {
        $service = Auth::check() ? $cartService : $guestService;
        $service->removePromoCode();

        return redirect()->route('cart');
    }
}
