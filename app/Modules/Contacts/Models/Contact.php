<?php

namespace App\Modules\Contacts\Models;

use App\Modules\Accounts\Models\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'account_id',
        'name',
        'customer_category',
        'address',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function phones(): HasMany
    {
        return $this->hasMany(ContactPhone::class);
    }

    public function emails(): HasMany
    {
        return $this->hasMany(ContactEmail::class);
    }
}