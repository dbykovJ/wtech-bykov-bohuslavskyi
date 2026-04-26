<?php

namespace App\Http\Controllers\Cart\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    private const PRODUCTS_PER_PAGE = 3;

    public function index(Request $request)
    {
        $now = now();

        $activeSalesScope = function ($q) use ($now) {
            $q->where('valid_to', '>', $now)
              ->where('valid_from', '<', $now)
              ->whereNull('promo_code');
        };

        $discountSubquery = DB::table('products_on_sales')
            ->join('sales', 'sales.id', '=', 'products_on_sales.sale_id')
            ->where('sales.valid_to', '>', $now)
            ->where('sales.valid_from', '<', $now)
            ->whereNull('sales.promo_code')
            ->groupBy('products_on_sales.product_id')
            ->selectRaw('products_on_sales.product_id, SUM(sales.discount) as active_discount_sum');

        $query = Product::query()
            ->leftJoinSub($discountSubquery, 'active_sales_discounts', function ($join) {
                $join->on('products.id', '=', 'active_sales_discounts.product_id');
            })
            ->select('products.*')
            ->with([
                'category',
                'sales' => $activeSalesScope,
                'images',
            ]);

        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'ilike', "%{$search}%");
        }

        match ($request->input('sort', 'newest')) {
            'price_asc' => $query->orderByRaw('(products.price - (products.price * COALESCE(active_sales_discounts.active_discount_sum, 0) / 100.0)) asc'),
            'price_desc' => $query->orderByRaw('(products.price - (products.price * COALESCE(active_sales_discounts.active_discount_sum, 0) / 100.0)) desc'),
            default => $query->orderBy('products.created_at', 'desc'),
        };

        $products = $query
            ->paginate(self::PRODUCTS_PER_PAGE)
            ->withQueryString();

        $categories = Category::all();

        return view('category', compact('products', 'categories'));
    }
}
