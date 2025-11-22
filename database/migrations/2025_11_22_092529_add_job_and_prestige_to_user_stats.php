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
            // Job/Class System
            if (!Schema::hasColumn('user_stats', 'job_id')) {
                $table->foreignId('job_id')->nullable()->constrained('jobs')->onDelete('set null');
            }
            
            // Prestige System
            if (!Schema::hasColumn('user_stats', 'prestige_level')) {
                $table->integer('prestige_level')->default(0);
            }
            if (!Schema::hasColumn('user_stats', 'total_prestiges')) {
                $table->integer('total_prestiges')->default(0);
            }
            if (!Schema::hasColumn('user_stats', 'last_prestige_at')) {
                $table->timestamp('last_prestige_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_stats', function (Blueprint $table) {
            if (Schema::hasColumn('user_stats', 'job_id')) {
                try { $table->dropForeign(['job_id']); } catch (\Exception $e) {}
                $table->dropColumn('job_id');
            }
            if (Schema::hasColumn('user_stats', 'prestige_level')) {
                $table->dropColumn('prestige_level');
            }
            if (Schema::hasColumn('user_stats', 'total_prestiges')) {
                $table->dropColumn('total_prestiges');
            }
            if (Schema::hasColumn('user_stats', 'last_prestige_at')) {
                $table->dropColumn('last_prestige_at');
            }
        });
    }
};
