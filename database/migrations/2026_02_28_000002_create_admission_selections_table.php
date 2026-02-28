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
        Schema::create('admission_selections', function (Blueprint $table) {
            $table->id();

            // Related to admission phase
            $table->foreignId('admission_phase_id')
                ->constrained()
                ->cascadeOnDelete();

            // Selection process information
            $table->string('name')->comment('e.g., Selection Round 1, Final Selection');
            $table->text('description')->nullable();

            // Selection configuration
            $table->json('selection_criteria')->nullable()->comment('JSON: scoring weights, cutoff scores');
            $table->integer('batch_size')->default(50)->comment('Number of registrants to process at once');

            // Status and dates
            $table->enum('status', [
                'draft',           // Not yet started
                'in_progress',     // Running selection
                'completed',       // Selection finished
                'announced',       // Results announced
                'canceled'         // Canceled
            ])->default('draft');

            $table->timestamp('selection_started_at')->nullable();
            $table->timestamp('selection_completed_at')->nullable();
            $table->timestamp('results_announced_at')->nullable();

            // Result configuration
            $table->integer('total_quota')->comment('Total seats available');
            $table->integer('accepted_count')->default(0)->comment('Number accepted');
            $table->integer('rejected_count')->default(0)->comment('Number rejected');
            $table->integer('processed_count')->default(0)->comment('Number processed');

            // Ranking/Scoring method
            $table->enum('ranking_method', [
                'score_based',      // Based on total score
                'merit_list',       // Merit-based ranking
                'round_robin',      // Sequential allocation
                'quota_based'       // Quota per major
            ])->default('quota_based');

            // Allow multiple majors handling
            $table->boolean('allow_multiple_choices')->default(true);
            $table->enum('choice_method', [
                'first_choice',     // Only first choice
                'best_match',       // Best of all choices
                'alternative'       // Fill alternatives if first full
            ])->default('first_choice');

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
            $table->index('admission_phase_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_selections');
    }
};
