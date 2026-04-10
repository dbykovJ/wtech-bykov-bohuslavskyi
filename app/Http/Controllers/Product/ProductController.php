<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $productService)
    {
    }

    public function show($id)
    {
        $product = $this->productService->getProduct($id);

        return view('product', ['product' => $product]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = $this->productService->search($query);
        return response()->json($products);
    }
}
