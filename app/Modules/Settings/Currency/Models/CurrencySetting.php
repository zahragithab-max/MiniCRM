<?php

namespace App\Modules\Settings\Currency\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencySetting extends Model
{
    protected $fillable = [
        'currency',
        'usd_rate',
    ];

    protected $casts = [
        'usd_rate' => 'decimal:4',
    ];
}