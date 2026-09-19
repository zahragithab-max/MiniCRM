<?php

namespace App\Modules\Settings\Notifications\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Settings\Notifications\Http\Requests\StoreNotificationRuleRequest;
use App\Modules\Settings\Notifications\Http\Requests\UpdateNotificationRuleRequest;
use App\Modules\Settings\Notifications\Models\NotificationRule;
use App\Modules\Settings\Notifications\Services\NotificationRuleService;
use Illuminate\Http\JsonResponse;

class NotificationRuleController extends Controller
{
    public function __construct(
        private NotificationRuleService $notificationRuleService
    ) {}

    public function index(): JsonResponse
    {
        return $this->success(
            $this->notificationRuleService->getAll()
        );
    }

    public function store(
        StoreNotificationRuleRequest $request
    ): JsonResponse {
        return $this->success(
            $this->notificationRuleService->create(
                $request->validated()
            ),
            201
        );
    }

    public function update(
        UpdateNotificationRuleRequest $request,
        NotificationRule $notificationRule
    ): JsonResponse {
        return $this->success(
            $this->notificationRuleService->update(
                $notificationRule,
                $request->validated()
            )
        );
    }
}