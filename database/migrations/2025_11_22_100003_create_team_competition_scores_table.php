<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('team_competition_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_competition_id');
            $table->unsignedBigInteger('team_id');
            $table->bigInteger('score')->default(0);
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('team_competition_scores');
    }
};