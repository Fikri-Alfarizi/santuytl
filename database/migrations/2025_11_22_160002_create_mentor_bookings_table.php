<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('mentor_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mentor_id');
            $table->unsignedBigInteger('user_id'); // who booked
            $table->timestamp('booked_at');
            $table->integer('rating')->nullable();
            $table->text('review')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('mentor_bookings');
    }
};