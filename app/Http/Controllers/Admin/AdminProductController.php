<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('admin.products', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.product-edit', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images'      => 'nullable|array',
            'images.*'    => 'image|max:2048',
        ]);

        unset($validated['images']);
        $validated['slug'] = Str::slug($validated['name']);
        $product = Product::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                $product->images()->create(['path' => $path]);
                if ($index === 0) {
                    $product->update(['image_url' => $path]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(string $id)
    {
        $product    = Product::with('images')->findOrFail($id);
        $categories = Category::all();
        return view('admin.product-edit', compact('product', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $product = Product::with('images')->findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images'      => 'nullable|array',
            'images.*'    => 'image|max:2048',
        ]);

        unset($validated['images']);
        $validated['slug'] = Str::slug($validated['name']);
        $product->update($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', 'public');
                $product->images()->create(['path' => $path]);
            }
            // Update primary image_url to first image if none set yet
            if (!$product->image_url) {
                $product->update(['image_url' => $product->images()->first()->path]);
            }
        }

        return redirect()->route('admin.products.edit', $product->id)->with('success', 'Product updated successfully.');
    }

    public function destroy(string $id)
    {
        $product = Product::with('images')->findOrFail($id);

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function destroyImage(string $productId, string $imageId)
    {
        $product = Product::findOrFail($productId);
        $image   = ProductImage::where('product_id', $productId)->findOrFail($imageId);

        Storage::disk('public')->delete($image->path);
        $image->delete();

        // If this was the primary image, promote the next one
        if ($product->image_url === $image->path) {
            $next = $product->images()->first();
            $product->update(['image_url' => $next?->path]);
        }

        return redirect()->route('admin.products.edit', $productId)->with('success', 'Image removed.');
    }
}
