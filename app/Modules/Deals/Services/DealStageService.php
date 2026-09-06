<?php

namespace App\Modules\Deals\Services;

use App\Modules\Deals\Models\DealStage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DealStageService
{
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return DealStage::query()
            ->orderBy('sort_order')
            ->paginate($perPage);
    }

    public function findById(int $id): DealStage
    {
        return DealStage::findOrFail($id);
    }

    public function create(array $data): DealStage
    {
        return DealStage::create($data);
    }

    public function update(DealStage $stage, array $data): DealStage
    {
        $stage->update($data);

        return $stage->fresh();
    }

    public function delete(DealStage $stage): void
    {
        $stage->delete();
    }
}