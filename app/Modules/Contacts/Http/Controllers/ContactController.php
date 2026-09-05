<?php

namespace App\Modules\Contacts\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Contacts\Requests\SendContactEmailRequest;
use App\Modules\Contacts\Requests\StoreContactRequest;
use App\Modules\Contacts\Requests\UpdateContactRequest;
use App\Modules\Contacts\Services\ContactEmailService;
use App\Modules\Contacts\Services\ContactService;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function __construct(
        private ContactService $contactService,
        private ContactEmailService $contactEmailService
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json(
            $this->contactService->getAll()
        );
    }

    public function store(StoreContactRequest $request): JsonResponse
    {
        $contact = $this->contactService->create(
            $request->validated()
        );

        return response()->json($contact, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(
            $this->contactService->findById($id)
        );
    }

    public function update(
        UpdateContactRequest $request,
        int $id
    ): JsonResponse {
        $contact = $this->contactService->findById($id);

        $updatedContact = $this->contactService->update(
            $contact,
            $request->validated()
        );

        return response()->json($updatedContact);
    }

    public function destroy(int $id): JsonResponse
    {
        $contact = $this->contactService->findById($id);

        $this->contactService->delete($contact);

        return response()->json([
            'message' => 'Contact deleted successfully.',
        ]);
    }

    public function restore(int $id): JsonResponse
    {
        $contact = $this->contactService->restore($id);

        return response()->json($contact);
    }

    public function sendEmail(
        SendContactEmailRequest $request,
        int $id
    ): JsonResponse {
        $contact = $this->contactService->findById($id);

        $this->contactEmailService->send(
            $contact,
            $request->validated('subject'),
            $request->validated('bodyText')
        );

        return response()->json([
            'message' => 'Email sent successfully.',
        ]);
    }
}