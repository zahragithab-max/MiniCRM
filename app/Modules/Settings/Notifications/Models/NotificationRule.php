<?php

namespace App\Modules\Settings\Notifications\Models;

use App\Modules\Settings\Workflow\Models\Workflow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Modules\Settings\Notifications\Models\NotificationRuleChannel;

class NotificationRule extends Model
{
    protected $fillable = [
        'workflow_id',
        'name',
        'event',
        'message',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(
            NotificationRuleRecipient::class,
            'notification_rule_id'
        );
    }

    public function channels(): HasMany
{
    return $this->hasMany(
        NotificationRuleChannel::class,
        'notification_rule_id'
    );
}

}