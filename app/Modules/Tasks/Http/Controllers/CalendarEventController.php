<?php

namespace App\Modules\Tasks\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Tasks\Http\Requests\StoreCalendarEventRequest;
use App\Modules\Tasks\Http\Requests\UpdateCalendarEventRequest;
use App\Modules\Tasks\Services\CalendarEventService;
use Illuminate\Http\JsonResponse;

class CalendarEventController extends Controller
{
    public function __construct(
        private CalendarEventService $calendarEventService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(
            $this->calendarEventService->getAll()
        );
    }

    public function store(StoreCalendarEventRequest $request): JsonResponse
    {
        $event = $this->calendarEventService->create(
            $request->validated()
        );

        return response()->json($event, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(
            $this->calendarEventService->findById($id)
        );
    }

    public function update(
        UpdateCalendarEventRequest $request,
        int $id
    ): JsonResponse {
        $event = $this->calendarEventService->findById($id);

        $updatedEvent = $this->calendarEventService->update(
            $event,
            $request->validated()
        );

        return response()->json($updatedEvent);
    }

    public function destroy(int $id): JsonResponse
    {
        $event = $this->calendarEventService->findById($id);

        $this->calendarEventService->delete($event);

        return response()->json([
            'message' => 'Calendar event deleted successfully.',
        ]);
    }
}