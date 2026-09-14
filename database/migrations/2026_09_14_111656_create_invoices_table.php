<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('deal_id')
                ->constrained('deals')
                ->restrictOnDelete();

            $table->foreignId('account_id')
                ->constrained('accounts')
                ->restrictOnDelete();

            $table->foreignId('contact_id')
                ->nullable()
                ->constrained('contacts')
                ->nullOnDelete();

            $table->foreignId('quote_id')
                ->nullable()
                ->constrained('quotes')
                ->nullOnDelete();

            $table->string('invoice_number')->unique();

            $table->text('address')->nullable();

            $table->date('issue_date');
            $table->date('due_date');

            $table->boolean('vat_enabled')->default(true);
            $table->decimal('vat_rate', 5, 2)->default(0);

            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('vat', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

