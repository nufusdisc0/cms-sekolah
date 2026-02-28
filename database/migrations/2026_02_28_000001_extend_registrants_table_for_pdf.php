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
        Schema::table('registrants', function (Blueprint $table) {
            // Personal Information
            $table->string('full_name')->nullable();
            $table->string('nisn')->unique()->nullable(); // NISN = ID Pelajar
            $table->string('nik')->unique()->nullable();  // NIK = ID nasional
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();

            // Contact Information
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('district')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();

            // Parent Information
            $table->string('parent_name')->nullable();
            $table->string('parent_email')->nullable();
            $table->string('parent_phone')->nullable();
            $table->text('parent_address')->nullable();

            // Academic Information
            $table->foreignId('admission_phase_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('admission_quota_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('admission_type')->nullable();
            $table->string('major')->nullable();

            // Previous Education
            $table->string('previous_school')->nullable();
            $table->float('previous_gpa')->nullable();
            $table->year('graduation_year')->nullable();

            // Application Status
            $table->enum('application_status', [
                'draft',           // Saved but incomplete
                'submitted',       // Submitted by applicant
                'under_review',    // Under processing
                'passed',          // Passed selection
                'failed',          // Failed selection
                'confirmed',       // Confirmed acceptance
                'enrolled'         // Enrolled as student
            ])->default('draft');

            // Selection Results
            $table->enum('selection_status', [
                'pending',     // Awaiting selection
                'passed',      // Passed selection
                'failed'       // Failed selection
            ])->nullable();
            $table->timestamp('selection_date')->nullable();

            // Registration & Document Info
            $table->string('registration_number')->unique()->nullable(); // Format: YEAR-RUNNING_NUMBER
            $table->timestamp('registration_date')->nullable();
            $table->string('registration_token')->unique()->nullable(); // For email verification
            $table->boolean('email_verified')->default(false);
            $table->timestamp('email_verified_at')->nullable();

            // PDF & Documents
            $table->string('photo_path')->nullable();
            $table->json('documents')->nullable(); // JSON array of uploaded document paths
            $table->timestamp('pdf_generated_at')->nullable();
            $table->string('pdf_path')->nullable();

            // Acceptance Info
            $table->string('acceptance_letter_number')->nullable();
            $table->date('acceptance_letter_date')->nullable();
            $table->timestamp('accepted_at')->nullable();

            // Audit Trail
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('restored_at')->nullable();
            $table->foreignId('restored_by')->nullable()->constrained('users')->cascadeOnDelete();

            // Indexes for performance
            $table->index('admission_phase_id');
            $table->index('application_status');
            $table->index('selection_status');
            $table->index('registration_number');
            $table->index('email');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrants', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['admission_phase_id']);
            $table->dropForeign(['admission_quota_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['deleted_by']);
            $table->dropForeign(['restored_by']);

            // Drop all columns
            $table->dropColumn([
                'full_name', 'nisn', 'nik', 'gender', 'birth_place', 'birth_date',
                'email', 'phone', 'address', 'district', 'city', 'province', 'postal_code',
                'parent_name', 'parent_email', 'parent_phone', 'parent_address',
                'admission_phase_id', 'admission_quota_id', 'admission_type', 'major',
                'previous_school', 'previous_gpa', 'graduation_year',
                'application_status', 'selection_status', 'selection_date',
                'registration_number', 'registration_date', 'registration_token',
                'email_verified', 'email_verified_at',
                'photo_path', 'documents', 'pdf_generated_at', 'pdf_path',
                'acceptance_letter_number', 'acceptance_letter_date', 'accepted_at',
                'created_by', 'updated_by', 'deleted_by', 'deleted_at', 'restored_at', 'restored_by'
            ]);
        });
    }
};
