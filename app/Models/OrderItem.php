<?php

namespace App\Models;

use App\Enums\Size;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'color_id',
        'size',
        'quantity',
        'unit_price_before_discount',
        'unit_sales_discount_percent',
        'unit_promo_discount_percent',
        'unit_loyalty_discount_percent',
        'unit_discount_percent',
        'unit_price_after_discount',
        'line_total',
    ];

    protected $casts = [
        'size' => Size::class,
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(ProductReview::class);
    }
}

