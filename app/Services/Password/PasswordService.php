<?php

namespace App\Services\Password;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PasswordService
{
    public function updatePassword(User $user, string $newPassword): void
    {
        $user->password = $newPassword;
        $user->save();

        Auth::login($user);
    }
}
