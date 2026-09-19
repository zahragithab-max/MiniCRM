<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_rule_channels', function (Blueprint $table) {
            $table->id();

            $table->foreignId('notification_rule_id')
                ->constrained('notification_rules')
                ->cascadeOnDelete();

            $table->string('channel');
            $table->boolean('is_enabled')->default(true);

            $table->timestamps();

            $table->index('channel');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_rule_channels');
    }
};