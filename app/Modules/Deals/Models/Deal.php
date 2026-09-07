<?php

namespace App\Modules\Deals\Models;

use App\Modules\Accounts\Models\Account;
use App\Modules\Contacts\Models\Contact;
use App\Modules\Settings\Auth\Models\User;
use App\Modules\Deals\Models\DealStage;
use App\Modules\Tasks\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deal extends Model
{
    protected $fillable = [
        'title',
        'account_id',
        'contact_id',
        'owner_id',
        'stage_id',
        'amount',
        'probability',
        'expected_close_date',
        'status',
        'loss_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'probability' => 'integer',
        'expected_close_date' => 'date',
    ];

    public function stage(): BelongsTo
    {
        return $this->belongsTo(DealStage::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'taskable_id')
            ->where('taskable_type', Task::class);
    }
}