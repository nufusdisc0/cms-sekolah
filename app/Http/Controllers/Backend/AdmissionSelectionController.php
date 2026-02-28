<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AdmissionSelection;
use App\Models\AdmissionPhase;
use App\Models\SelectionResult;
use App\Services\AdmissionSelectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdmissionSelectionController extends Controller
{
    protected $selectionService;

    public function __construct(AdmissionSelectionService $selectionService)
    {
        $this->selectionService = $selectionService;
    }

    /**
     * Display all selections
     */
    public function index()
    {
        $selections = AdmissionSelection::with('admissionPhase')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('backend.admission.selections.index', compact('selections'));
    }

    /**
     * Show form to create new selection
     */
    public function create()
    {
        $phases = AdmissionPhase::where('is_active', true)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('backend.admission.selections.create', compact('phases'));
    }

    /**
     * Store new selection
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'admission_phase_id' => 'required|exists:admission_phases,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_quota' => 'required|integer|min:1',
            'ranking_method' => 'required|in:score_based,merit_list,round_robin,quota_based',
            'choice_method' => 'required|in:first_choice,best_match,alternative',
            'batch_size' => 'required|integer|min:10|max:1000',
        ]);

        $selection = AdmissionSelection::create([
            ...$validated,
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('backend.selection.show', $selection)
            ->with('success', 'Selection process created successfully');
    }

    /**
     * Show selection details
     */
    public function show(AdmissionSelection $selection)
    {
        $statistics = $this->selectionService->getStatistics($selection);
        $resultsByMajor = $this->selectionService->getResultsByMajor($selection);

        return view('backend.admission.selections.show', compact(
            'selection',
            'statistics',
            'resultsByMajor'
        ));
    }

    /**
     * Show edit form
     */
    public function edit(AdmissionSelection $selection)
    {
        if ($selection->status !== 'draft') {
            return redirect()->route('backend.selection.show', $selection)
                ->with('error', 'Can only edit selections in draft status');
        }

        $phases = AdmissionPhase::where('is_active', true)->get();

        return view('backend.admission.selections.edit', compact('selection', 'phases'));
    }

    /**
     * Update selection
     */
    public function update(Request $request, AdmissionSelection $selection)
    {
        if ($selection->status !== 'draft') {
            return redirect()->back()->with('error', 'Can only edit selections in draft status');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_quota' => 'required|integer|min:1',
            'ranking_method' => 'required|in:score_based,merit_list,round_robin,quota_based',
            'choice_method' => 'required|in:first_choice,best_match,alternative',
            'batch_size' => 'required|integer|min:10|max:1000',
        ]);

        $selection->update([
            ...$validated,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('backend.selection.show', $selection)
            ->with('success', 'Selection updated successfully');
    }

    /**
     * Execute selection process
     */
    public function executeSelection(AdmissionSelection $selection)
    {
        try {
            $result = $this->selectionService->executeSelection($selection);

            return redirect()->route('backend.selection.show', $selection)
                ->with('success', "Selection executed successfully. Processed: {$result['total_processed']}, Accepted: {$result['accepted']}, Rejected: {$result['rejected']}");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to execute selection: ' . $e->getMessage());
        }
    }

    /**
     * Announce results
     */
    public function announceResults(AdmissionSelection $selection)
    {
        try {
            if ($selection->status !== 'completed') {
                return redirect()->back()->with('error', 'Can only announce results for completed selections');
            }

            $this->selectionService->announceResults($selection);

            // Event: ResultsAnnounced (for email notifications in Phase 4)
            // event(new AdmissionResultsAnnounced($selection));

            return redirect()->route('backend.selection.show', $selection)
                ->with('success', 'Results announced successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to announce results: ' . $e->getMessage());
        }
    }

    /**
     * View selection results with filters
     */
    public function viewResults(AdmissionSelection $selection, Request $request)
    {
        $query = $selection->selectionResults()
            ->with('registrant');

        // Filter by result status
        if ($request->has('result') && $request->result !== '') {
            $query->where('result', $request->result);
        }

        // Filter by major
        if ($request->has('major') && $request->major !== '') {
            $query->where('allocated_major', $request->major);
        }

        // Sort
        $sort = $request->get('sort', 'rank');
        $direction = $request->get('direction', 'asc');

        if ($sort === 'score') {
            $query->orderBy('total_score', $direction);
        } else {
            $query->orderBy('rank', $direction);
        }

        $results = $query->paginate(50);

        return view('backend.admission.selections.results', compact('selection', 'results'));
    }

    /**
     * Export results to CSV
     */
    public function exportResults(AdmissionSelection $selection)
    {
        $data = $this->selectionService->exportResults($selection);

        $filename = "selection_results_{$selection->id}_" . now()->format('YmdHis') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM for UTF-8

            // Header
            fputcsv($file, [
                'No. Pendaftaran',
                'Nama',
                'Email',
                'Jurusan',
                'Nilai',
                'Ranking',
                'Hasil',
                'Tanggal Pengumuman',
            ]);

            // Data
            foreach ($data as $row) {
                fputcsv($file, [
                    $row['registration_number'],
                    $row['full_name'],
                    $row['email'],
                    $row['major'],
                    $row['score'],
                    $row['rank'],
                    $row['result'],
                    $row['announcement_date'],
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Rollback selection (admin only)
     */
    public function rollbackSelection(AdmissionSelection $selection)
    {
        try {
            $this->authorize('admin'); // Admin check

            $this->selectionService->rollbackSelection($selection);

            return redirect()->route('backend.selection.show', $selection)
                ->with('success', 'Selection rolled back successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to rollback: ' . $e->getMessage());
        }
    }

    /**
     * Delete selection
     */
    public function destroy(AdmissionSelection $selection)
    {
        if ($selection->status !== 'draft') {
            return redirect()->back()->with('error', 'Can only delete selections in draft status');
        }

        $selection->delete();

        return redirect()->route('backend.selection.index')
            ->with('success', 'Selection deleted successfully');
    }

    /**
     * Get registrant's result
     */
    public function getRegistrantResult(AdmissionSelection $selection, Request $request)
    {
        $request->validate([
            'registration_number' => 'required|string',
        ]);

        $registrant = \App\Models\Registrant::where('registration_number', $request->registration_number)
            ->first();

        if (!$registrant) {
            return response()->json(['error' => 'Registrant not found'], 404);
        }

        $result = $this->selectionService->getRegistrantResult($registrant, $selection);

        if (!$result) {
            return response()->json(['error' => 'No result found for this registrant'], 404);
        }

        return response()->json([
            'registrant' => [
                'full_name' => $result->registrant->full_name,
                'registration_number' => $result->registrant->registration_number,
            ],
            'result' => [
                'status' => $result->result,
                'rank' => $result->rank,
                'score' => $result->total_score,
                'major' => $result->allocated_major,
                'announcement_date' => $result->announced_at?->format('d/m/Y'),
            ],
        ]);
    }
}
