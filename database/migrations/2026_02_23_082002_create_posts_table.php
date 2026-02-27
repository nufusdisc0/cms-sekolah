<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('post_title', 255)->nullable();
            $table->longText('post_content')->nullable();
            $table->string('post_image', 100)->nullable();
            $table->bigInteger('post_author')->default(0)->index();
            $table->string('post_categories', 255)->nullable();
            $table->string('post_type', 50)->default('post');
            $table->enum('post_status', ['publish', 'draft'])->default('draft');
            $table->enum('post_visibility', ['public', 'private'])->default('public');
            $table->enum('post_comment_status', ['open', 'close'])->default('close');
            $table->string('post_slug', 255)->nullable();
            $table->string('post_tags', 255)->nullable();
            $table->bigInteger('post_counter')->default(0);

            $table->timestamps();
            $table->softDeletes();
            $table->datetime('restored_at')->nullable();

            $table->bigInteger('created_by')->default(0);
            $table->bigInteger('updated_by')->default(0);
            $table->bigInteger('deleted_by')->default(0);
            $table->bigInteger('restored_by')->default(0);
            $table->enum('is_deleted', ['true', 'false'])->default('false');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
