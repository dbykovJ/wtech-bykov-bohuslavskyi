<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@example.com')],
            [
                'name'     => 'Admin User',
                'password' => env('ADMIN_PASSWORD', 'password'),
                'is_admin' => true,
            ]
        );
    }
}
