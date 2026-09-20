<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('currency', 3)
                ->default('IRT')
                ->after('grand_total');

            $table->decimal('usd_rate_snapshot', 20, 4)
                ->nullable()
                ->after('currency');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'currency',
                'usd_rate_snapshot',
            ]);
        });
    }
};