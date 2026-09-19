<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_actions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('workflow_id')
                ->constrained('workflows')
                ->cascadeOnDelete();

            $table->string('type');
            $table->string('channel')->default('mail');
            $table->string('recipient');
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index('type');
            $table->index('recipient');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_actions');
    }
};