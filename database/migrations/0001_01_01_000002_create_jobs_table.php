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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Warrior, Explorer, Scholar
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('icon')->nullable(); // Icon path or emoji
            $table->string('color')->default('#6366f1'); // Color for UI
            
            // Passive Skill
            $table->string('passive_skill_name');
            $table->text('passive_skill_description');
            
            // Bonus System
            $table->enum('bonus_type', ['xp_voice', 'xp_message', 'xp_event', 'xp_all'])->default('xp_all');
            $table->integer('bonus_percentage')->default(10); // 10% bonus
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
