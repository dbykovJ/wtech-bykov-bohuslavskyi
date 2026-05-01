<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Services\Cart\AuthorizedCartService;
use App\Services\Password\PasswordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{

    public function __construct(private PasswordService $passwordService) {}
    public function update(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'Password'         => ['required', 'min:8', 'confirmed'],
        ]);

        $this->passwordService->updatePassword(Auth::user(), $validated['Password']);

        return back()->with('success', 'Password updated successfully.');
    }
}

