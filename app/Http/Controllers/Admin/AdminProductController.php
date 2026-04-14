<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'images'])->get();
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
            'images.*'    => 'nullable|image|max:2048', // NEW
        ]);

        $productData = collect($validated)->except('images')->toArray();
        $productData['slug'] = Str::slug($productData['name']);

        $product = Product::create($productData);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');

                $product->images()->create([
                    'image_url' => $path,
                    'size'      => $file->dimensions()[0],
                ]);

                if ($index === 0) {
                    $product->update(['image_url' => $path]);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }


    public function edit(string $id)
    {
        $product    = Product::findOrFail($id);
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
            'images.*'    => 'nullable|image|max:2048',
        ]);

        $productData = collect($validated)->except('images')->toArray();
        $productData['slug'] = Str::slug($productData['name']);
        $product->update($productData);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');

                $product->images()->create([
                    'image_url' => $path,
                    'size'      => $file->dimensions()[0],
                ]);

                if (!$product->image_url && $index === 0) {
                    $product->update(['image_url' => $path]);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }


    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
