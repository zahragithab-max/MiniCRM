<?php

namespace App\Modules\Tickets\Services;

use App\Modules\Tickets\Models\TicketAttachment;
use App\Modules\Tickets\Models\TicketMessage;
use Illuminate\Http\UploadedFile;

class TicketAttachmentService
{
    public function create(
        TicketMessage $message,
        UploadedFile $file
    ): TicketAttachment {
        $path = $file->store('tickets/attachments');

        return $message->attachments()->create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);
    }
}