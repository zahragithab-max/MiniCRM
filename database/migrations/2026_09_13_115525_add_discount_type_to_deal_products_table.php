<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deal_products', function (Blueprint $table) {
            $table->enum('discount_type', ['fixed', 'percentage'])
                ->default('fixed')
                ->after('discount');
        });
    }

    public function down(): void
    {
        Schema::table('deal_products', function (Blueprint $table) {
            $table->dropColumn('discount_type');
        });
    }
};