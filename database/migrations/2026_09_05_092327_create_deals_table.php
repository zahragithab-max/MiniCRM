<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();

            $table->string('title');

            $table->foreignId('account_id')
                ->constrained('accounts')
                ->cascadeOnDelete();

            $table->foreignId('contact_id')
                ->nullable()
                ->constrained('contacts')
                ->nullOnDelete();

            $table->foreignId('owner_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->decimal('amount', 15, 2)->default(0);

            $table->unsignedTinyInteger('probability')->default(0);

            $table->date('expected_close_date')->nullable();

            $table->enum('status', ['open', 'won', 'lost'])
                ->default('open');

            $table->text('loss_reason')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};