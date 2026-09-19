<?php

namespace App\Modules\Settings\Notifications\Services;

use App\Modules\Settings\Notifications\Models\NotificationRule;

class NotificationRuleService
{
    public function getAll()
    {
        return NotificationRule::with('workflow')
            ->latest()
            ->get();
    }

    public function create(array $data): NotificationRule
    {
        return NotificationRule::create($data);
    }

    public function update(
        NotificationRule $rule,
        array $data
    ): NotificationRule {
        $rule->update($data);

        return $rule->fresh('workflow');
    }
}