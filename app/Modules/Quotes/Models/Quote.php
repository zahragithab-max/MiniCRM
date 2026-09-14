<?php

namespace App\Modules\Quotes\Models;

use App\Modules\Accounts\Models\Account;
use App\Modules\Contacts\Models\Contact;
use App\Modules\Deals\Models\Deal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
    protected $fillable = [
        'deal_id',
        'account_id',
        'contact_id',
        'quote_number',
        'issue_date',
        'valid_until',
        'status',
        'vat_enabled',
        'vat_rate',
        'subtotal',
        'vat',
        'grand_total',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'valid_until' => 'date',
        'vat_enabled' => 'boolean',
        'vat_rate' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'vat' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }
}