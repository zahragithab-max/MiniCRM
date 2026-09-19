<?php

namespace App\Modules\Settings\Workflow\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowAction extends Model
{
    protected $fillable = [
        'workflow_id',
        'type',
        'channel',
        'recipient',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }
}