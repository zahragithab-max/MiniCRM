<?php

namespace App\Modules\Deals\Services;

use App\Modules\Deals\Models\DealStage;
use App\Modules\Deals\Models\DealStageHistory;

class DealReportService
{
    public function conversionRate(): array
    {
        $stages = DealStage::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $result = [];

        foreach ($stages as $stage) {
            $entered = DealStageHistory::query()
                ->where('to_stage_id', $stage->id)
                ->distinct('deal_id')
                ->count('deal_id');

            $nextStage = $stages
                ->firstWhere('sort_order', '>', $stage->sort_order);

            $converted = 0;

            if ($nextStage) {
                $converted = DealStageHistory::query()
                    ->where('from_stage_id', $stage->id)
                    ->where('to_stage_id', $nextStage->id)
                    ->distinct('deal_id')
                    ->count('deal_id');
            }

            $rate = $entered > 0
                ? round(($converted / $entered) * 100, 2)
                : 0;

            $result[] = [
                'stage_id' => $stage->id,
                'stage_name' => $stage->name,
                'entered_deals' => $entered,
                'converted_to_next_stage' => $converted,
                'conversion_rate' => $rate,
                'next_stage' => $nextStage?->name,
            ];
        }

        return $result;
    }
}