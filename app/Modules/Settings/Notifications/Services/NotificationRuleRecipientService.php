<?php

namespace App\Modules\Settings\Notifications\Services;

use App\Modules\Settings\Notifications\Models\NotificationRule;
use App\Modules\Settings\Notifications\Models\NotificationRuleRecipient;

class NotificationRuleRecipientService
{
    public function getAll(NotificationRule $rule)
    {
        return $rule->recipients()->latest()->get();
    }

    public function create(
        NotificationRule $rule,
        array $data
    ): NotificationRuleRecipient {
        return $rule->recipients()->create($data);
    }

    public function update(
        NotificationRuleRecipient $recipient,
        array $data
    ): NotificationRuleRecipient {
        $recipient->update($data);

        return $recipient->fresh();
    }
}