<?php

namespace App\Modules\Leads\Models;

use App\Modules\Documents\Models\Document;
use App\Modules\Settings\Auth\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Lead extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'company_name',
        'email',
        'phone',
        'source',
        'status',
        'assigned_to',
        'notes',
    ];

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_to'
        );
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}