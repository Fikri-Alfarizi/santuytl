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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            // Avatar URL (full URL, not just filename)
            $table->string('avatar')->nullable();
            // Discord Integration
            $table->string('discord_id')->nullable()->unique();
            $table->string('discord_username')->nullable();
            $table->string('discord_discriminator')->nullable();
            $table->string('discord_avatar')->nullable(); // Store Discord avatar hash
            // User Role System
            $table->enum('role', ['member', 'vip', 'moderator', 'admin'])->default('member');
            $table->enum('level', ['warga_baru', 'pemula', 'belajar_pro', 'suhu', 'sepuh'])->default('warga_baru');
            // User Status
            $table->boolean('is_banned')->default(false);
            $table->text('ban_reason')->nullable();
            $table->timestamp('banned_until')->nullable();
            // VIP Status
            $table->timestamp('vip_expires_at')->nullable();
            // Remember Token
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
