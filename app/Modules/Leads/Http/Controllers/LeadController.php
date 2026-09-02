<?php

namespace App\Modules\Leads\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Leads\Requests\StoreLeadRequest;
use App\Modules\Leads\Requests\UpdateLeadRequest;
use App\Modules\Leads\Services\LeadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function __construct(
        private LeadService $leadService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 15);

        return response()->json(
            $this->leadService->getAll($perPage)
        );
    }

    public function store(StoreLeadRequest $request): JsonResponse
    {
        $lead = $this->leadService->create(
            $request->validated()
        );

        return response()->json($lead, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(
            $this->leadService->findById($id)
        );
    }

    public function update(
        UpdateLeadRequest $request,
        int $id
    ): JsonResponse {
        $lead = $this->leadService->findById($id);

        $updatedLead = $this->leadService->update(
            $lead,
            $request->validated()
        );

        return response()->json($updatedLead);
    }

    public function destroy(int $id): JsonResponse
    {
        $lead = $this->leadService->findById($id);

        $this->leadService->delete($lead);

        return response()->json([
            'message' => 'Lead deleted successfully.',
        ]);
    }

    public function export(): \Symfony\Component\HttpFoundation\StreamedResponse
{
    return $this->leadService->export();
}

    public function restore(int $id): JsonResponse
    {
        $lead = $this->leadService->restore($id);

        return response()->json($lead);
    }
}