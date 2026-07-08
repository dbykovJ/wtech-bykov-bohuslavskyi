<?php

namespace App\Models;

use App\Enums\DeliveryMethod;
use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'currency',
        'promo_code',
        'subtotal_before_discount',
        'sales_discount_total',
        'promo_discount_total',
        'loyalty_discount_percent',
        'loyalty_discount_total',
        'discount_total',
        'subtotal',
        'delivery_fee',
        'total',
        'shipping_method',
        'shipping_full_name',
        'shipping_email',
        'shipping_phone',
        'shipping_street',
        'shipping_city',
        'shipping_postal_code',
        'shipping_country',
        'paid_at',
        'payment_method',
        'payment_transaction_id',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'shipping_method' => DeliveryMethod::class,
        'payment_method' => PaymentMethod::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
