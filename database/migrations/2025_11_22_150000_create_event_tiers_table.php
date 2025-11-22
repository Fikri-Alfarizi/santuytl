<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('event_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // daily, weekly, monthly
            $table->string('reward_type'); // xp, coin, badge, frame
            $table->integer('reward_amount')->default(0);
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('event_tiers');
    }
};