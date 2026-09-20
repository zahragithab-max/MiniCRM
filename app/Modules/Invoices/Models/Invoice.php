<?php

namespace App\Modules\Invoices\Models;

use App\Modules\Accounts\Models\Account;
use App\Modules\Contacts\Models\Contact;
use App\Modules\Deals\Models\Deal;
use App\Modules\Quotes\Models\Quote;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'deal_id',
        'account_id',
        'contact_id',
        'quote_id',
        'invoice_number',
        'address',
        'issue_date',
        'due_date',
        'status',
        'vat_enabled',
        'vat_rate',
        'subtotal',
        'vat',
        'grand_total',
        'currency',
        'usd_rate_snapshot',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'vat_enabled' => 'boolean',
        'vat_rate' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'vat' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'usd_rate_snapshot' => 'decimal:4',
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

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }
}