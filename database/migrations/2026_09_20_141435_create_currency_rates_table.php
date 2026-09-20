<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('currency_rates', function (Blueprint $table) {
            $table->id();

            $table->string('from_currency', 3);

            $table->string('to_currency', 3);

            $table->decimal('rate', 20, 8);

            $table->timestamp('effective_at')->useCurrent();

            $table->timestamps();

            $table->index([
                'from_currency',
                'to_currency',
                'effective_at',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_rates');
    }
};