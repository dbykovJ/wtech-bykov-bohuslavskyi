<?php

namespace App\Services\ProductImage;

use App\Models\ProductImage;

class ProductImageService
{
    public function createProductImage($data){
        $validated = $data->validate([
            'product_id' => 'required|integer|exists:products,id',
            'image_url' => 'required|string|max:255',
            'size' => 'required|integer|max:1920|min:220',
        ]);

        return ProductImage::create([
            'product_id' => $validated['product_id'],
            'image_url' => $validated['image_url'],
            'size' => $validated['size'],
        ]);
    }


    public function getProductImages($product_id){
        return ProductImage::where('product_id',$product_id);
    }
}
