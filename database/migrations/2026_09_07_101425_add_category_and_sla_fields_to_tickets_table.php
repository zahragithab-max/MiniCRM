<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('category')->after('description');

            $table->foreignId('created_by')
                ->nullable()
                ->after('category')
                ->constrained('users')
                ->nullOnDelete();

            $table->unsignedInteger('sla_hours')
                ->nullable()
                ->after('assigned_to');

            $table->dateTime('sla_due_at')
                ->nullable()
                ->after('sla_hours');

            $table->dateTime('sla_escalated_at')
                ->nullable()
                ->after('sla_due_at');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['created_by']);

            $table->dropColumn([
                'category',
                'created_by',
                'sla_hours',
                'sla_due_at',
                'sla_escalated_at',
            ]);
        });
    }
};