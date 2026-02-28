<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportStudentsRequest;
use App\Http\Requests\ImportEmployeesRequest;
use App\Models\ImportLog;
use App\Models\ImportError;
use App\Services\CSVImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BulkImportController extends Controller
{
    protected $importService;

    public function __construct(CSVImportService $importService)
    {
        $this->importService = $importService;
    }

    /**
     * Show student import form
     */
    public function showStudentImportForm()
    {
        return view('backend.import.students.form');
    }

    /**
     * Preview student import
     */
    public function previewStudentImport(ImportStudentsRequest $request)
    {
        $file = $request->file('file');
        $batchSize = $request->input('batch_size', 50);

        // Parse file
        $parseResult = $this->importService->parseFile($file, 'student');

        if (!$parseResult['success']) {
            return redirect()->back()
                ->with('error', $parseResult['message'])
                ->with('errors', $parseResult['errors']);
        }

        // Create import log
        $importLog = $this->importService->createImportLog(
            $file,
            'student',
            $parseResult['total_rows'],
            $parseResult['column_mapping'],
            auth()->id()
        );

        $importLog->update(['batch_size' => $batchSize]);

        return view('backend.import.students.preview', [
            'importLog' => $importLog,
            'data' => $parseResult['data'],
            'totalRows' => $parseResult['total_rows'],
            'columnMapping' => $parseResult['column_mapping'],
        ]);
    }

    /**
     * Process student import
     */
    public function processStudentImport(Request $request)
    {
        $request->validate([
            'import_log_id' => 'required|exists:import_logs,id',
        ]);

        $importLog = ImportLog::find($request->import_log_id);

        if ($importLog->status !== 'pending') {
            return redirect()->back()->with('error', 'Import sudah diproses');
        }

        // Get stored file and re-parse
        $file = Storage::disk('local')->get($importLog->file_path);
        $rows = array_map('str_getcsv', explode("\n", $file));

        // Get headers from first row
        $headers = array_map('strtolower', array_map('trim', $rows[0]));

        // Prepare data
        $data = [];
        for ($i = 1; $i < count($rows); $i++) {
            if (empty(array_filter($rows[$i]))) {
                continue;
            }

            $row = array_map('trim', $rows[$i]);
            $mappedData = [];

            foreach ($headers as $colIndex => $header) {
                if (isset($importLog->column_mapping[$header])) {
                    $fieldName = $importLog->column_mapping[$header];
                    $mappedData[$fieldName] = $row[$colIndex] ?? '';
                }
            }

            $data[] = [
                'row_number' => $i + 1,
                'data' => $mappedData,
            ];
        }

        // Process import
        $result = $this->importService->processImport($importLog, $data, 'student');

        return redirect()->route('backend.import.students.results', $importLog)
            ->with('success', "Import selesai: {$result['success']} berhasil, {$result['failed']} gagal");
    }

    /**
     * Show student import results
     */
    public function showStudentResults(ImportLog $importLog)
    {
        if ($importLog->import_type !== 'student') {
            abort(404);
        }

        $errors = $importLog->errors()->paginate(50);

        return view('backend.import.students.results', [
            'importLog' => $importLog,
            'errors' => $errors,
        ]);
    }

    /**
     * Download student error report
     */
    public function downloadStudentErrorReport(ImportLog $importLog)
    {
        if ($importLog->import_type !== 'student') {
            abort(404);
        }

        $errors = $this->importService->generateErrorReport($importLog);

        $filename = "error_report_student_{$importLog->id}_" . now()->format('YmdHis') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($errors, $importLog) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['Baris', 'Tipe Error', 'Pesan', 'Field yang Error']);

            foreach ($errors as $error) {
                fputcsv($file, [
                    $error['row'],
                    $error['type'],
                    $error['message'],
                    implode(',', $error['fields'] ?? []),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show employee import form
     */
    public function showEmployeeImportForm()
    {
        return view('backend.import.employees.form');
    }

    /**
     * Preview employee import
     */
    public function previewEmployeeImport(ImportEmployeesRequest $request)
    {
        $file = $request->file('file');
        $batchSize = $request->input('batch_size', 50);

        // Parse file
        $parseResult = $this->importService->parseFile($file, 'employee');

        if (!$parseResult['success']) {
            return redirect()->back()
                ->with('error', $parseResult['message'])
                ->with('errors', $parseResult['errors']);
        }

        // Create import log
        $importLog = $this->importService->createImportLog(
            $file,
            'employee',
            $parseResult['total_rows'],
            $parseResult['column_mapping'],
            auth()->id()
        );

        $importLog->update(['batch_size' => $batchSize]);

        return view('backend.import.employees.preview', [
            'importLog' => $importLog,
            'data' => $parseResult['data'],
            'totalRows' => $parseResult['total_rows'],
            'columnMapping' => $parseResult['column_mapping'],
        ]);
    }

    /**
     * Process employee import
     */
    public function processEmployeeImport(Request $request)
    {
        $request->validate([
            'import_log_id' => 'required|exists:import_logs,id',
        ]);

        $importLog = ImportLog::find($request->import_log_id);

        if ($importLog->status !== 'pending') {
            return redirect()->back()->with('error', 'Import sudah diproses');
        }

        // Get stored file and re-parse
        $file = Storage::disk('local')->get($importLog->file_path);
        $rows = array_map('str_getcsv', explode("\n", $file));

        // Get headers from first row
        $headers = array_map('strtolower', array_map('trim', $rows[0]));

        // Prepare data
        $data = [];
        for ($i = 1; $i < count($rows); $i++) {
            if (empty(array_filter($rows[$i]))) {
                continue;
            }

            $row = array_map('trim', $rows[$i]);
            $mappedData = [];

            foreach ($headers as $colIndex => $header) {
                if (isset($importLog->column_mapping[$header])) {
                    $fieldName = $importLog->column_mapping[$header];
                    $mappedData[$fieldName] = $row[$colIndex] ?? '';
                }
            }

            $data[] = [
                'row_number' => $i + 1,
                'data' => $mappedData,
            ];
        }

        // Process import
        $result = $this->importService->processImport($importLog, $data, 'employee');

        return redirect()->route('backend.import.employees.results', $importLog)
            ->with('success', "Import selesai: {$result['success']} berhasil, {$result['failed']} gagal");
    }

    /**
     * Show employee import results
     */
    public function showEmployeeResults(ImportLog $importLog)
    {
        if ($importLog->import_type !== 'employee') {
            abort(404);
        }

        $errors = $importLog->errors()->paginate(50);

        return view('backend.import.employees.results', [
            'importLog' => $importLog,
            'errors' => $errors,
        ]);
    }

    /**
     * Download employee error report
     */
    public function downloadEmployeeErrorReport(ImportLog $importLog)
    {
        if ($importLog->import_type !== 'employee') {
            abort(404);
        }

        $errors = $this->importService->generateErrorReport($importLog);

        $filename = "error_report_employee_{$importLog->id}_" . now()->format('YmdHis') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($errors, $importLog) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['Baris', 'Tipe Error', 'Pesan', 'Field yang Error']);

            foreach ($errors as $error) {
                fputcsv($file, [
                    $error['row'],
                    $error['type'],
                    $error['message'],
                    implode(',', $error['fields'] ?? []),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show all imports history
     */
    public function showImportHistory(Request $request)
    {
        $query = ImportLog::query();

        // Filter by type
        if ($request->type) {
            $query->where('import_type', $request->type);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $imports = $query->with('createdBy')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('backend.import.history', compact('imports'));
    }

    /**
     * Rollback import
     */
    public function rollbackImport(ImportLog $importLog)
    {
        if (!$importLog->canRollback()) {
            return redirect()->back()->with('error', 'Import tidak dapat dibatalkan');
        }

        $success = $this->importService->rollbackImport($importLog, auth()->id());

        if ($success) {
            return redirect()->back()->with('success', 'Import berhasil dibatalkan');
        } else {
            return redirect()->back()->with('error', 'Gagal membatalkan import');
        }
    }

    /**
     * Download student template
     */
    public function downloadStudentTemplate()
    {
        $filename = 'student_import_template.csv';
        $headers = [
            'full_name', 'nisn', 'nik', 'gender', 'birth_place', 'birth_date',
            'email', 'phone', 'street_address', 'village', 'district',
            'sub_district', 'postal_code', 'major_id'
        ];

        return response()->stream(function () use ($headers) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $headers);
            fclose($file);
        }, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Download employee template
     */
    public function downloadEmployeeTemplate()
    {
        $filename = 'employee_import_template.csv';
        $headers = [
            'full_name', 'nik', 'nip', 'gender', 'birth_place', 'birth_date',
            'email', 'phone', 'street_address', 'village', 'district',
            'sub_district', 'postal_code', 'employment_type_id'
        ];

        return response()->stream(function () use ($headers) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $headers);
            fclose($file);
        }, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
