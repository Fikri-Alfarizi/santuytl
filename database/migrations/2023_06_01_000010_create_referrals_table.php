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
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('referred_user_id')->constrained('users')->onDelete('cascade');
            // Referral Code
            $table->string('code')->unique();
            // Referral Status
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            // Rewards
            $table->integer('xp_reward')->default(0);
            $table->integer('vip_days_reward')->default(0);
            $table->timestamps();
            $table->unique(['referrer_id', 'referred_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('referrals');
    }
};
