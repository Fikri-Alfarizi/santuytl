<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('mentors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique(); // mentor user
            $table->string('skill');
            $table->text('bio')->nullable();
            $table->integer('rating')->default(0);
            $table->integer('sessions')->default(0);
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('mentors');
    }
};