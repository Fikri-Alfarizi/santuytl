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
        Schema::table('events', function (Blueprint $table) {
            $table->string('tier')->default('special')->after('type'); // daily, weekly, monthly, special
            $table->integer('coin_reward')->default(0)->after('xp_reward');
            $table->boolean('auto_reward')->default(false)->after('vip_days_reward');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['tier', 'coin_reward', 'auto_reward']);
        });
    }
};
