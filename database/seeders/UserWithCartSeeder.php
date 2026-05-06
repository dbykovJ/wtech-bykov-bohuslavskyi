<?php

namespace Database\Seeders;

use App\Models\CartItem;
use App\Models\Color;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserWithCartSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name'     => 'Test User',
                'password' => bcrypt('password'),
                'is_admin' => false,
            ]
        );

        $products = Product::limit(3)->get();
        $color    = Color::first();
        $sizes    = ['S', 'M', 'L'];

        foreach ($products as $index => $product) {
            CartItem::firstOrCreate(
                [
                    'user_id'    => $user->id,
                    'product_id' => $product->id,
                    'color_id'   => $color->id,
                    'size'       => $sizes[$index],
                ],
                ['count' => rand(1, 3)]
            );
        }
    }
}
