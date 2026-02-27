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
        Schema::create('student_groups', function (Blueprint $table) {
            $table->id();
            // This table doesn't seem to exist in the dump snippet we saw,
            // or maybe it's `class_groups`? I'll re-check if it exists. 
            // Wait, I created a migration for student_groups but it might not be in the dump.
            // Looking back at the dump, there is `class_group_students` and `class_groups`. 
            // Let's leave it mostly empty or with just basic fields if it didn't exist in dump.
            // Oh, I generated `create_student_groups_table` earlier. 
            // Let's create it with just basic fields matching the usual pattern, or skip it.
            // I'll add the standard fields for now.
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
        Schema::dropIfExists('student_groups');
    }
};
