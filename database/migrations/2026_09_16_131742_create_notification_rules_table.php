<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_rules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('workflow_id')
                ->nullable()
                ->constrained('workflows')
                ->nullOnDelete();

            $table->string('name');
            $table->string('event');
            $table->text('message');

            $table->boolean('is_enabled')->default(true);

            $table->timestamps();

            $table->index('event');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_rules');
    }
};