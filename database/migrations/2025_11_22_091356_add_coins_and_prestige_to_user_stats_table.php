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
        Schema::table('user_stats', function (Blueprint $table) {
            $table->bigInteger('coins')->default(0)->after('xp_to_next_level');
            $table->integer('prestige_level')->default(0)->after('level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_stats', function (Blueprint $table) {
            if (Schema::hasColumn('user_stats', 'coins')) {
                $table->dropColumn('coins');
            }
            if (Schema::hasColumn('user_stats', 'prestige_level')) {
                $table->dropColumn('prestige_level');
            }
        });
    }
};
