<?php

namespace App\Modules\Tickets\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Tickets\Requests\StoreTicketRequest;
use App\Modules\Tickets\Requests\UpdateTicketRequest;
use App\Modules\Tickets\Services\TicketService;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function __construct(
        private TicketService $ticketService
    ) {}

    public function index(): JsonResponse
    {
        return $this->success(
            $this->ticketService->getAll()
        );
    }

    public function store(StoreTicketRequest $request): JsonResponse
    {
        $ticket = $this->ticketService->create(
            $request->validated(),
            $request->user()
        );

        return $this->success($ticket, 201);
    }

    public function show(int $id): JsonResponse
    {
        return $this->success(
            $this->ticketService->findById($id)
        );
    }

    public function update(
        UpdateTicketRequest $request,
        int $id
    ): JsonResponse {
        $ticket = $this->ticketService->findById($id);

        $updatedTicket = $this->ticketService->update(
            $ticket,
            $request->validated()
        );

        return $this->success($updatedTicket);
    }

    public function destroy(int $id): JsonResponse
    {
        $ticket = $this->ticketService->findById($id);

        $this->ticketService->delete($ticket);

        return $this->success([
            'message' => 'Ticket deleted successfully.',
        ]);
    }
}