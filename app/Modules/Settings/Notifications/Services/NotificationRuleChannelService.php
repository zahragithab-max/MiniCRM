<?php

namespace App\Modules\Settings\Notifications\Services;

use App\Modules\Settings\Notifications\Models\NotificationRule;
use App\Modules\Settings\Notifications\Models\NotificationRuleChannel;

class NotificationRuleChannelService
{
    public function getAll(NotificationRule $rule)
    {
        return $rule->channels()->latest()->get();
    }

    public function create(
        NotificationRule $rule,
        array $data
    ): NotificationRuleChannel {
        return $rule->channels()->create($data);
    }

    public function update(
        NotificationRuleChannel $channel,
        array $data
    ): NotificationRuleChannel {
        $channel->update($data);

        return $channel->fresh();
    }
}