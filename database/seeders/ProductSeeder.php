<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Color;
use App\Models\ItemColorSizeCount;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $clothing    = Category::where('slug', 'clothing')->first();
        $shoes       = Category::where('slug', 'shoes')->first();
        $accessories = Category::where('slug', 'accessories')->first();

        $products = [
            [
                'name'        => 'Classic T-Shirt',
                'slug'        => 'classic-t-shirt',
                'description' => 'A comfortable everyday cotton t-shirt.',
                'price'       => 19.99,
                'stock'       => 100,
                'rating'      => 4.5,
                'category_id' => $clothing->id,
                'image_url'   => null,
            ],
            [
                'name'        => 'Slim Fit Jeans',
                'slug'        => 'slim-fit-jeans',
                'description' => 'Modern slim fit denim jeans.',
                'price'       => 49.99,
                'stock'       => 60,
                'rating'      => 4.2,
                'category_id' => $clothing->id,
                'image_url'   => null,
            ],
            [
                'name'        => 'Running Sneakers',
                'slug'        => 'running-sneakers',
                'description' => 'Lightweight sneakers for everyday running.',
                'price'       => 79.99,
                'stock'       => 40,
                'rating'      => 4.7,
                'category_id' => $shoes->id,
                'image_url'   => null,
            ],
            [
                'name'        => 'Leather Belt',
                'slug'        => 'leather-belt',
                'description' => 'Genuine leather belt with a metal buckle.',
                'price'       => 24.99,
                'stock'       => 80,
                'rating'      => 4.0,
                'category_id' => $accessories->id,
                'image_url'   => null,
            ],
            [
                'name'        => 'Hoodie Sweatshirt',
                'slug'        => 'hoodie-sweatshirt',
                'description' => 'Warm fleece hoodie for cool days.',
                'price'       => 39.99,
                'stock'       => 50,
                'rating'      => 4.6,
                'category_id' => $clothing->id,
                'image_url'   => null,
            ],
        ];

        foreach ($products as $data) {
            Product::firstOrCreate(['slug' => $data['slug']], $data);
        }

        // Seed stock per product / color / size
        $sizes  = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        $colors = Color::all();

        foreach (Product::all() as $product) {
            foreach ($colors as $color) {
                foreach ($sizes as $size) {
                    ItemColorSizeCount::firstOrCreate(
                        [
                            'item_id'  => $product->id,
                            'color_id' => $color->id,
                            'size'     => $size,
                        ],
                        ['count' => rand(5, 30)]
                    );
                }
            }
        }
    }
}
