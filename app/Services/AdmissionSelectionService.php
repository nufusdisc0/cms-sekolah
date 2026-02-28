<?php

namespace App\Services;

use App\Models\AdmissionSelection;
use App\Models\AdmissionQuota;
use App\Models\SelectionResult;
use App\Models\Registrant;
use Illuminate\Support\Collection;

class AdmissionSelectionService
{
    /**
     * Execute selection process for a given selection
     */
    public function executeSelection(AdmissionSelection $selection): array
    {
        if (!$selection->canStartSelection()) {
            throw new \Exception('Selection cannot be started for this phase');
        }

        $selection->startSelection();

        // Get all submitted registrants for this phase
        $registrants = $selection->admissionPhase
            ->registrants()
            ->submitted()
            ->get();

        $results = [];

        // Get quotas for this phase
        $quotas = $selection->admissionPhase->quotas;

        match ($selection->ranking_method) {
            'quota_based' => $results = $this->processQuotaBased($selection, $registrants, $quotas),
            'score_based' => $results = $this->processScoreBased($selection, $registrants),
            'merit_list' => $results = $this->processMeritList($selection, $registrants),
            default => $results = $this->processQuotaBased($selection, $registrants, $quotas),
        };

        $selection->update([
            'processed_count' => count($results),
        ]);

        $selection->completeSelection();

        return [
            'total_processed' => count($results),
            'accepted' => $selection->accepted_count,
            'rejected' => $selection->rejected_count,
            'results' => $results,
        ];
    }

    /**
     * Process quota-based selection (allocate seats per major)
     */
    private function processQuotaBased(
        AdmissionSelection $selection,
        Collection $registrants,
        Collection $quotas
    ): array {
        $results = [];
        $acceptedCount = 0;
        $rejectedCount = 0;

        // Build quota allocation map
        $quotaAllocation = [];
        foreach ($quotas as $quota) {
            $quotaAllocation[$quota->major_id] = [
                'quota' => $quota->quota,
                'accepted' => 0,
            ];
        }

        // Get sorted registrants by choice
        if ($selection->choice_method === 'first_choice') {
            $registrants = $registrants->sortBy('major');
        }

        // Process each registrant
        foreach ($registrants as $registrant) {
            $major = $registrant->major;
            $selected = false;

            // Try first choice
            if (isset($quotaAllocation[$major])) {
                if ($quotaAllocation[$major]['accepted'] < $quotaAllocation[$major]['quota']) {
                    $result = $this->createSelectionResult(
                        $selection,
                        $registrant,
                        'passed',
                        1,
                        $major
                    );
                    $results[] = $result;
                    $quotaAllocation[$major]['accepted']++;
                    $acceptedCount++;
                    $selected = true;
                }
            }

            // If not selected, mark as failed
            if (!$selected) {
                $result = $this->createSelectionResult(
                    $selection,
                    $registrant,
                    'failed',
                    null,
                    null
                );
                $results[] = $result;
                $rejectedCount++;
            }
        }

        $selection->update([
            'accepted_count' => $acceptedCount,
            'rejected_count' => $rejectedCount,
        ]);

        return $results;
    }

    /**
     * Process score-based selection (rank by total score)
     */
    private function processScoreBased(
        AdmissionSelection $selection,
        Collection $registrants
    ): array {
        $results = [];
        $totalQuota = $selection->total_quota;
        $rank = 1;

        // Sort registrants by calculated score (descending)
        $sorted = $registrants
            ->map(function (Registrant $registrant) {
                return [
                    'registrant' => $registrant,
                    'score' => $this->calculateRegistrantScore($registrant),
                ];
            })
            ->sortByDesc('score')
            ->values();

        $acceptedCount = 0;

        foreach ($sorted as $item) {
            $registrant = $item['registrant'];
            $score = $item['score'];

            $result = $acceptedCount < $totalQuota ? 'passed' : 'failed';

            $this->createSelectionResult(
                $selection,
                $registrant,
                $result,
                $rank,
                $registrant->major,
                $score
            );

            if ($result === 'passed') {
                $acceptedCount++;
            } else {
                $results[] = [
                    'registrant_id' => $registrant->id,
                    'score' => $score,
                    'result' => $result,
                    'rank' => $rank,
                ];
            }

            $rank++;
        }

        $selection->update([
            'accepted_count' => $acceptedCount,
            'rejected_count' => count($sorted) - $acceptedCount,
        ]);

        return $results;
    }

