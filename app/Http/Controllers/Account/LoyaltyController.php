<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Services\Loyalty\LoyaltyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoyaltyController extends Controller
{
    public function __construct(private readonly LoyaltyService $loyalty)
    {
    }

    public function create(): RedirectResponse
    {
        $this->loyalty->createCard(Auth::user());

        return redirect()->route('account')->with('success', 'Картку лояльності створено!');
    }
}
