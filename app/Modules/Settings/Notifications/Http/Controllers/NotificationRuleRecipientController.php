<?php

namespace App\Modules\Settings\Notifications\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Settings\Notifications\Http\Requests\StoreNotificationRuleRecipientRequest;
use App\Modules\Settings\Notifications\Http\Requests\UpdateNotificationRuleRecipientRequest;
use App\Modules\Settings\Notifications\Models\NotificationRule;
use App\Modules\Settings\Notifications\Models\NotificationRuleRecipient;
use App\Modules\Settings\Notifications\Services\NotificationRuleRecipientService;
use Illuminate\Http\JsonResponse;

class NotificationRuleRecipientController extends Controller
{
    public function __construct(
        private NotificationRuleRecipientService $recipientService
    ) {}

    public function index(NotificationRule $notificationRule): JsonResponse
    {
        return $this->success(
            $this->recipientService->getAll($notificationRule)
        );
    }

    public function store(
        StoreNotificationRuleRecipientRequest $request,
        NotificationRule $notificationRule
    ): JsonResponse {
        return $this->success(
            $this->recipientService->create(
                $notificationRule,
                $request->validated()
            ),
            201
        );
    }

    public function update(
        UpdateNotificationRuleRecipientRequest $request,
        NotificationRuleRecipient $recipient
    ): JsonResponse {
        return $this->success(
            $this->recipientService->update(
                $recipient,
                $request->validated()
            )
        );
    }
}