    /**
     * Process merit list selection
     */
    private function processMeritList(
        AdmissionSelection $selection,
        Collection $registrants
    ): array {
        // Similar to score-based but with additional criteria
        return $this->processScoreBased($selection, $registrants);
    }

    /**
     * Create a selection result record
     */
    private function createSelectionResult(
        AdmissionSelection $selection,
        Registrant $registrant,
        string $result,
        ?int $rank = null,
        ?string $allocatedMajor = null,
        ?float $totalScore = null
    ): array {
        $selectionResult = SelectionResult::create([
            'admission_selection_id' => $selection->id,
            'registrant_id' => $registrant->id,
            'result' => $result,
            'rank' => $rank,
            'allocated_major' => $allocatedMajor,
            'total_score' => $totalScore,
            'choice_priority' => 1,
            'processed_at' => now(),
            'created_by' => auth()->id(),
        ]);

        return $selectionResult->toArray();
    }

    /**
     * Calculate score for a registrant (can be customized)
     */
    private function calculateRegistrantScore(Registrant $registrant): float
    {
        // Simple scoring: can be enhanced based on actual data
        $base_score = 80;

        // Add GPA points if available
        if ($registrant->previous_gpa) {
            $base_score += ($registrant->previous_gpa / 4) * 20;
        }

        return round($base_score, 2);
    }

    /**
     * Announce selection results
     */
    public function announceResults(AdmissionSelection $selection): bool
    {
        if (!$selection->canAnnounceResults()) {
            throw new \Exception('Cannot announce results for this selection');
        }

        $selection->announceResults();

        return true;
    }

    /**
     * Get selection statistics
     */
    public function getStatistics(AdmissionSelection $selection): array
    {
        $results = $selection->selectionResults;

        return [
            'total_processed' => $results->count(),
            'passed' => $results->where('result', 'passed')->count(),
            'failed' => $results->where('result', 'failed')->count(),
            'waitlisted' => $results->where('result', 'waitlisted')->count(),
            'pending' => $results->where('result', 'pending')->count(),
            'acceptance_confirmed' => $results->where('acceptance_confirmed', true)->count(),
            'average_score' => round($results->avg('total_score'), 2),
            'highest_score' => $results->max('total_score'),
            'lowest_score' => $results->min('total_score'),
            'quota_coverage' => $selection->quota_coverage,
            'progress' => $selection->progress_percentage,
        ];
    }

    /**
     * Get results by major
     */
    public function getResultsByMajor(AdmissionSelection $selection): array
    {
        $results = $selection->selectionResults;

        return $results->groupBy('allocated_major')
            ->map(function ($majorResults) {
                return [
                    'total' => $majorResults->count(),
                    'passed' => $majorResults->where('result', 'passed')->count(),
                    'failed' => $majorResults->where('result', 'failed')->count(),
                    'average_score' => round($majorResults->avg('total_score'), 2),
                ];
            })
            ->toArray();
    }

    /**
     * Export results to array
     */
    public function exportResults(AdmissionSelection $selection): array
    {
        return $selection->selectionResults
            ->map(function (SelectionResult $result) {
                return [
                    'registration_number' => $result->registrant->registration_number,
                    'full_name' => $result->registrant->full_name,
                    'email' => $result->registrant->email,
                    'major' => $result->allocated_major,
                    'score' => $result->total_score,
                    'rank' => $result->rank,
                    'result' => $result->result,
                    'announcement_date' => $result->announced_at?->format('Y-m-d'),
                ];
            })
            ->toArray();
    }

    /**
     * Get registrant's selection result
     */
    public function getRegistrantResult(Registrant $registrant, AdmissionSelection $selection): ?SelectionResult
    {
        return SelectionResult::where('registrant_id', $registrant->id)
            ->where('admission_selection_id', $selection->id)
            ->first();
    }

    /**
     * Rollback selection (for admin use)
     */
    public function rollbackSelection(AdmissionSelection $selection): bool
    {
        if (!$selection->active()) {
            throw new \Exception('Cannot rollback this selection');
        }

        // Delete all results
        $selection->selectionResults()->forceDelete();

        // Reset counters
        $selection->update([
            'status' => 'draft',
            'processed_count' => 0,
            'accepted_count' => 0,
            'rejected_count' => 0,
            'selection_started_at' => null,
            'selection_completed_at' => null,
        ]);

        return true;
    }
}
