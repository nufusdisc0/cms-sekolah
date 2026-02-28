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
        Schema::create('import_logs', function (Blueprint $table) {
            $table->id();

            // Import Details
            $table->enum('import_type', ['student', 'employee'])->index();
            $table->string('filename');
            $table->unsignedInteger('total_rows');
            $table->unsignedInteger('successful_rows')->default(0);
            $table->unsignedInteger('failed_rows')->default(0);
            $table->unsignedInteger('duplicate_rows')->default(0);

            // Status Tracking
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'rolled_back'])->default('pending');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // File Storage
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->unsignedInteger('file_size');

            // Batch Processing
            $table->unsignedInteger('batch_size')->default(50);
            $table->unsignedInteger('batches_processed')->default(0);
            $table->unsignedInteger('total_batches')->default(0);

            // Mapping
            $table->json('column_mapping')->nullable(); // Maps CSV columns to model fields
            $table->json('validation_rules')->nullable(); // Rules used for validation

            // Audit Trail
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('rolled_back_by')->nullable();

            // Metadata
            $table->text('notes')->nullable();
            $table->json('summary')->nullable(); // Statistics summary

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['import_type', 'status']);
            $table->index(['created_at']);
            $table->index(['created_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_logs');
    }
};
