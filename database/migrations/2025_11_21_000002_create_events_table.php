<?php
// database/migrations/2025_11_21_000002_create_events_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->dateTime('date')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('discord_message_id')->nullable();
            $table->integer('xp_reward')->default(0);
            $table->integer('vip_days_reward')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
