<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('team_competitions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('team_competitions');
    }
};