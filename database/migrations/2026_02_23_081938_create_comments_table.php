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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('comment_post_id')->default(0)->index();
            $table->string('comment_author', 255);
            $table->string('comment_email', 255)->nullable();
            $table->string('comment_url', 255)->nullable();
            $table->string('comment_ip_address', 255);
            $table->text('comment_content');
            $table->string('comment_subject', 255)->nullable();
            $table->text('comment_reply')->nullable();
            $table->enum('comment_status', ['approved', 'unapproved', 'spam'])->default('approved');
            $table->string('comment_agent', 255)->nullable();
            $table->string('comment_parent_id', 255)->nullable();
            $table->enum('comment_type', ['post', 'message'])->default('post');

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
        Schema::dropIfExists('comments');
    }
};
