<?php

namespace App\Modules\Settings\Notifications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationRuleRecipient extends Model
{
    protected $fillable = [
        'notification_rule_id',
        'type',
        'value',
    ];

    public function notificationRule(): BelongsTo
    {
        return $this->belongsTo(
            NotificationRule::class,
            'notification_rule_id'
        );
    }
}