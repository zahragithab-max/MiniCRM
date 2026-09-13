<?php

namespace App\Modules\Tickets\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Tickets\Requests\StoreTicketMessageRequest;
use App\Modules\Tickets\Services\TicketMessageService;
use App\Modules\Tickets\Services\TicketService;
use Illuminate\Http\JsonResponse;

class TicketMessageController extends Controller
{
    public function __construct(
        private TicketMessageService $ticketMessageService,
        private TicketService $ticketService
    ) {}

    public function index(int $ticketId): JsonResponse
    {
        $ticket = $this->ticketService->findById($ticketId);

        return $this->success(
            $this->ticketMessageService->getAll($ticket)
        );
    }

    public function store(
        StoreTicketMessageRequest $request,
        int $ticketId
    ): JsonResponse {
        $ticket = $this->ticketService->findById($ticketId);

        $message = $this->ticketMessageService->create(
            $ticket,
            $request->validated(),
            $request->user()
        );

        return $this->success($message, 201);
    }
}