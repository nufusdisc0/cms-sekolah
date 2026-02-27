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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('menu_title', 150);
            $table->string('menu_url', 150);
            $table->enum('menu_target', ['_blank', '_self', '_parent', '_top'])->default('_self');
            $table->string('menu_type', 100)->default('pages');
            $table->bigInteger('menu_parent_id')->default(0);
            $table->bigInteger('menu_position')->default(0);

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
        Schema::dropIfExists('menus');
    }
};
