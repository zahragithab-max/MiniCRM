<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deal_products', function (Blueprint $table) {
            $table->unique(
                ['deal_id', 'product_id'],
                'deal_products_deal_id_product_id_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('deal_products', function (Blueprint $table) {
            $table->dropUnique(
                'deal_products_deal_id_product_id_unique'
            );
        });
    }
};