<?php

namespace App\Modules\Settings\Notifications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationRuleChannel extends Model
{
    protected $fillable = [
        'notification_rule_id',
        'channel',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function notificationRule(): BelongsTo
    {
        return $this->belongsTo(
            NotificationRule::class,
            'notification_rule_id'
        );
    }
}