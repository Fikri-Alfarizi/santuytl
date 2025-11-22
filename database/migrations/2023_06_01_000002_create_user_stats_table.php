<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // XP and Level System
            $table->integer('xp')->default(0);
            $table->integer('level')->default(1);
            $table->integer('xp_to_next_level')->default(100);
            // Activity Stats
            $table->integer('messages_count')->default(0);
            $table->integer('games_downloaded')->default(0);
            $table->integer('requests_made')->default(0);
            $table->integer('events_participated')->default(0);
            $table->integer('events_won')->default(0);
            // Last Activity
            $table->timestamp('last_active_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_stats');
    }
};
