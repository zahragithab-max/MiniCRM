<?php

namespace App\Modules\Tickets\Services;

use App\Modules\Settings\Auth\Models\User;
use App\Modules\Tickets\Models\Ticket;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TicketService
{
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return Ticket::with([
            'creator',
            'assignee',
            'messages',
            'satisfaction',
        ])
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): Ticket
    {
        /** @var Ticket $ticket */
        $ticket = Ticket::with([
            'creator',
            'assignee',
            'messages',
            'satisfaction',
        ])->findOrFail($id);

        return $ticket;
    }

    public function create(array $data, User $user): Ticket
    {
        if (!array_key_exists('created_by', $data)) {
            $data['created_by'] = $user->id;
        }

        return Ticket::create($data)
            ->load([
                'creator',
                'assignee',
                'messages',
                'satisfaction',
            ]);
    }

    public function update(Ticket $ticket, array $data): Ticket
    {
        $ticket->update($data);

        return $ticket->fresh([
            'creator',
            'assignee',
            'messages',
            'satisfaction',
        ]);
    }

    public function delete(Ticket $ticket): void
    {
        $ticket->delete();
    }
}