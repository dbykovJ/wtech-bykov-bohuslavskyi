<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Http\Request;

class UserService
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function getUserById(int $id)
    {
        return User::find($id);
    }

    public function getUserByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:64',
            'email'    => 'required|string|email|max:128|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        return User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'],
        ]);
    }

    public function updatePersonalData(User $user, Request $request): void
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:64'],
            'email'    => ['required', 'email', 'max:128', 'unique:users,email,' . $user->id],
            'address'  => ['nullable', 'string', 'max:255'],
            'postcode' => ['nullable', 'integer', 'digits_between:1,7'],
            'city'     => ['nullable', 'string', 'max:128'],
            'country'  => ['nullable', 'string', 'max:128'],
        ]);

        $user->update($validated);
    }
}
