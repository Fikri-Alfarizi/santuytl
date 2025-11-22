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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('rules')->nullable();
            // Event Schedule
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            // Event Type
            $table->enum('type', ['screenshot', 'tournament', 'quiz', 'custom'])->default('custom');
            // Participation Requirements
            $table->enum('min_level', ['warga_baru', 'pemula', 'belajar_pro', 'suhu', 'sepuh'])->default('warga_baru');
            $table->enum('access_level', ['member', 'vip'])->default('member');
            // Rewards
            $table->integer('xp_reward')->default(0);
            $table->string('badge_reward')->nullable();
            $table->string('role_reward')->nullable();
            $table->integer('vip_days_reward')->default(0);
            // Event Status
            $table->boolean('is_active')->default(false);
            $table->boolean('is_featured')->default(false);
            // Event Media
            $table->string('banner_image')->nullable();
            $table->timestamps();
        });
        Schema::create('event_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Participation Data
            $table->text('submission_data')->nullable(); // JSON data for submission
            $table->integer('votes')->default(0);
            // Participation Status
            $table->enum('status', ['registered', 'submitted', 'winner', 'disqualified'])->default('registered');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            $table->unique(['event_id', 'user_id']);
        });
        Schema::create('event_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('participant_id')->constrained('event_participants')->onDelete('cascade');
            $table->foreignId('voter_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['event_id', 'voter_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_votes');
        Schema::dropIfExists('event_participants');
        Schema::dropIfExists('events');
    }
};
