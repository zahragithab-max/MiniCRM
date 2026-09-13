<?php

namespace App\Modules\Tickets\Services;

use App\Modules\Tickets\Models\Ticket;
use App\Modules\Tickets\Models\TicketSatisfaction;
use Illuminate\Validation\ValidationException;

class TicketSatisfactionService
{
    public function create(
        Ticket $ticket,
        int $rating
    ): TicketSatisfaction {
        if ($ticket->status !== 'closed') {
            throw ValidationException::withMessages([
                'ticket' => 'Satisfaction can only be submitted for a closed ticket.',
            ]);
        }

        return $ticket->satisfaction()->create([
            'rating' => $rating,
        ]);
    }
}

