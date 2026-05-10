<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $allProductIds = Product::pluck('id');


        $springDeals = Sale::firstOrCreate(
            ['slug' => 'spring-deals'],
            [
                'name'       => 'Spring Deals',
                'discount'   => 30.00,
                'valid_from' => now()->subDays(3),
                'valid_to'   => now()->addDays(30),
                'promo_code' => null,
            ]
        );

        $springDeals->products()->syncWithoutDetaching(
            $allProductIds->take(6)
        );

        $weekendFlash = Sale::firstOrCreate(
            ['slug' => 'weekend-flash'],
            [
                'name'       => 'Weekend Flash Sale',
                'discount'   => 20.00,
                'valid_from' => now()->subDay(),
                'valid_to'   => now()->addDays(2),
                'promo_code' => null,
            ]
        );

        $weekendFlash->products()->syncWithoutDetaching(
            $allProductIds->slice(6, 5)->values()
        );

        $clearance = Sale::firstOrCreate(
            ['slug' => 'clearance'],
            [
                'name'       => 'Clearance',
                'discount'   => 50.00,
                'valid_from' => now()->subDays(10),
                'valid_to'   => now()->addDays(5),
                'promo_code' => null,
            ]
        );

        $clearance->products()->syncWithoutDetaching(
            $allProductIds->slice(-4)->values()
        );


        $summer20 = Sale::firstOrCreate(
            ['slug' => 'summer-sale'],
            [
                'name'       => 'Summer Sale',
                'discount'   => 20.00,
                'valid_from' => now()->subDays(5),
                'valid_to'   => now()->addDays(60),
                'promo_code' => 'SUMMER20',
            ]
        );

        $summer20->products()->syncWithoutDetaching($allProductIds);

        $welcome10 = Sale::firstOrCreate(
            ['slug' => 'welcome-discount'],
            [
                'name'       => 'Welcome Discount',
                'discount'   => 10.00,
                'valid_from' => now()->subDays(30),
                'valid_to'   => now()->addDays(365),
                'promo_code' => 'WELCOME10',
            ]
        );
        $welcome10->products()->syncWithoutDetaching($allProductIds);

        $shoes15 = Sale::firstOrCreate(
            ['slug' => 'shoes-promo'],
            [
                'name'       => 'Shoes Promo',
                'discount'   => 15.00,
                'valid_from' => now(),
                'valid_to'   => now()->addDays(14),
                'promo_code' => 'SHOES15',
            ]
        );
        $shoes15->products()->syncWithoutDetaching(
            $allProductIds->slice(18, 13)->values()
        );

        $blackFriday = Sale::firstOrCreate(
            ['slug' => 'black-friday'],
            [
                'name'       => 'Black Friday',
                'discount'   => 40.00,
                'valid_from' => now()->subDays(1),
                'valid_to'   => now()->addDays(3),
                'promo_code' => 'BF40',
            ]
        );
        $blackFriday->products()->syncWithoutDetaching($allProductIds);
    }
}
