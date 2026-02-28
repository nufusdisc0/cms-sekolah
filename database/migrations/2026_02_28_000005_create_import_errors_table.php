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
        Schema::create('import_errors', function (Blueprint $table) {
            $table->id();

            // Reference to import log
            $table->unsignedBigInteger('import_log_id');
            $table->foreign('import_log_id')
                ->references('id')
                ->on('import_logs')
                ->onDelete('cascade');

            // Error Details
            $table->unsignedInteger('row_number');
            $table->string('error_type'); // e.g., 'validation', 'duplicate', 'null_required_field'
            $table->string('error_code')->nullable();
            $table->text('error_message');

            // Problem Data
            $table->json('row_data'); // The actual CSV row that failed
            $table->json('failed_fields')->nullable(); // Which fields failed validation
            $table->json('validation_errors')->nullable(); // Detailed validation error messages

            // Resolution
            $table->boolean('is_resolved')->default(false);
            $table->text('resolution_notes')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('import_log_id');
            $table->index('error_type');
            $table->index('row_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_errors');
    }
};
