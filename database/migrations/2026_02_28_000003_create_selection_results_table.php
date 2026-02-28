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
        Schema::create('selection_results', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('admission_selection_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('registrant_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('admission_quota_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Scoring information
            $table->float('total_score', 8, 2)->nullable()->comment('Total score from selection');
            $table->float('test_score', 8, 2)->nullable();
            $table->float('academic_score', 8, 2)->nullable();
            $table->float('interview_score', 8, 2)->nullable();
            $table->float('extra_curricular_score', 8, 2)->nullable();
            $table->float('other_score', 8, 2)->nullable();

            // Ranking in selection
            $table->integer('rank')->nullable()->comment('Ranking position in this selection');
            $table->json('ranking_details')->nullable()->comment('Details of ranking calculation');

            // Selection result
            $table->enum('result', [
                'pending',      // Not yet processed
                'passed',       // Accepted/Passed selection
                'failed',       // Rejected/Failed selection
                'waitlisted',   // On waitlist
                'withdrawn'     // Withdrew by applicant
            ])->default('pending');

            // Quota allocation
            $table->integer('choice_priority')->nullable()->comment('Which choice (1st, 2nd, etc)');
            $table->string('allocated_major')->nullable()->comment('Major allocated to');

            // Dates
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('announced_at')->nullable();

            // Notes and comments
            $table->text('remarks')->nullable()->comment('Admin notes/comments');

            // Acceptance tracking
            $table->boolean('acceptance_confirmed')->default(false);
            $table->timestamp('acceptance_confirmed_at')->nullable();
            $table->timestamp('acceptance_expired_at')->nullable();

            // Audit trail
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();
            $table->timestamp('deleted_at')->nullable();

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index('admission_selection_id');
            $table->index('registrant_id');
            $table->index('result');
            $table->index('rank');
            $table->index('total_score');
            $table->unique(['admission_selection_id', 'registrant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selection_results');
    }
};
