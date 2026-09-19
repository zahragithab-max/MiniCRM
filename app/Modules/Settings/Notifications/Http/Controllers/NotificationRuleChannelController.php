<?php

namespace App\Modules\Settings\Notifications\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Settings\Notifications\Http\Requests\StoreNotificationRuleChannelRequest;
use App\Modules\Settings\Notifications\Http\Requests\UpdateNotificationRuleChannelRequest;
use App\Modules\Settings\Notifications\Models\NotificationRule;
use App\Modules\Settings\Notifications\Models\NotificationRuleChannel;
use App\Modules\Settings\Notifications\Services\NotificationRuleChannelService;
use Illuminate\Http\JsonResponse;

class NotificationRuleChannelController extends Controller
{
    public function __construct(
        private NotificationRuleChannelService $channelService
    ) {}

    public function index(NotificationRule $notificationRule): JsonResponse
    {
        return $this->success(
            $this->channelService->getAll($notificationRule)
        );
    }

    public function store(
        StoreNotificationRuleChannelRequest $request,
        NotificationRule $notificationRule
    ): JsonResponse {
        return $this->success(
            $this->channelService->create(
                $notificationRule,
                $request->validated()
            ),
            201
        );
    }

    public function update(
        UpdateNotificationRuleChannelRequest $request,
        NotificationRuleChannel $channel
    ): JsonResponse {
        return $this->success(
            $this->channelService->update(
                $channel,
                $request->validated()
            )
        );
    }
}