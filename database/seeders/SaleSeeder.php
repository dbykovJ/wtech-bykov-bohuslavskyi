<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        // Public sale — no promo code, shows on homepage
        $publicSale = Sale::firstOrCreate(
            ['slug' => 'spring-deals'],
            [
                'name'      => 'Spring Deals',
                'discount'  => 30.00,
                'valid_from' => now()->subDays(3),
                'valid_to'   => now()->addDays(30),
                'promo_code' => null,
            ]
        );

        // Attach first 3 products to the public sale
        $publicSale->products()->syncWithoutDetaching(
            Product::limit(3)->pluck('id')
        );

        // Promo code sale — only applies when code is entered
        $promoSale = Sale::firstOrCreate(
            ['slug' => 'summer-sale'],
            [
                'name'      => 'Summer Sale',
                'discount'  => 20.00,
                'valid_from' => now()->subDays(5),
                'valid_to'   => now()->addDays(30),
                'promo_code' => 'SUMMER20',
            ]
        );

        // Attach last 2 products to the promo sale
        $promoSale->products()->syncWithoutDetaching(
            Product::orderBy('id', 'desc')->limit(2)->pluck('id')
        );
    }
}
