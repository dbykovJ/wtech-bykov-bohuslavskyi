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
            // ── Clothing (18 products) ──────────────────────────────────────
            ['name' => 'Classic White T-Shirt',       'slug' => 'classic-white-t-shirt',       'description' => 'Everyday 100% cotton crew-neck tee.',                          'price' => 19.99, 'stock' => 120, 'rating' => 4.5, 'category_id' => $clothing->id],
            ['name' => 'Slim Fit Jeans',               'slug' => 'slim-fit-jeans',               'description' => 'Modern slim-cut denim in dark indigo wash.',                  'price' => 49.99, 'stock' => 60,  'rating' => 4.2, 'category_id' => $clothing->id],
            ['name' => 'Hoodie Sweatshirt',            'slug' => 'hoodie-sweatshirt',            'description' => 'Warm fleece-lined pullover hoodie.',                          'price' => 39.99, 'stock' => 50,  'rating' => 4.6, 'category_id' => $clothing->id],
            ['name' => 'Classic Pullover Hoodie',      'slug' => 'classic-pullover-hoodie',      'description' => 'Oversized fit, brushed interior, kangaroo pocket.',           'price' => 44.99, 'stock' => 45,  'rating' => 4.4, 'category_id' => $clothing->id],
            ['name' => 'Skinny Stretch Jeans',         'slug' => 'skinny-stretch-jeans',         'description' => 'High-rise skinny jeans with 2% elastane for flexibility.',    'price' => 44.99, 'stock' => 55,  'rating' => 4.1, 'category_id' => $clothing->id],
            ['name' => 'Oversized Hoodie',             'slug' => 'oversized-hoodie',             'description' => 'Relaxed-fit drop-shoulder hoodie in heavyweight fleece.',     'price' => 54.99, 'stock' => 40,  'rating' => 4.7, 'category_id' => $clothing->id],
            ['name' => 'Graphic Print Tee',            'slug' => 'graphic-print-tee',            'description' => 'Bold screen-printed graphic on a relaxed cotton tee.',       'price' => 24.99, 'stock' => 90,  'rating' => 4.3, 'category_id' => $clothing->id],
            ['name' => 'Cargo Trousers',               'slug' => 'cargo-trousers',               'description' => 'Multi-pocket utility trousers in ripstop cotton.',            'price' => 59.99, 'stock' => 35,  'rating' => 4.0, 'category_id' => $clothing->id],
            ['name' => 'Linen Shirt',                  'slug' => 'linen-shirt',                  'description' => 'Breathable summer shirt in 100% linen.',                      'price' => 34.99, 'stock' => 65,  'rating' => 4.2, 'category_id' => $clothing->id],
            ['name' => 'Zip-Up Tracksuit Jacket',      'slug' => 'zip-up-tracksuit-jacket',      'description' => 'Lightweight zip-through jacket with side pockets.',           'price' => 49.99, 'stock' => 48,  'rating' => 4.5, 'category_id' => $clothing->id],
            ['name' => 'Ribbed Turtleneck',            'slug' => 'ribbed-turtleneck',            'description' => 'Slim-fit ribbed knit turtleneck sweater.',                    'price' => 38.99, 'stock' => 42,  'rating' => 4.4, 'category_id' => $clothing->id],
            ['name' => 'Denim Jacket',                 'slug' => 'denim-jacket',                 'description' => 'Classic trucker-style denim jacket, stonewashed.',            'price' => 64.99, 'stock' => 30,  'rating' => 4.6, 'category_id' => $clothing->id],
            ['name' => 'Chino Pants',                  'slug' => 'chino-pants',                  'description' => 'Tailored slim chinos in stretch cotton twill.',               'price' => 44.99, 'stock' => 55,  'rating' => 4.1, 'category_id' => $clothing->id],
            ['name' => 'Polo Shirt',                   'slug' => 'polo-shirt',                   'description' => 'Piqué cotton polo with contrast tipping.',                    'price' => 29.99, 'stock' => 70,  'rating' => 4.0, 'category_id' => $clothing->id],
            ['name' => 'Bomber Jacket',                'slug' => 'bomber-jacket',                'description' => 'Satin-finish bomber with ribbed cuffs and hem.',              'price' => 74.99, 'stock' => 25,  'rating' => 4.8, 'category_id' => $clothing->id],
            ['name' => 'Fleece Quarter-Zip',           'slug' => 'fleece-quarter-zip',           'description' => 'Anti-pill micro-fleece with quarter-zip collar.',             'price' => 42.99, 'stock' => 50,  'rating' => 4.3, 'category_id' => $clothing->id],
            ['name' => 'Relaxed Fit Sweatpants',       'slug' => 'relaxed-fit-sweatpants',       'description' => 'French terry sweatpants with elasticated waist.',            'price' => 34.99, 'stock' => 60,  'rating' => 4.5, 'category_id' => $clothing->id],
            ['name' => 'Puffer Vest',                  'slug' => 'puffer-vest',                  'description' => 'Lightweight recycled-fill puffer gilet.',                    'price' => 55.99, 'stock' => 30,  'rating' => 4.2, 'category_id' => $clothing->id],

            // ── Shoes (13 products) ─────────────────────────────────────────
            ['name' => 'Running Sneakers',             'slug' => 'running-sneakers',             'description' => 'Lightweight mesh sneakers with foam midsole.',               'price' => 79.99, 'stock' => 40,  'rating' => 4.7, 'category_id' => $shoes->id],
            ['name' => 'Classic White Trainers',       'slug' => 'classic-white-trainers',       'description' => 'Clean low-profile leather trainers.',                        'price' => 69.99, 'stock' => 50,  'rating' => 4.5, 'category_id' => $shoes->id],
            ['name' => 'High-Top Basketball Shoes',    'slug' => 'high-top-basketball-shoes',    'description' => 'Ankle-support high-tops with herringbone outsole.',          'price' => 99.99, 'stock' => 30,  'rating' => 4.6, 'category_id' => $shoes->id],
            ['name' => 'Leather Chelsea Boots',        'slug' => 'leather-chelsea-boots',        'description' => 'Pull-on ankle boots in full-grain leather.',                 'price' => 119.99,'stock' => 20,  'rating' => 4.8, 'category_id' => $shoes->id],
            ['name' => 'Chunky Sole Loafers',          'slug' => 'chunky-sole-loafers',          'description' => 'Platform loafers with penny strap and thick TPR sole.',     'price' => 84.99, 'stock' => 25,  'rating' => 4.3, 'category_id' => $shoes->id],
            ['name' => 'Slip-On Canvas Shoes',         'slug' => 'slip-on-canvas-shoes',         'description' => 'Elastic-gore slip-ons in washed canvas.',                   'price' => 34.99, 'stock' => 70,  'rating' => 4.0, 'category_id' => $shoes->id],
            ['name' => 'Trail Running Shoes',          'slug' => 'trail-running-shoes',          'description' => 'Rugged outsole for off-road running on mixed terrain.',      'price' => 94.99, 'stock' => 28,  'rating' => 4.6, 'category_id' => $shoes->id],
            ['name' => 'Suede Desert Boots',           'slug' => 'suede-desert-boots',           'description' => 'Two-eyelet crepe-sole desert boots in suede.',               'price' => 89.99, 'stock' => 22,  'rating' => 4.4, 'category_id' => $shoes->id],
            ['name' => 'Retro Basketball Sneakers',    'slug' => 'retro-basketball-sneakers',    'description' => 'Vintage court style with leather upper and gum sole.',       'price' => 109.99,'stock' => 18,  'rating' => 4.7, 'category_id' => $shoes->id],
            ['name' => 'Waterproof Hiking Boots',      'slug' => 'waterproof-hiking-boots',      'description' => 'Gore-Tex membrane, Vibram outsole, ankle support.',          'price' => 134.99,'stock' => 15,  'rating' => 4.9, 'category_id' => $shoes->id],
            ['name' => 'Low-Cut Skate Shoes',          'slug' => 'low-cut-skate-shoes',          'description' => 'Vulcanised sole with suede/canvas upper.',                   'price' => 64.99, 'stock' => 35,  'rating' => 4.2, 'category_id' => $shoes->id],
            ['name' => 'Sock-Fit Knit Trainers',       'slug' => 'sock-fit-knit-trainers',       'description' => 'Primeknit upper with a snug sock-like fit.',                 'price' => 89.99, 'stock' => 20,  'rating' => 4.5, 'category_id' => $shoes->id],
            ['name' => 'Moccasin Slippers',            'slug' => 'moccasin-slippers',            'description' => 'Sheepskin-lined moccasin for indoor and outdoor use.',      'price' => 44.99, 'stock' => 45,  'rating' => 4.1, 'category_id' => $shoes->id],

            // ── Accessories (9 products) ────────────────────────────────────
            ['name' => 'Leather Belt',                 'slug' => 'leather-belt',                 'description' => 'Genuine leather belt with brushed-silver buckle.',           'price' => 24.99, 'stock' => 80,  'rating' => 4.0, 'category_id' => $accessories->id],
            ['name' => 'Wool Beanie',                  'slug' => 'wool-beanie',                  'description' => 'Ribbed merino wool beanie with a folded cuff.',              'price' => 18.99, 'stock' => 100, 'rating' => 4.5, 'category_id' => $accessories->id],
            ['name' => 'Canvas Tote Bag',              'slug' => 'canvas-tote-bag',              'description' => 'Heavy-duty waxed canvas tote with leather handles.',         'price' => 32.99, 'stock' => 60,  'rating' => 4.3, 'category_id' => $accessories->id],
            ['name' => 'Aviator Sunglasses',           'slug' => 'aviator-sunglasses',           'description' => 'UV400 metal-frame aviators with gradient lenses.',           'price' => 29.99, 'stock' => 75,  'rating' => 4.1, 'category_id' => $accessories->id],
            ['name' => 'Knit Scarf',                   'slug' => 'knit-scarf',                   'description' => 'Extra-long cable-knit scarf in soft acrylic blend.',         'price' => 22.99, 'stock' => 85,  'rating' => 4.4, 'category_id' => $accessories->id],
            ['name' => 'Leather Wallet',               'slug' => 'leather-wallet',               'description' => 'Slim bifold wallet in full-grain leather, 8 card slots.',    'price' => 39.99, 'stock' => 55,  'rating' => 4.6, 'category_id' => $accessories->id],
            ['name' => 'Snapback Cap',                 'slug' => 'snapback-cap',                 'description' => 'Structured 6-panel cap with flat brim and snap closure.',    'price' => 19.99, 'stock' => 90,  'rating' => 4.2, 'category_id' => $accessories->id],
            ['name' => 'Gym Duffle Bag',               'slug' => 'gym-duffle-bag',               'description' => '40L water-resistant duffle with shoe compartment.',           'price' => 49.99, 'stock' => 40,  'rating' => 4.5, 'category_id' => $accessories->id],
            ['name' => 'Silver Chain Necklace',        'slug' => 'silver-chain-necklace',        'description' => '925 sterling silver figaro chain, 50 cm.',                   'price' => 34.99, 'stock' => 50,  'rating' => 4.3, 'category_id' => $accessories->id],
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
