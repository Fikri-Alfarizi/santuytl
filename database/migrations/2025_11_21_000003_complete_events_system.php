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
        // Update events table to add missing columns
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'slug')) {
                $table->string('slug')->unique()->after('title');
            }
            if (!Schema::hasColumn('events', 'type')) {
                $table->enum('type', ['screenshot', 'custom', 'quiz', 'tournament'])->default('custom')->after('description');
            }
            if (!Schema::hasColumn('events', 'access_level')) {
                $table->enum('access_level', ['all', 'vip'])->default('all')->after('type');
            }
            if (!Schema::hasColumn('events', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('access_level');
            }
            if (!Schema::hasColumn('events', 'ends_at')) {
                $table->dateTime('ends_at')->nullable()->after('date');
            }
        });

        // Create event_participants table
        if (!Schema::hasTable('event_participants')) {
            Schema::create('event_participants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->enum('status', ['registered', 'submitted', 'winner'])->default('registered');
                $table->json('submission_data')->nullable();
                $table->integer('votes')->default(0);
                $table->timestamps();
                
                // Prevent duplicate registrations
                $table->unique(['event_id', 'user_id']);
            });
        }

        // Create event_votes table
        if (!Schema::hasTable('event_votes')) {
            Schema::create('event_votes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->constrained()->onDelete('cascade');
                $table->foreignId('participant_id')->constrained('event_participants')->onDelete('cascade');
                $table->foreignId('voter_id')->constrained('users')->onDelete('cascade');
                $table->timestamps();
                
                // Prevent duplicate votes
                $table->unique(['event_id', 'voter_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_votes');
        Schema::dropIfExists('event_participants');
        
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['slug', 'type', 'access_level', 'is_active', 'ends_at']);
        });
    }
};
