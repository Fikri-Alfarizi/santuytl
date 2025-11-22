<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coin_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->bigInteger('amount'); // Positive for earn, negative for spend
            $table->enum('type', ['earn', 'spend', 'trade', 'bank_deposit', 'bank_withdraw', 'interest', 'market_buy', 'market_sell', 'admin_adjustment'])->default('earn');
            $table->string('description')->nullable();
            $table->json('metadata')->nullable(); // Extra data like related item_id, trade_id etc
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coin_transactions');
    }
};
