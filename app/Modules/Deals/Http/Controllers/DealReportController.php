<?php

namespace App\Modules\Deals\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Deals\Services\DealReportService;
use Illuminate\Http\JsonResponse;

class DealReportController extends Controller
{
    public function __construct(
        private DealReportService $dealReportService
    ) {}

    public function conversionRate(): JsonResponse
    {
        return response()->json(
            $this->dealReportService->conversionRate()
        );
    }
}
