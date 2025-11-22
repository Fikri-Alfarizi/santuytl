<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('user_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('type'); // level_up, event_win, badge_get, join_server, etc
            $table->string('description');
            $table->timestamp('occurred_at');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('user_histories');
    }
};