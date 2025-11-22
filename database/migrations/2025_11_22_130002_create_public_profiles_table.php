<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('public_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('avatar')->nullable();
            $table->string('bio')->nullable();
            $table->string('share_link')->unique();
            $table->integer('reputation')->default(0);
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('public_profiles');
    }
};