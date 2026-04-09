<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        return $this->belongsToMany(Sale::class, 'products_on_sales', 'product_id', 'sale_id');
    }

    public static function getOnSale($limit = 4)
    {
        return self::join('products_on_sales', 'products.id', '=', 'products_on_sales.product_id')
            ->join('sales', 'sales.id', '=', 'products_on_sales.sale_id')
            ->where('sales.valid_to', '>', now())
            ->where('sales.valid_from', '<', now())
            ->groupBy('products.id')
            ->selectRaw('products.*, (SUM(sales.discount)/100) as total_discount')
            ->orderBy('total_discount', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getNewArrivals($limit = 4)
    {
        return self::with(['sales' => function ($query) {
            $query->where('valid_to', '>', now())
                ->where('sales.valid_from', '<', now());
        }])
            ->orderBy('products.created_at', 'desc')
            ->limit($limit)
            ->get();

    }
}
