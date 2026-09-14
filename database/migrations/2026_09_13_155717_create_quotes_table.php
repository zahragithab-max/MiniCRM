<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('deal_id')
                ->constrained('deals')
                ->cascadeOnDelete();

            $table->foreignId('account_id')
                ->constrained('accounts')
                ->restrictOnDelete();

            $table->foreignId('contact_id')
                ->nullable()
                ->constrained('contacts')
                ->nullOnDelete();

            $table->string('quote_number')->unique();

            $table->date('issue_date');
            $table->date('valid_until');

            $table->enum('status', [
                'draft',
                'sent',
                'accepted',
                'rejected',
                'expired',
            ])->default('draft');

            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('vat', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};