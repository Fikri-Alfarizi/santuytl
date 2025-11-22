<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('tutorials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // author
            $table->string('title');
            $table->text('content');
            $table->string('category')->nullable();
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('tutorials');
    }
};