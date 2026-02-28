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
        Schema::table('image_sliders', function (Blueprint $table) {
            $table->enum('is_active', ['true', 'false'])->default('true')->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('image_sliders', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
