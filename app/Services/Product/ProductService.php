<?php

namespace App\Services\Product;


use App\Models\Product;
class ProductService
{

    public static function getOnSale($limit = 4)
    {
        return Product::join('products_on_sales', 'products.id', '=', 'products_on_sales.product_id')
            ->join('sales', 'sales.id', '=', 'products_on_sales.sale_id')
            ->where('sales.valid_to', '>', now())
            ->where('sales.valid_from', '<', now())
            ->whereNull('promo_code')
            ->groupBy('products.id')
            ->selectRaw('products.*, (SUM(sales.discount)/100) as total_discount')
            ->orderBy('total_discount', 'desc')
            ->limit($limit)
            ->get();
    }


    public static function getNewArrivals($limit = 4)
    {
        return Product::with(['sales' => function ($query) {
            $query->where('sales.valid_to', '>', now())
                ->where('sales.valid_from', '<', now())
                ->whereNull('sales.promo_code');
        }])
            ->orderBy('products.created_at', 'desc')
            ->limit($limit)
            ->get();

    }

}
