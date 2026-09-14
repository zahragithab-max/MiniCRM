<?php

namespace App\Modules\Settings\System\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Settings\System\Requests\UpdateVatRateRequest;
use App\Modules\Settings\System\Services\SystemSettingService;
use Illuminate\Http\JsonResponse;

class SystemSettingController extends Controller
{
    public function __construct(
        private SystemSettingService $systemSettingService
    ) {}

    public function vat(): JsonResponse
    {
        return $this->success([
            'vat_rate' => $this->systemSettingService->getVatRate(),
        ]);
    }

    public function updateVat(UpdateVatRateRequest $request): JsonResponse
    {
        $setting = $this->systemSettingService->setVatRate(
            (float) $request->validated('vat_rate')
        );

        return $this->success([
            'key' => $setting->key,
            'value' => (float) $setting->value,
        ]);
    }
}