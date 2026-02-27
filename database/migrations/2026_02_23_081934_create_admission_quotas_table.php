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
        Schema::create('admission_quotas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('academic_year_id')->default(0)->comment('Tahun Pelajaran')->index();
            $table->bigInteger('admission_type_id')->default(0)->comment('Jalur Pendaftaran')->index();
            $table->bigInteger('major_id')->default(0)->comment('Program Keahlian')->index();
            $table->smallInteger('quota')->default(0)->comment('Kuota Pendaftaran');

            $table->timestamps();
            $table->softDeletes();
            $table->datetime('restored_at')->nullable();

            $table->bigInteger('created_by')->default(0);
            $table->bigInteger('updated_by')->default(0);
            $table->bigInteger('deleted_by')->default(0);
            $table->bigInteger('restored_by')->default(0);
            $table->enum('is_deleted', ['true', 'false'])->default('false');

            $table->unique(['academic_year_id', 'admission_type_id', 'major_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_quotas');
    }
};
