<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\ItemColorSizeCount;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\Storage\SupabaseStorageService;
use App\Services\Product\ProductService;
use Illuminate\Database\QueryException;
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
        $colors     = Color::orderBy('name')->get();
        return view('admin.product-edit', compact('categories', 'colors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());
        $this->validateUploadedImages($request->file('images', []));

        $uploadedImages = $this->normalizeUploadedFiles($request->file('images', []));
        if (count($uploadedImages) < 2) {
            return back()->withErrors(['images' => 'Потрібно щонайменше 2 зображення.'])->withInput();
        }

        DB::transaction(function () use ($validated, $request) {
            $productData         = collect($validated)->except(['remove_image_ids', 'variants'])->toArray();
            $productData['slug'] = Str::slug($productData['name']);

            $product = Product::create($productData);
            $this->storeUploadedImages($product, $request->file('images', []));
            $this->syncVariants($product, $request->input('variants', []));
        });

        ProductService::clearPublicCache();

        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успішно створено.');
    }

    public function edit(string $id)
    {
        $product    = Product::with(['images', 'colorSizes'])->findOrFail($id);
        $categories = Category::all();
        $colors     = Color::orderBy('name')->get();
        return view('admin.product-edit', compact('product', 'categories', 'colors'));
    }

    public function update(Request $request, string $id)
    {
        $product = Product::with('images')->findOrFail($id);

        $validated      = $request->validate($this->rules());
        $this->validateUploadedImages($request->file('images', []));
        $removeImageIds = collect($validated['remove_image_ids'] ?? [])->map(fn ($id) => (int) $id)->all();

        $newImages       = $this->normalizeUploadedFiles($request->file('images', []));
        $remainingCount  = $product->images()->whereNotIn('id', $removeImageIds)->count();
        $totalAfter      = $remainingCount + count($newImages);

        if ($totalAfter < 2) {
            return back()->withErrors(['images' => 'Потрібно щонайменше 2 зображення.'])->withInput();
        }

        DB::transaction(function () use ($validated, $request, $product, $removeImageIds) {
            $productData         = collect($validated)->except(['remove_image_ids', 'variants'])->toArray();
            $productData['slug'] = Str::slug($productData['name']);
            $product->update($productData);

            $this->removeProductImages($product, $removeImageIds);
            $this->storeUploadedImages($product, $request->file('images', []));
            $this->syncVariants($product, $request->input('variants', []));
        });

        ProductService::clearPublicCache();

        return redirect()->route('admin.products.edit', $product->id)
            ->with('success', 'Товар успішно оновлено.');
    }

    public function destroy(string $id)
    {
        $product = Product::with('images')->findOrFail($id);

        try {
            $product->delete();
        } catch (QueryException $e) {
            if ($e->getCode() === '23503') {
                return back()->withErrors([
                    'product' => 'Неможливо видалити товар: він фігурує в історії замовлень.',
                ]);
            }

            throw $e;
        }

        foreach ($product->images as $image) {
            $this->storage->delete($image->image_url);
        }

        ProductService::clearPublicCache();

        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успішно видалено.');
    }

    private function rules(): array
    {
        return [
            'name'               => 'required|string|max:255',
            'description'        => 'nullable|string',
            'price'              => 'required|numeric|min:0',
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

    private function syncVariants(Product $product, array $variants): void
    {
        ItemColorSizeCount::where('item_id', $product->id)->delete();

        $seen = [];

        foreach ($variants as $variant) {
            $colorId = (int) ($variant['color_id']);
            $size    = $variant['size'];
            $count   = (int) ($variant['count'] ?? 0);

            if (!$colorId || !$size || $count < 0) {
                continue;
            }

            $key = "{$colorId}_{$size}";
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;

            ItemColorSizeCount::create([
                'item_id'  => $product->id,
                'color_id' => $colorId,
                'size'     => $size,
                'count'    => $count,
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
}
