<?php

namespace App\Modules\Tasks\Services;

use App\Modules\Tasks\Models\CalendarEvent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CalendarEventService
{
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return CalendarEvent::with('user')
            ->latest('start_at')
            ->paginate($perPage);
    }

    public function findById(int $id): CalendarEvent
    {
        /** @var CalendarEvent $event */
        $event = CalendarEvent::with('user')
            ->findOrFail($id);

        return $event;
    }

    public function create(array $data): CalendarEvent
    {
        return CalendarEvent::create($data)
            ->load('user');
    }

    public function update(CalendarEvent $event, array $data): CalendarEvent
    {
        $event->update($data);

        return $event->fresh('user');
    }

    public function delete(CalendarEvent $event): void
    {
        $event->delete();
    }
}