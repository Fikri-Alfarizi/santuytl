<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('leaderboards', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // global, weekly, etc
            $table->unsignedBigInteger('user_id');
            $table->bigInteger('xp')->default(0);
            $table->bigInteger('coins')->default(0);
            $table->integer('team_id')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('leaderboards');
    }
};