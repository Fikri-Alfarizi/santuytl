<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            // Post Metadata
            $table->string('featured_image')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false);
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            // Author
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            // Categories and Tags
            $table->json('categories')->nullable();
            $table->json('tags')->nullable();
            // Publishing Schedule
            $table->timestamp('published_at')->nullable();
            // Discord Integration
            $table->boolean('posted_to_discord')->default(false);
            $table->string('discord_message_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_posts');
    }
};
