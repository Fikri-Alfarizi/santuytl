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
        Schema::table('items', function (Blueprint $table) {
            $table->enum('rarity', ['common', 'rare', 'epic', 'legendary', 'mythic'])->default('common')->after('type');
            $table->decimal('gacha_chance', 5, 2)->default(0)->after('rarity'); // Percentage 0-100
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['rarity', 'gacha_chance']);
        });
    }
};
