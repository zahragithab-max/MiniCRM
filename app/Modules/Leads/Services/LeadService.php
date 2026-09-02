<?php

namespace App\Modules\Leads\Services;

use App\Modules\Leads\Models\Lead;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LeadService
{
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return Lead::with('assignedUser')
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): Lead
    {
        /** @var Lead $lead */
        $lead = Lead::with('assignedUser')->findOrFail($id);
    
        return $lead;
    }

    public function create(array $data): Lead
    {
        return Lead::create($data);
    }

    public function update(Lead $lead, array $data): Lead
    {
        $lead->update($data);

        return $lead->fresh('assignedUser');
    }

    public function delete(Lead $lead): void
    {
        $lead->delete();
    }

    public function export(): \Symfony\Component\HttpFoundation\StreamedResponse
{
    $fileName = 'leads-' . now()->format('Y-m-d-H-i-s') . '.csv';

    return response()->streamDownload(function () {
        $handle = fopen('php://output', 'w');

        fputcsv($handle, [
            'ID',
            'First Name',
            'Last Name',
            'Company Name',
            'Email',
            'Phone',
            'Source',
            'Status',
            'Assigned To',
            'Notes',
            'Created At',
        ]);

        Lead::with('assignedUser')
            ->latest()
            ->chunk(500, function ($leads) use ($handle) {
                foreach ($leads as $lead) {
                    fputcsv($handle, [
                        $lead->id,
                        $lead->first_name,
                        $lead->last_name,
                        $lead->company_name,
                        $lead->email,
                        $lead->phone,
                        $lead->source,
                        $lead->status,
                        $lead->assignedUser?->name,
                        $lead->notes,
                        $lead->created_at?->toDateTimeString(),
                    ]);
                }
            });

        fclose($handle);
    }, $fileName, [
        'Content-Type' => 'text/csv; charset=UTF-8',
    ]);
}

    public function restore(int $id): Lead
    {
        $lead = Lead::withTrashed()->findOrFail($id);

        $lead->restore();

        return $lead->fresh('assignedUser');
    }
}