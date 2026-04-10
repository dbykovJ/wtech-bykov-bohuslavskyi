<?php

namespace App\Services\Product;


use App\Models\Product;

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
        $product = Product::with('colorSizes', 'sales')->findOrFail($id);

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

            // Do NOT pre-select any size; let the customer choose manually
            $product->defaultSizeCode = null;
        } else {
            $product->colorGroups = collect();
            $product->defaultColorId = null;
            $product->defaultSizeCode = null;
        }

        return $product;
    }

    public static function getOnSale($limit = 4)
    {
        return Product::with('colorSizes')
            ->join('products_on_sales', 'products.id', '=', 'products_on_sales.product_id')
            ->join('sales', 'sales.id', '=', 'products_on_sales.sale_id')
            ->where('sales.valid_to', '>', now())
            ->where('sales.valid_from', '<', now())
            ->whereNull('sales.promo_code')
            ->groupBy('products.id')
            ->selectRaw('products.*, (SUM(sales.discount)/100) as total_discount')
            ->orderBy('total_discount', 'desc')
            ->limit($limit)
            ->get();
    }


    public static function getNewArrivals($limit = 4)
    {
        return Product::with([
            'sales' => function ($query) {
            $query->where('sales.valid_to', '>', now())
                ->where('sales.valid_from', '<', now())
                ->whereNull('sales.promo_code');
            },
            'colorSizes',
        ])
            ->orderBy('products.created_at', 'desc')
            ->limit($limit)
            ->get();

    }

    /**
     * Map a size code (e.g. "M") to a human-readable label.
     */
    private function getSizeLabel(string $code): string
    {
        return match ($code) {
            'XS' => 'X-Small',
            'S' => 'Small',
            'M' => 'Medium',
            'L' => 'Large',
            'XL' => 'X-Large',
            'XXL' => 'XX-Large',
            default => $code,
        };
    }


    public function search(string $query, int $limit = 5)
    {
        return Product::where('name', 'ilike', "%{$query}%")
            ->limit($limit)
            ->get(['id', 'name', 'price', 'image_url']);
    }
}
