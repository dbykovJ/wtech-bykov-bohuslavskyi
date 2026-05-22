<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Size;

class ItemColorSizeCount extends Model
{
    protected $primaryKey = ['item_id', 'color_id', 'size'];
    public $incrementing = false;

    protected $fillable = ['item_id', 'color_id', 'size', 'count'];

    protected $casts = [
        'size' => Size::class,
    ];
}
