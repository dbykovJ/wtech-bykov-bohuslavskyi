<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    /**
     * Maps product slug → two Unsplash photo URLs.
     * The ProductImage::getPublicUrlAttribute() already returns any https:// URL as-is,
     * so we store the full URL directly — no Supabase upload needed for seeding.
     */
    private array $images = [
        // ── Clothing ──────────────────────────────────────────────────────
        'classic-white-t-shirt'     => ['https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800', 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=800'],
        'slim-fit-jeans'            => ['https://images.unsplash.com/photo-1542272604-787c3835535d?w=800', 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=800'],
        'skinny-stretch-jeans'      => ['https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=800', 'https://images.unsplash.com/photo-1604176354204-9268737828e4?w=800'],
        'oversized-hoodie'          => ['https://images.unsplash.com/photo-1620799139507-2a76f79a2f4d?w=800', 'https://images.unsplash.com/photo-1626497764746-6dc36546b388?w=800'],
        'graphic-print-tee'         => ['https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=800', 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800'],
        'cargo-trousers'            => ['https://images.unsplash.com/photo-1517841905240-472988babdf9?w=800', 'https://images.unsplash.com/photo-1591195853828-11db59a44f43?w=800'],
        'linen-shirt'               => ['https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=800', 'https://images.unsplash.com/photo-1596755389378-c31d21fd1273?w=800'],
        'zip-up-tracksuit-jacket'   => ['https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=800', 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=800'],
        'ribbed-turtleneck'         => ['https://images.unsplash.com/photo-1593030761757-71fae45fa0e7?w=800', 'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?w=800'],
        'denim-jacket'              => ['https://images.unsplash.com/photo-1523205771623-e0faa4d2813d?w=800', 'https://images.unsplash.com/photo-1495105787522-5334e3ffa0ef?w=800'],
        'chino-pants'               => ['https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=800', 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=800'],
        'polo-shirt'                => ['https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=800', 'https://images.unsplash.com/photo-1598032895397-b9472444bf93?w=800'],
        'bomber-jacket'             => ['https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=800', 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800'],
        'relaxed-fit-sweatpants'    => ['https://images.unsplash.com/photo-1552902865-b72c031ac5ea?w=800', 'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=800'],

        // ── Shoes ─────────────────────────────────────────────────────────
        'running-sneakers'          => ['https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800', 'https://images.unsplash.com/photo-1539185441755-769473a23570?w=800'],
        'classic-white-trainers'    => ['https://images.unsplash.com/photo-1600269452121-4f2416e55c28?w=800', 'https://images.unsplash.com/photo-1518894781321-630e638d0742?w=800'],
        'high-top-basketball-shoes' => ['https://images.unsplash.com/photo-1579338559194-a162d19bf842?w=800', 'https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=800'],
        'leather-chelsea-boots'     => ['https://images.unsplash.com/photo-1638247025967-b4e38f787b76?w=800', 'https://images.unsplash.com/photo-1608256246200-53e635b5b65f?w=800'],
        'chunky-sole-loafers'       => ['https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=800', 'https://images.unsplash.com/photo-1614252235316-8c857d38b5f4?w=800'],
        'slip-on-canvas-shoes'      => ['https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=800', 'https://images.unsplash.com/photo-1562183241-b937e9102303?w=800'],
        'trail-running-shoes'       => ['https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=800', 'https://images.unsplash.com/photo-1595341888016-a392ef81b7de?w=800'],
        'suede-desert-boots'        => ['https://images.unsplash.com/photo-1520639888713-7851133b1ed0?w=800', 'https://images.unsplash.com/photo-1603487742131-4160ec999306?w=800'],
        'retro-basketball-sneakers' => ['https://images.unsplash.com/photo-1552346154-21d32810aba3?w=800', 'https://images.unsplash.com/photo-1560769629-975ec94e6a86?w=800'],
        'waterproof-hiking-boots'   => ['https://images.unsplash.com/photo-1464490188426-f6d5a6252800?w=800', 'https://images.unsplash.com/photo-1510674078840-a5e9e0c3d7df?w=800'],
        'sock-fit-knit-trainers'    => ['https://images.unsplash.com/photo-1575537302964-96cd47c06b1b?w=800', 'https://images.unsplash.com/photo-1551107696-a4b0c5a0d9a2?w=800'],
        'moccasin-slippers'         => ['https://images.unsplash.com/photo-1511556820780-d912e42b4980?w=800', 'https://images.unsplash.com/photo-1582588678413-dbf45f4823e9?w=800'],

        // ── Accessories ───────────────────────────────────────────────────
        'leather-belt'              => ['https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800', 'https://images.unsplash.com/photo-1624222247344-550fb60583dc?w=800'],
        'wool-beanie'               => ['https://images.unsplash.com/photo-1576871337622-98d48d1cf531?w=800', 'https://images.unsplash.com/photo-1510598969022-c4c6c5d05769?w=800'],
        'canvas-tote-bag'           => ['https://images.unsplash.com/photo-1544816155-12df9643f363?w=800', 'https://images.unsplash.com/photo-1622560480654-d96214fdc887?w=800'],
        'aviator-sunglasses'        => ['https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=800', 'https://images.unsplash.com/photo-1584036561566-baf8f5f1b144?w=800'],
        'knit-scarf'                => ['https://images.unsplash.com/photo-1520903920243-00d872a2d1c9?w=800', 'https://images.unsplash.com/photo-1601924994987-69e26d50dc26?w=800'],
        'leather-wallet'            => ['https://images.unsplash.com/photo-1627123424574-724758594e93?w=800', 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=800'],
        'snapback-cap'              => ['https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=800', 'https://images.unsplash.com/photo-1556306535-38febf6cdbe9?w=800'],
        'silver-chain-necklace'     => ['https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=800', 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=800'],

        'hoodie-sweatshirt'         => ['https://picsum.photos/seed/hoodie1/800/800',   'https://images.unsplash.com/photo-1509942774463-acf339cf87d5?w=800'],
        'classic-pullover-hoodie'   => ['https://picsum.photos/seed/pullover1/800/800', 'https://images.unsplash.com/photo-1631721076847-eff38aecfc12?w=800'],
        'fleece-quarter-zip'        => ['https://picsum.photos/seed/fleece1/800/800',   'https://images.unsplash.com/photo-1571945153237-4929e783af4a?w=800'],
        'puffer-vest'               => ['https://picsum.photos/seed/vest1/800/800',     'https://images.unsplash.com/photo-1578768079052-aa76e52ff62e?w=800'],
        'low-cut-skate-shoes'       => ['https://picsum.photos/seed/skate1/800/800',    'https://images.unsplash.com/photo-1598808503824-5c461ca6d22f?w=800'],
        'gym-duffle-bag'            => ['https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800', 'https://picsum.photos/seed/duffle2/800/800'],

    ];

    public function run(): void
    {
        foreach ($this->images as $slug => $urls) {
            $product = Product::where('slug', $slug)->first();

            if (! $product) {
                continue;
            }

            // Skip if images already exist for this product
            if ($product->images()->count() > 0) {
                continue;
            }

            foreach ($urls as $url) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url'  => $url,   // public_url accessor returns https:// URLs as-is
                    'size'       => 0,
                ]);
            }
        }
    }
}
