<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductService;

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
}
