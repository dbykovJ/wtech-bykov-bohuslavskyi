<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscription;
use App\Services\Cart\GuestCartService;
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

        $subscription = NewsletterSubscription::firstOrNew(['email' => strtolower(trim($user->email))]);
        $subscription->user_id = $user->id;
        $subscription->save();

        return redirect()->route('home')->with('success', 'Ласкаво просимо до Look of Today!');
    }
}
