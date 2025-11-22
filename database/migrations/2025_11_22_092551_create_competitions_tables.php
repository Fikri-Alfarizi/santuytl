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
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('type')->default('team'); // team, individual
            $table->string('status')->default('upcoming'); // upcoming, active, completed
            $table->json('rewards')->nullable();
            $table->timestamps();
        });

        Schema::create('competition_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained('competitions')->onDelete('cascade');
            $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->bigInteger('score')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_participants');
        Schema::dropIfExists('competitions');
    }
};
