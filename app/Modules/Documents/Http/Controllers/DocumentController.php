<?php

namespace App\Modules\Documents\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Contacts\Models\Contact;
use App\Modules\Deals\Models\Deal;
use App\Modules\Documents\Http\Requests\StoreDocumentRequest;
use App\Modules\Documents\Models\Document;
use App\Modules\Documents\Services\DocumentService;
use App\Modules\Leads\Models\Lead;
use App\Modules\Tickets\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentController extends Controller
{
    public function __construct(
        private DocumentService $documentService
    ) {}

    public function index(string $type, int $id): JsonResponse
    {
        $model = $this->resolveDocumentable($type, $id);

        return $this->success(
            $model->documents()->latest()->get()
        );
    }

    public function store(
        StoreDocumentRequest $request,
        string $type,
        int $id
    ): JsonResponse {
        $model = $this->resolveDocumentable($type, $id);

        $file = $request->file('file');

        if (!$file instanceof UploadedFile) {
            return response()->json([
                'message' => 'Invalid file.',
            ], 422);
        }

        $document = $this->documentService->upload(
            $model,
            $file,
            $request->input('category')
        );

        return $this->success($document, 201);
    }

    public function download(int $id): BinaryFileResponse
    {
        $document = Document::findOrFail($id);

        return response()->download(
            $this->documentService->download($document),
            $document->name
        );
    }

    public function copy(int $id): JsonResponse
    {
        $document = Document::findOrFail($id);

        $copiedDocument = $this->documentService->copy($document);

        return $this->success($copiedDocument, 201);
    }

    private function resolveDocumentable(string $type, int $id)
    {
        return match ($type) {
            'lead' => Lead::findOrFail($id),
            'contact' => Contact::findOrFail($id),
            'deal' => Deal::findOrFail($id),
            'ticket' => Ticket::findOrFail($id),
            default => abort(404),
        };
    }
}