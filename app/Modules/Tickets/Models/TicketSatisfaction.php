<?php

namespace App\Modules\Tickets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketSatisfaction extends Model
{
    protected $fillable = [
        'ticket_id',
        'rating',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}