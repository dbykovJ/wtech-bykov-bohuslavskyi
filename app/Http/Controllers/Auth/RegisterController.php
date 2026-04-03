<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{

    public function __construct(protected UserService $userService){}

    public function __invoke(Request $request)
    {
        $user = $this->userService->createUser($request);

        Auth::login($user);

        return redirect('/account')->with('success', 'Welcome to SuperSell!');
    }
}
