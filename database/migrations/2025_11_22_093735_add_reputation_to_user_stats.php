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
            $table->integer('reputation')->default(0)->after('coins');
        });
    }

    public function down(): void
    {
        Schema::table('user_stats', function (Blueprint $table) {
            $table->dropColumn('reputation');
        });
    }
};
