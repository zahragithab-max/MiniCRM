<?php

namespace App\Modules\Deals\Services;

use App\Modules\Deals\Events\DealStageChanged;
use App\Modules\Deals\Models\Deal;
use App\Modules\Deals\Models\DealStageHistory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DealService
{
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return Deal::with(['account', 'contact', 'owner', 'stage'])
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): Deal
    {
        /** @var Deal $deal */
        $deal = Deal::with(['account', 'contact', 'owner', 'stage'])
            ->findOrFail($id);

        return $deal;
    }

    public function create(array $data): Deal
    {
        $deal = Deal::create($data);

        if ($deal->stage_id) {
            DealStageHistory::create([
                'deal_id' => $deal->id,
                'from_stage_id' => null,
                'to_stage_id' => $deal->stage_id,
            ]);
        }

        return $deal->load(['account', 'contact', 'owner', 'stage']);
    }

    public function update(Deal $deal, array $data): Deal
    {
        $oldStageId = $deal->stage_id;

        $deal->update($data);

        $updatedDeal = $deal->fresh(['account', 'contact', 'owner', 'stage']);

        if (
            array_key_exists('stage_id', $data) &&
            (int) $oldStageId !== (int) $updatedDeal->stage_id
        ) {
            DealStageChanged::dispatch(
                $updatedDeal,
                (int) $oldStageId,
                (int) $updatedDeal->stage_id
            );
        }

        return $updatedDeal;
    }

    public function delete(Deal $deal): void
    {
        $deal->delete();
    }
}

