<?php

namespace App\Modules\Tickets\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Tickets\Requests\StoreTicketAttachmentRequest;
use App\Modules\Tickets\Models\TicketMessage;
use App\Modules\Tickets\Services\TicketAttachmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;

class TicketAttachmentController extends Controller
{
    public function __construct(
        private TicketAttachmentService $ticketAttachmentService
    ) {}

    public function store(
        StoreTicketAttachmentRequest $request,
        int $messageId
    ): JsonResponse {
        $message = TicketMessage::findOrFail($messageId);

        /** @var UploadedFile $file */
        $file = $request->file('file');

        $attachment = $this->ticketAttachmentService->create(
            $message,
            $file
        );

        return $this->success($attachment, 201);
    }
}