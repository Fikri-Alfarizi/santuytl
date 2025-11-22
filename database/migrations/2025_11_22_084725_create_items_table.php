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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['avatar_frame', 'xp_boost', 'badge', 'profile_theme', 'other'])->default('other');
            $table->integer('price_coins')->default(0);
            $table->integer('duration_days')->nullable(); // For temporary items like boosts
            $table->integer('boost_percentage')->default(0); // For XP boosts
            $table->string('image')->nullable();
            $table->boolean('is_tradeable')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
