<?php

namespace App\Modules\Settings\Currency\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    protected $fillable = [
        'from_currency',
        'to_currency',
        'rate',
        'effective_at',
    ];

    protected $casts = [
        'rate' => 'decimal:8',
        'effective_at' => 'datetime',
    ];
}