<?php

namespace App\Modules\Settings\Company\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $fillable = [
        'name',
        'logo_path',
        'address',
        'registration_number',
    ];
}