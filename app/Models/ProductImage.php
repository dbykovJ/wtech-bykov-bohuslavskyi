<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

        return app(\App\Services\Storage\SupabaseStorageService::class)->url($this->image_url);
    }
}
