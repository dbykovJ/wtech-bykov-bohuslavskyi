<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'rating',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function sales(): BelongsToMany
    {
        return $this->belongsToMany(Sale::class, 'products_on_sales', 'product_id', 'sale_id')
            ->whereNull('promo_code');
    }


    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function getFirstImage(){
        return $this->images()->first();
    }

    public function colorSizes(): BelongsToMany
    {
        return $this->belongsToMany(Color::class, 'item_color_size_counts', 'item_id', 'color_id')
            ->withPivot('size', 'count');
    }
}
