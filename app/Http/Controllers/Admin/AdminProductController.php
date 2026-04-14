<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
        $validated = $request->validate($this->rules());
        $this->validateUploadedImages($request->file('images', []));

        DB::transaction(function () use ($validated, $request) {
            $productData = collect($validated)->except(['images', 'remove_image_ids'])->toArray();
            $productData['slug'] = Str::slug($productData['name']);

            $product = Product::create($productData);
            $this->storeUploadedImages($product, $request->file('images', []));
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
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

        $validated = $request->validate($this->rules());
        $this->validateUploadedImages($request->file('images', []));
        $removeImageIds = collect($validated['remove_image_ids'] ?? [])->map(fn ($id) => (int) $id)->all();

        DB::transaction(function () use ($validated, $request, $product, $removeImageIds) {
            $productData = collect($validated)->except(['images', 'remove_image_ids'])->toArray();
            $productData['slug'] = Str::slug($productData['name']);
            $product->update($productData);

            $this->removeProductImages($product, $removeImageIds);
            $this->storeUploadedImages($product, $request->file('images', []));
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }


    public function destroy(string $id)
    {
        $product = Product::with('images')->findOrFail($id);

        $this->deleteImageFiles($product->images);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    private function rules(): array
    {
        return [
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'stock'            => 'required|integer|min:0',
            'category_id'      => 'required|exists:categories,id',
            'remove_image_ids' => 'nullable|array',
            'remove_image_ids.*' => 'integer',
        ];
    }

    private function storeUploadedImages(Product $product, UploadedFile|array|null $images): void
    {
        $files = $this->normalizeUploadedFiles($images);

        foreach ($files as $file) {
            if (! $file->isValid()) {
                continue;
            }

            $path = $file->store('products', 'public');

            $product->images()->create([
                'image_url' => $path,
                'size'      => $file->getSize() ?? 0,
            ]);
        }
    }

    private function normalizeUploadedFiles(UploadedFile|array|null $images): array
    {
        if ($images instanceof UploadedFile) {
            return [$images];
        }

        if (! is_array($images)) {
            return [];
        }

        $files = [];

        foreach ($images as $image) {
            if ($image instanceof UploadedFile) {
                $files[] = $image;
                continue;
            }

            if (is_array($image)) {
                $files = [...$files, ...$this->normalizeUploadedFiles($image)];
            }
        }

        return $files;
    }

    private function removeProductImages(Product $product, array $imageIds): void
    {
        if ($imageIds === []) {
            return;
        }

        $images = $product->images()->whereIn('id', $imageIds)->get();

        if ($images->isEmpty()) {
            return;
        }

        $this->deleteImageFiles($images);
        $product->images()->whereIn('id', $images->pluck('id'))->delete();
    }

    private function validateUploadedImages(UploadedFile|array|null $images): void
    {
        foreach ($this->normalizeUploadedFiles($images) as $file) {
            Validator::make(
                ['image' => $file],
                ['image' => 'required|image|max:4096']
            )->validate();
        }
    }

    private function deleteImageFiles($images): void
    {
        foreach ($images as $image) {
            if ($image instanceof ProductImage && $image->image_url) {
                Storage::disk('public')->delete($image->image_url);
            }
        }
    }
}
