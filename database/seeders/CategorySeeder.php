<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Clothing',     'slug' => 'clothing'],
            ['name' => 'Shoes',        'slug' => 'shoes'],
            ['name' => 'Accessories',  'slug' => 'accessories'],
            ['name' => 'Electronics',  'slug' => 'electronics'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
