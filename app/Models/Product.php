<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'slug',
        'category_id',
        'image_url',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
