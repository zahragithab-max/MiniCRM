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
        Schema::create('deal_stage_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('deal_id')
                ->constrained('deals')
                ->cascadeOnDelete();

            $table->foreignId('from_stage_id')
                ->nullable()
                ->constrained('deal_stages')
                ->nullOnDelete();

            $table->foreignId('to_stage_id')
                ->nullable()
                ->constrained('deal_stages')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deal_stage_histories');
    }
};
