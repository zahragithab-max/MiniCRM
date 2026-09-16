<?php

namespace App\Modules\Documents\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Document extends Model
{
    protected $fillable = [
        'documentable_type',
        'documentable_id',
        'name',
        'file_path',
        'mime_type',
        'file_size',
        'category',
        'version',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'version' => 'integer',
    ];

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }
}