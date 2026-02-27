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
        Schema::create('class_group_students', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('class_group_setting_id')->default(0)->index();
            $table->bigInteger('student_id')->default(0)->index();
            $table->enum('is_class_manager', ['true', 'false'])->default('false')->comment('Ketua Kelas ?');

            $table->timestamps();
            $table->softDeletes();
            $table->datetime('restored_at')->nullable();

            $table->bigInteger('created_by')->default(0);
            $table->bigInteger('updated_by')->default(0);
            $table->bigInteger('deleted_by')->default(0);
            $table->bigInteger('restored_by')->default(0);
            $table->enum('is_deleted', ['true', 'false'])->default('false');

            $table->unique(['class_group_setting_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_group_students');
    }
};
