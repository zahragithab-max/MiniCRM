<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflows', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('event');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('event');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflows');
    }
};