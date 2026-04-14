<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'ilike', "%{$search}%"); // use 'like' if you're not on Postgres
        }


        if ($request->sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products   = $query->paginate(9);
        $categories = Category::all();

        return view('category', compact('products', 'categories'));
    }
}
