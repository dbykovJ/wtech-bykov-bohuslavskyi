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
        $products = Product::all();
        $colors   = Color::all();
        $sizes    = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

        $users = [
            [
                'name'     => 'Test User',
                'email'    => 'test@example.com',
                'password' => 'password',
                'is_admin' => false,
                'cart'     => [
                    ['product_index' => 0, 'size' => 'M',  'qty' => 2],
                    ['product_index' => 1, 'size' => 'L',  'qty' => 1],
                    ['product_index' => 4, 'size' => 'S',  'qty' => 3],
                ],
            ],
            [
                'name'     => 'Alice Johnson',
                'email'    => 'alice@example.com',
                'password' => 'password',
                'is_admin' => false,
                'cart'     => [
                    ['product_index' => 2,  'size' => 'XS', 'qty' => 1],
                    ['product_index' => 18, 'size' => 'M',  'qty' => 1],
                    ['product_index' => 32, 'size' => 'L',  'qty' => 2],
                ],
            ],

            [
                'name'     => 'Bob Smith',
                'email'    => 'bob@example.com',
                'password' => 'password',
                'is_admin' => false,
                'cart'     => [
                    ['product_index' => 5,  'size' => 'XL',  'qty' => 1],
                    ['product_index' => 19, 'size' => 'XXL', 'qty' => 1],
                ],
            ],
            [
                'name'     => 'Carol Williams',
                'email'    => 'carol@example.com',
                'password' => 'password',
                'is_admin' => false,
                'cart'     => [
                    ['product_index' => 6,  'size' => 'S',  'qty' => 2],
                    ['product_index' => 10, 'size' => 'M',  'qty' => 1],
                    ['product_index' => 35, 'size' => 'L',  'qty' => 1],
                    ['product_index' => 37, 'size' => 'XL', 'qty' => 3],
                ],
            ],
            [
                'name'     => 'David Brown',
                'email'    => 'david@example.com',
                'password' => 'password',
                'is_admin' => false,
                'cart'     => [
                    ['product_index' => 3,  'size' => 'L',  'qty' => 1],
                    ['product_index' => 21, 'size' => 'XS', 'qty' => 2],
                ],
            ],
        ];

        foreach ($users as $userData) {
            $cartData = $userData['cart'];
            unset($userData['cart']);

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            foreach ($cartData as $item) {
                $index   = $item['product_index'];
                $product = $products->get($index) ?? $products->first();
                $color   = $colors->random();

                CartItem::firstOrCreate(
                    [
                        'user_id'    => $user->id,
                        'product_id' => $product->id,
                        'color_id'   => $color->id,
                        'size'       => $item['size'],
                    ],
                    ['count' => $item['qty']]
                );
            }
        }
    }
}
