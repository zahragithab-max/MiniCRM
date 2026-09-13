<?php

namespace App\Modules\Tickets\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Tickets\Requests\StoreTicketSatisfactionRequest;
use App\Modules\Tickets\Services\TicketSatisfactionService;
use App\Modules\Tickets\Services\TicketService;
use Illuminate\Http\JsonResponse;

class TicketSatisfactionController extends Controller
{
    public function __construct(
        private TicketSatisfactionService $ticketSatisfactionService,
        private TicketService $ticketService
    ) {}

    public function store(
        StoreTicketSatisfactionRequest $request,
        int $ticketId
    ): JsonResponse {
        $ticket = $this->ticketService->findById($ticketId);

        $satisfaction = $this->ticketSatisfactionService->create(
            $ticket,
            (int) $request->validated()['rating']
        );

        return $this->success($satisfaction, 201);
    }
}
