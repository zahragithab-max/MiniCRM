<?php

namespace App\Modules\Settings\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Settings\Company\Requests\UpdateCompanySettingRequest;
use App\Modules\Settings\Company\Requests\UploadCompanyLogoRequest;
use App\Modules\Settings\Company\Services\CompanySettingService;
use Illuminate\Http\JsonResponse;

class CompanySettingController extends Controller
{
    public function __construct(
        private CompanySettingService $companySettingService
    ) {
    }

    public function show(): JsonResponse
    {
        return $this->success(
            $this->companySettingService->get()
        );
    }

    public function update(
        UpdateCompanySettingRequest $request
    ): JsonResponse {
        return $this->success(
            $this->companySettingService->update(
                $request->validated()
            )
        );
    }

    public function uploadLogo(
        UploadCompanyLogoRequest $request
    ): JsonResponse {
        if (!$request->hasFile('logo')) {
            return response()->json([
                'message' => 'Logo file is required.',
            ], 422);
        }

        $file = $request->file('logo');

        if (!$file instanceof \Illuminate\Http\UploadedFile) {
            return response()->json([
                'message' => 'Invalid logo file.',
            ], 422);
        }

        return $this->success(
            $this->companySettingService->uploadLogo($file)
        );
    }

    public function deleteLogo(): JsonResponse
    {
        return $this->success(
            $this->companySettingService->deleteLogo()
        );
    }
}