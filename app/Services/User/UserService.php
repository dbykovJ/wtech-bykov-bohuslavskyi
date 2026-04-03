<?php
namespace App\Services\User;
use App\Models\User;

class UserService
{
    public function getAllUsers(){
        return User::all();
    }

    public function getUserById(int $id){
        return User::find($id);
    }

    public function getUserByEmail(string $email){
        return User::where('email', $email)->first();
    }

    public function createUser($data){

        $validated = $data->validate([
            'name' => 'required|string|max:64',
            'email' => 'required|string|email|max:128|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        return User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);
    }

}
