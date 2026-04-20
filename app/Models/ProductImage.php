<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'image_url',
        'size'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getPublicUrlAttribute(): ?string
    {
        if (! $this->image_url) {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $this->image_url)) {
            return $this->image_url;
        }

        $path = $this->normalizeStoragePath($this->image_url);

        if (! $path) {
            return null;
        }

        return '/storage/' . ltrim($path, '/');
    }

    private function normalizeStoragePath(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        $normalized = ltrim($path, '/\\');
        $normalized = preg_replace('/^(public\/|storage\/)+/i', '', $normalized);
        $normalized = Str::replace('\\', '/', $normalized);

        return $normalized !== '' ? $normalized : null;
    }
}
