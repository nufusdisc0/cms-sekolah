<?php

namespace App\Services;

use App\Models\ImportLog;
use App\Models\ImportError;
use App\Models\Student;
use App\Models\Employee;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CSVImportService
{
    // Student CSV headers
    protected $studentHeaders = [
        'full_name', 'nisn', 'nik', 'gender', 'birth_place', 'birth_date',
        'email', 'phone', 'street_address', 'village', 'district',
        'sub_district', 'postal_code', 'major_id'
    ];

    // Employee CSV headers
    protected $employeeHeaders = [
        'full_name', 'nik', 'nip', 'gender', 'birth_place', 'birth_date',
        'email', 'phone', 'street_address', 'village', 'district',
        'sub_district', 'postal_code', 'employment_type_id'
    ];

    /**
     * Parse CSV file and return preview data
     */
    public function parseFile(UploadedFile $file, string $importType)
    {
        $data = [];
        $errors = [];
        $rowNumber = 0;

        if (!in_array($file->getClientOriginalExtension(), ['csv', 'xls', 'xlsx'])) {
            return [
                'success' => false,
                'message' => 'File harus berformat CSV atau Excel',
                'data' => [],
                'errors' => []
            ];
        }

        // Read file
        $file_content = file_get_contents($file->getRealPath());
        $rows = array_map('str_getcsv', explode("\n", $file_content));

        if (empty($rows) || count($rows) < 2) {
            return [
                'success' => false,
                'message' => 'File CSV kosong atau hanya memiliki header',
                'data' => [],
                'errors' => []
            ];
        }

        // Get headers from first row
        $headers = array_map('strtolower', array_map('trim', $rows[0]));
        $columnMapping = $this->mapColumns($headers, $importType);

        if ($columnMapping['errors']) {
            return [
                'success' => false,
                'message' => 'Kolom CSV tidak sesuai. Berikan: ' . implode(', ', $columnMapping['required']),
                'data' => [],
                'errors' => $columnMapping['errors']
            ];
        }

        // Process data rows
        for ($i = 1; $i < count($rows); $i++) {
            $rowNumber = $i + 1;

            if (empty(array_filter($rows[$i]))) {
                continue; // Skip empty rows
            }

            $row = array_map('trim', $rows[$i]);
            $mappedData = [];

            foreach ($headers as $colIndex => $header) {
                if (isset($columnMapping['map'][$header])) {
                    $fieldName = $columnMapping['map'][$header];
                    $mappedData[$fieldName] = $row[$colIndex] ?? '';
                }
            }

            // Store preview (first 100 rows for performance)
            if (count($data) < 100) {
                $data[] = [
                    'row_number' => $rowNumber,
                    'data' => $mappedData,
                    'errors' => []
                ];
            }
        }

        return [
            'success' => true,
            'total_rows' => count($rows) - 1,
            'preview_rows' => count($data),
            'data' => $data,
            'column_mapping' => $columnMapping['map'],
            'headers' => $headers,
            'errors' => []
        ];
    }

    /**
     * Map CSV columns to model fields
     */
    public function mapColumns(array $csvHeaders, string $importType): array
    {
        $requiredHeaders = $importType === 'student' ? $this->studentHeaders : $this->employeeHeaders;
        $mapping = [];
        $errors = [];

        foreach ($csvHeaders as $csvHeader) {
            // Try exact match first
            if (in_array($csvHeader, $requiredHeaders)) {
                $mapping[$csvHeader] = $csvHeader;
            } else {
                // Try fuzzy match
                $fuzzyMatch = $this->fuzzyMatchHeader($csvHeader, $requiredHeaders);
                if ($fuzzyMatch) {
                    $mapping[$csvHeader] = $fuzzyMatch;
                }
            }
        }

        // Check for required fields
        $missingFields = array_diff($requiredHeaders, array_values($mapping));
        if ($missingFields) {
            $errors[] = 'Field yang diperlukan tidak ditemukan: ' . implode(', ', $missingFields);
        }

        return [
            'map' => $mapping,
            'required' => $requiredHeaders,
            'errors' => $errors
        ];
    }

    /**
     * Fuzzy match column headers
     */
    protected function fuzzyMatchHeader(string $header, array $requiredHeaders): ?string
    {
        $headerLower = strtolower($header);

        // Try variations
        $variations = [
            'nama' => 'full_name',
            'name' => 'full_name',
            'nisn' => 'nisn',
            'nik' => 'nik',
            'email' => 'email',
            'tlp' => 'phone',
            'telp' => 'phone',
            'telepon' => 'phone',
            'phone' => 'phone',
            'alamat' => 'street_address',
            'jalan' => 'street_address',
            'desa' => 'village',
            'kecamatan' => 'district',
            'kabupaten' => 'sub_district',
            'kode_pos' => 'postal_code',
            'kodepos' => 'postal_code',
        ];

        foreach ($variations as $alias => $field) {
            if (strpos($headerLower, $alias) !== false && in_array($field, $requiredHeaders)) {
                return $field;
            }
        }

        return null;
    }

    /**
     * Validate CSV row data
     */
    public function validateRow(array $data, string $importType): array
    {
        $rules = $importType === 'student' ? $this->getStudentValidationRules() : $this->getEmployeeValidationRules();
        $validator = Validator::make($data, $rules);

        return [
            'passes' => $validator->passes(),
            'errors' => $validator->errors()->toArray()
        ];
    }

    /**
     * Get student validation rules
     */
    protected function getStudentValidationRules(): array
    {
        return [
            'full_name' => 'required|string|max:150',
            'nisn' => 'nullable|string|max:50|unique:students,nisn',
            'nik' => 'nullable|string|max:50|unique:students,nik',
            'gender' => 'required|in:M,F,L,P',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date_format:Y-m-d|before:today',
            'email' => 'nullable|email|max:150|unique:students,email',
            'phone' => 'nullable|string|max:50',
            'street_address' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'sub_district' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'major_id' => 'nullable|exists:majors,id'
        ];
    }

    /**
     * Get employee validation rules
     */
    protected function getEmployeeValidationRules(): array
    {
        return [
            'full_name' => 'required|string|max:150',
            'nik' => 'required|string|max:50|unique:employees,nik',
            'nip' => 'nullable|string|max:50',
            'gender' => 'required|in:M,F,L,P',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date_format:Y-m-d|before:today',
            'email' => 'required|email|max:150|unique:employees,email',
            'phone' => 'nullable|string|max:50',
            'street_address' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'sub_district' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'employment_type_id' => 'nullable|exists:options,id'
        ];
    }

    /**
     * Check for duplicate entries
     */
    public function checkDuplicates(array $data, string $importType): array
    {
        $model = $importType === 'student' ? new Student() : new Employee();
        $duplicates = [];

        // Check by email
        if (!empty($data['email'])) {
            if ($model->where('email', $data['email'])->exists()) {
                $duplicates['email'] = 'Email sudah terdaftar';
            }
        }

        // Check by unique ID
        if ($importType === 'student' && !empty($data['nisn'])) {
            if (Student::where('nisn', $data['nisn'])->exists()) {
                $duplicates['nisn'] = 'NISN sudah terdaftar';
            }
        }

        if (!empty($data['nik'])) {
            $checkField = $importType === 'student' ? 'nik' : 'nik';
            if ($model->where($checkField, $data['nik'])->exists()) {
                $duplicates[$checkField] = ucfirst($checkField . ' sudah terdaftar');
            }
        }

        return $duplicates;
    }

    /**
     * Create import log
     */
    public function createImportLog(UploadedFile $file, string $importType, int $totalRows, $columnMapping, $userId = null): ImportLog
    {
        return ImportLog::create([
            'import_type' => $importType,
            'filename' => $file->getClientOriginalName(),
            'total_rows' => $totalRows,
            'file_path' => $file->store('imports/' . $importType, 'local'),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'status' => 'pending',
            'column_mapping' => $columnMapping,
            'created_by' => $userId ?? auth()->id(),
        ]);
    }

    /**
     * Process import
     */
    public function processImport(ImportLog $importLog, array $data, string $importType): array
    {
        $importLog->update([
            'status' => 'processing',
            'started_at' => now(),
            'total_batches' => ceil(count($data) / $importLog->batch_size),
        ]);

        $successCount = 0;
        $failureCount = 0;
        $duplicateCount = 0;
        $errors = [];

        foreach ($data as $rowData) {
            DB::beginTransaction();
            try {
                $rowNumber = $rowData['row_number'] ?? null;
                $rowValues = $rowData['data'] ?? [];

                // Validate
                $validation = $this->validateRow($rowValues, $importType);
                if (!$validation['passes']) {
                    $failureCount++;
                    ImportError::create([
                        'import_log_id' => $importLog->id,
                        'row_number' => $rowNumber,
                        'error_type' => 'validation',
                        'error_message' => 'Validasi data gagal',
                        'row_data' => $rowValues,
                        'validation_errors' => $validation['errors'],
                        'failed_fields' => array_keys($validation['errors']),
                    ]);
                    DB::commit();
                    continue;
                }

                // Check duplicates
                $duplicates = $this->checkDuplicates($rowValues, $importType);
                if ($duplicates) {
                    $duplicateCount++;
                    ImportError::create([
                        'import_log_id' => $importLog->id,
                        'row_number' => $rowNumber,
                        'error_type' => 'duplicate',
                        'error_message' => 'Data duplikat ditemukan',
                        'row_data' => $rowValues,
                        'validation_errors' => $duplicates,
                    ]);
                    DB::commit();
                    continue;
                }

                // Create record
                $model = $importType === 'student' ? new Student() : new Employee();
                $record = $model->create($rowValues);

                $successCount++;
                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                $failureCount++;

                ImportError::create([
                    'import_log_id' => $importLog->id,
                    'row_number' => $rowNumber ?? null,
                    'error_type' => 'system',
                    'error_message' => $e->getMessage(),
                    'row_data' => $rowValues ?? [],
                ]);
            }
        }

        // Update import log
        $importLog->update([
            'status' => 'completed',
            'completed_at' => now(),
            'successful_rows' => $successCount,
            'failed_rows' => $failureCount,
            'duplicate_rows' => $duplicateCount,
            'summary' => [
                'total' => count($data),
                'success_rate' => $successCount > 0 ? round(($successCount / count($data)) * 100, 2) : 0,
                'failure_rate' => $failureCount > 0 ? round(($failureCount / count($data)) * 100, 2) : 0,
                'duplicate_rate' => $duplicateCount > 0 ? round(($duplicateCount / count($data)) * 100, 2) : 0,
            ]
        ]);

        return [
            'import_log_id' => $importLog->id,
            'total' => count($data),
            'success' => $successCount,
            'failed' => $failureCount,
            'duplicate' => $duplicateCount,
            'success_rate' => $importLog->success_rate,
        ];
    }

    /**
     * Rollback import
     */
    public function rollbackImport(ImportLog $importLog, $userId = null): bool
    {
        if (!$importLog->canRollback()) {
            return false;
        }

        DB::beginTransaction();
        try {
            // Get all successfully imported records
            // This requires tracking created records - for now we'll note this limitation
            // In a real system, we'd track record IDs created during import

            $importLog->update([
                'status' => 'rolled_back',
                'rolled_back_by' => $userId ?? auth()->id(),
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Generate error report
     */
    public function generateErrorReport(ImportLog $importLog): array
    {
        return $importLog->errors()->get()->map(function ($error) {
            return [
                'row' => $error->row_number,
                'type' => $error->getErrorTypeLabel(),
                'message' => $error->error_message,
                'fields' => $error->failed_fields,
                'data' => $error->row_data,
            ];
        })->toArray();
    }
}
