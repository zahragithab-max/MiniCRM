<?php

namespace App\Modules\Tickets\Services;

use App\Modules\Settings\Auth\Models\User;
use App\Modules\Tickets\Models\Ticket;
use App\Modules\Tickets\Models\TicketMessage;

class TicketMessageService
{
    public function getAll(Ticket $ticket)
    {
        return $ticket->messages()
            ->with('user', 'attachments')
            ->latest()
            ->get();
    }

    public function create(
        Ticket $ticket,
        array $data,
        User $user
    ): TicketMessage {
        return $ticket->messages()->create([
            'user_id' => $user->id,
            'message' => $data['message'],
        ])->load([
            'user',
            'attachments',
        ]);
    }
}