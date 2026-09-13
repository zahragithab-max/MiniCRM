<?php

namespace App\Modules\Tickets\Models;

use App\Modules\Settings\Auth\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    protected $fillable = [
        'subject',
        'description',
        'category',
        'status',
        'priority',
        'created_by',
        'assigned_to',
        'sla_hours',
        'sla_due_at',
        'sla_escalated_at',
    ];

    protected $casts = [
        'sla_due_at' => 'datetime',
        'sla_escalated_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function satisfaction(): HasOne
    {
        return $this->hasOne(TicketSatisfaction::class);
    }
}