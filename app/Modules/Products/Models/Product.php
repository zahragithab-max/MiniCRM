<?php

namespace App\Modules\Products\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Deals\Models\DealProduct;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'sku',
        'price',
        'stock',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    public function dealProducts(): HasMany
{
    return $this->hasMany(DealProduct::class);
}
}
