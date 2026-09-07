<?php

namespace App\Modules\Tasks\Models;

use App\Modules\Settings\Auth\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'due_date',
        'priority',
        'status',
        'assigned_to',
        'taskable_type',
        'taskable_id',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function taskable(): MorphTo
    {
        return $this->morphTo();
    }
}