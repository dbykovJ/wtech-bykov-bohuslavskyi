<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\Storage\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function __construct(private SupabaseStorageService $storage) {}

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
            $productData         = collect($validated)->except(['remove_image_ids'])->toArray();
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

        $validated      = $request->validate($this->rules());
        $this->validateUploadedImages($request->file('images', []));
        $removeImageIds = collect($validated['remove_image_ids'] ?? [])->map(fn ($id) => (int) $id)->all();

        DB::transaction(function () use ($validated, $request, $product, $removeImageIds) {
            $productData         = collect($validated)->except(['remove_image_ids'])->toArray();
            $productData['slug'] = Str::slug($productData['name']);
            $product->update($productData);

            $this->removeProductImages($product, $removeImageIds);
            $this->storeUploadedImages($product, $request->file('images', []));
        });

        return redirect()->route('admin.products.edit', $product->id)
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(string $id)
    {
        $product = Product::with('images')->findOrFail($id);

        foreach ($product->images as $image) {
            $this->storage->delete($image->image_url);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    private function rules(): array
    {
        return [
            'name'               => 'required|string|max:255',
            'description'        => 'nullable|string',
            'price'              => 'required|numeric|min:0',
            'stock'              => 'required|integer|min:0',
            'category_id'        => 'required|exists:categories,id',
            'remove_image_ids'   => 'nullable|array',
            'remove_image_ids.*' => 'integer',
        ];
    }

    private function storeUploadedImages(Product $product, UploadedFile|array|null $images): void
    {
        $isFirst = $product->images()->count() === 0;

        foreach ($this->normalizeUploadedFiles($images) as $file) {
            if (! $file->isValid()) {
                continue;
            }

            $filename = $this->storage->upload($file);

            $product->images()->create([
                'image_url' => $filename,
                'size'      => $file->getSize() ?? 0,
            ]);

            if ($isFirst) {
                $product->update(['image_url' => $this->storage->url($filename)]);
                $isFirst = false;
            }
        }
    }

    private function removeProductImages(Product $product, array $imageIds): void
    {
        if ($imageIds === []) {
            return;
        }

        $images = $product->images()->whereIn('id', $imageIds)->get();

        foreach ($images as $image) {
            $this->storage->delete($image->image_url);
        }

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
}
