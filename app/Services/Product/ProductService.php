<?php

namespace App\Services\Product;


use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    /**
     * Defines the display/order of available sizes.
     */
    private const SIZE_ORDER = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

    /**
     * Load a product with its color/size groups.
     *
     * @param  int|string  $id
     */
    public function getProduct($id)
    {
        $product = Product::with([
            'colorSizes',
            'sales' => fn ($q) => $q->where('sales.valid_to', '>', now())
                ->where('sales.valid_from', '<', now()),
            'images',
            'reviews.user',
        ])->findOrFail($id);

        if ($product->colorSizes->isNotEmpty()) {
            $order = array_flip(self::SIZE_ORDER);

            $groups = $product->colorSizes
                ->groupBy('pivot.color_id')
                ->map(function ($colors, $colorId) use ($order) {
                    $color = $colors->first();

                    $sizes = $colors->map(function ($c) {
                        $code = $c->pivot->size;
                        $count = (int) $c->pivot->count;

                        return [
                            'code' => $code,
                            'label' => $this->getSizeLabel($code),
                            'count' => $count,
                            'in_stock' => $count > 0,
                        ];
                    })->sortBy(function (array $size) use ($order) {
                        return $order[$size['code']] ?? PHP_INT_MAX;
                    })->values();

                    $hasInStock = $sizes->contains(function (array $size) {
                        return $size['in_stock'];
                    });

                    return [
                        'id' => $colorId,
                        'name' => $color->name,
                        'hex_code' => $color->hex_code,
                        'sizes' => $sizes,
                        'has_in_stock' => $hasInStock,
                    ];
                });

            $product->colorGroups = $groups;

            // Default color is simply the first color in the list
            $product->defaultColorId = $groups->keys()->first();
        } else {
            $product->colorGroups = collect();
            $product->defaultColorId = null;
        }

        return $product;
    }

    public static function getOnSale($limit = 4)
    {
        return Cache::remember("catalog.home.on-sale.{$limit}", now()->addMinutes(10), fn () => Product::with(['colorSizes', 'images'])
            ->join('products_on_sales', 'products.id', '=', 'products_on_sales.product_id')
            ->join('sales', 'sales.id', '=', 'products_on_sales.sale_id')
            ->where('sales.valid_to', '>', now())
            ->where('sales.valid_from', '<', now())
            ->whereNull('sales.promo_code')
            ->groupBy('products.id')
            ->selectRaw('products.*, (SUM(sales.discount)/100) as total_discount')
            ->orderBy('total_discount', 'desc')
            ->limit($limit)
            ->get());
    }


    public static function getNewArrivals($limit = 4)
    {
        return Cache::remember("catalog.home.new-arrivals.{$limit}", now()->addMinutes(10), fn () => Product::with([
            'sales' => function ($query) {
            $query->where('sales.valid_to', '>', now())
                ->where('sales.valid_from', '<', now())
                ->whereNull('sales.promo_code');
            },
            'colorSizes',
            'images',
        ])
            ->orderBy('products.created_at', 'desc')
            ->limit($limit)
            ->get());

    }

    public static function clearPublicCache(): void
    {
        foreach ([4, 8, 12] as $limit) {
            Cache::forget("catalog.home.on-sale.{$limit}");
            Cache::forget("catalog.home.new-arrivals.{$limit}");
        }
    }

    /**
     * Map a size code (e.g. "M") to a human-readable label.
     */
    private function getSizeLabel(string $code): string
    {
        return match ($code) {
            'XS' => 'Дуже малий',
            'S' => 'Малий',
            'M' => 'Середній',
            'L' => 'Великий',
            'XL' => 'Дуже великий',
            'XXL' => 'Надвеликий',
            default => $code,
        };
    }


    public function getSimilar(Product $product, int $limit = 4): \Illuminate\Database\Eloquent\Collection
    {
        $similar = Product::with([
            'images',
            'sales' => fn ($q) => $q->where('sales.valid_to', '>', now())
                ->where('sales.valid_from', '<', now())
                ->whereNull('sales.promo_code'),
        ])
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->orderByDesc('rating')
            ->limit($limit)
            ->get();

        // Pad with other products if same category has fewer than $limit
        if ($similar->count() < $limit) {
            $exclude = $similar->pluck('id')->push($product->id);
            $pad = Product::with([
                'images',
                'sales' => fn ($q) => $q->where('sales.valid_to', '>', now())
                    ->where('sales.valid_from', '<', now())
                    ->whereNull('sales.promo_code'),
            ])
                ->whereNotIn('id', $exclude)
                ->orderByDesc('rating')
                ->limit($limit - $similar->count())
                ->get();

            $similar = $similar->concat($pad);
        }

        return $similar;
    }

    public function search(string $query, int $limit = 5)
    {
        return Product::with('images')
            ->where('name', 'ilike', "%{$query}%")
            ->limit($limit)
            ->get(['id', 'name', 'price'])
            ->map(function (Product $product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image_url' => optional($product->getFirstImage())->image_url,
                ];
            });
    }
}
