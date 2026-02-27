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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('file_title', 255)->nullable();
            $table->string('file_description', 255)->nullable();
            $table->string('file_name', 255)->nullable();
            $table->string('file_type', 255)->nullable();
            $table->bigInteger('file_category_id')->default(0)->index();
            $table->string('file_path', 255)->nullable();
            $table->string('file_ext', 255)->nullable();
            $table->string('file_size', 255)->nullable();
            $table->enum('file_visibility', ['public', 'private'])->default('public');
            $table->bigInteger('file_counter')->default(0);

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
        Schema::dropIfExists('files');
    }
};
