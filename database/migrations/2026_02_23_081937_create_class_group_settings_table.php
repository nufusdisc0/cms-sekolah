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
        Schema::create('class_group_settings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('academic_year_id')->default(0)->comment('FK dari academic_years')->index();
            $table->bigInteger('class_group_id')->default(0)->comment('Kelas, FK dari class_groups')->index();
            $table->bigInteger('employee_id')->default(0)->comment('Wali Kelas, FK dari employees');

            $table->timestamps();
            $table->softDeletes();
            $table->datetime('restored_at')->nullable();

            $table->bigInteger('created_by')->default(0);
            $table->bigInteger('updated_by')->default(0);
            $table->bigInteger('deleted_by')->default(0);
            $table->bigInteger('restored_by')->default(0);
            $table->enum('is_deleted', ['true', 'false'])->default('false');

            $table->unique(['academic_year_id', 'class_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_group_settings');
    }
};
