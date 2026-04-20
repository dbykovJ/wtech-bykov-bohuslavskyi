<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\CartItem\GuestCartService;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{

    public function __construct(
        protected UserService $userService,
        protected GuestCartService $guestCartService,
    ) {}

    public function __invoke(Request $request)
    {
        $user = $this->userService->createUser($request);

        Auth::login($user);

        $this->guestCartService->mergeIntoDb($user->id);

        return redirect()->route('home')->with('success', 'Welcome to SuperSell!');
    }
}
