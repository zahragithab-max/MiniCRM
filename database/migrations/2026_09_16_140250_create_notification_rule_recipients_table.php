<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_rule_recipients', function (Blueprint $table) {
            $table->id();

            $table->foreignId('notification_rule_id')
                ->constrained('notification_rules')
                ->cascadeOnDelete();

            $table->string('type');
            $table->string('value');

            $table->timestamps();

            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_rule_recipients');
    }
};