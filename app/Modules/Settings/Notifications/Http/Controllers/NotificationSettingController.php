<?php

namespace App\Modules\Settings\Notifications\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Settings\Notifications\Http\Requests\UpdateNotificationSettingRequest;
use App\Modules\Settings\Notifications\Models\NotificationSetting;
use App\Modules\Settings\Notifications\Services\NotificationSettingService;
use Illuminate\Http\JsonResponse;

class NotificationSettingController extends Controller
{
    public function __construct(
        private NotificationSettingService $notificationSettingService
    ) {}

    public function index(): JsonResponse
    {
        return $this->success(
            $this->notificationSettingService->getAll()
        );
    }

    public function update(
        UpdateNotificationSettingRequest $request,
        NotificationSetting $notificationSetting
    ): JsonResponse {
        $setting = $this->notificationSettingService->update(
            $notificationSetting,
            $request->validated()
        );

        return $this->success($setting);
    }
}
