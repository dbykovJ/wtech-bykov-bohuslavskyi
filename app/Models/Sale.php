<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Sale extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'discount',
        'valid_from',
        'valid_to',
        'promo_code',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Sale::class, 'product_on_sales')
            ->withPivot('discount');
    }
}
