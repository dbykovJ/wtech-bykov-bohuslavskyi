<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
class ProductController extends Controller
{
    public function indexWithSales(){
        return Product::whereHas('sales', function ($query) {
            $query->where('valid_to', '>', now());
        })
            ->with(['sales' => function ($query) {
                $query->where('valid_to', '>', now());
            }])
            ->join('product_on_sales', 'products.id', '=', 'product_on_sales.product_id')
            ->join('sales', 'sales.id', '=', 'product_on_sales.sale_id')
            ->where('sales.valid_to', '>', now())
            ->groupBy('products.id')
            ->selectRaw('products.*, SUM(product_on_sales.discount) as totalDiscount')
            ->orderBy('totalDiscount', 'desc')
            ->limit(4)
            ->get();

    }
}
