<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $productService)
    {
    }

    public function show($id)
    {
        $product = $this->productService->getProduct($id);
        $similar = $this->productService->getSimilar($product);

        return view('product', ['product' => $product, 'similar' => $similar]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');

        $products = $this->productService->search($query);
        return ApiResponse::success($products);
    }
}
