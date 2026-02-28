<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Employee;
use Illuminate\Http\Request;

class PublicDirectoryController extends Controller
{
    /**
     * Show alumni directory
     */
    public function showAlumniDirectory(Request $request)
    {
        $query = Student::where('is_alumni', true)
            ->whereNull('deleted_at')
            ->orderBy('full_name', 'asc');

        // Search by name, NISN, or email
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by major
        if ($request->has('major') && !empty($request->major)) {
            $query->where('major_id', $request->major);
        }

        // Filter by graduation year (assuming end_date exists)
        if ($request->has('year') && !empty($request->year)) {
            $query->whereYear('end_date', $request->year);
        }

        $alumni = $query->paginate(20);
        $majors = $this->getActiveMajors();
        $graduationYears = $this->getGraduationYears();

        return view('public.directory.alumni', compact('alumni', 'majors', 'graduationYears'));
    }

    /**
     * Show student directory
     */
    public function showStudentDirectory(Request $request)
    {
        $query = Student::where('is_student', true)
            ->whereNull('deleted_at')
            ->orderBy('full_name', 'asc');

        // Search by name, NISN, or email
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by major
        if ($request->has('major') && !empty($request->major)) {
            $query->where('major_id', $request->major);
        }

        // Filter by class group
        if ($request->has('class_group') && !empty($request->class_group)) {
            $query->whereHas('classGroups', function ($q) {
                $q->where('class_group_id', request('class_group'));
            });
        }

        // Filter by gender
        if ($request->has('gender') && !empty($request->gender)) {
            $query->where('gender', $request->gender);
        }

        $students = $query->paginate(20);
        $majors = $this->getActiveMajors();
        $classGroups = $this->getActiveClassGroups();

        return view('public.directory.students', compact('students', 'majors', 'classGroups'));
    }

    /**
     * Show employee directory
     */
    public function showEmployeeDirectory(Request $request)
    {
        $query = Employee::whereNull('deleted_at')
            ->orderBy('full_name', 'asc');

        // Search by name, NIK, or email
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by employment type
        if ($request->has('employment_type') && !empty($request->employment_type)) {
            $query->where('employment_type_id', $request->employment_type);
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('employment_status_id', $request->status);
        }

        // Filter by gender
        if ($request->has('gender') && !empty($request->gender)) {
            $query->where('gender', $request->gender);
        }

        $employees = $query->paginate(20);
        $employmentTypes = $this->getEmploymentTypes();
        $employmentStatuses = $this->getEmploymentStatuses();

        return view('public.directory.employees', compact('employees', 'employmentTypes', 'employmentStatuses'));
    }

    /**
     * Show individual alumni profile
     */
    public function showAlumniProfile(Student $student)
    {
        // Check if student is alumni
        if (!$student->is_alumni || $student->deleted_at) {
            abort(404);
        }

        // Get related information
        $student->load('major');

        return view('public.directory.alumni-profile', compact('student'));
    }

    /**
     * Show individual student profile
     */
    public function showStudentProfile(Student $student)
    {
        // Check if student is active (privacy consideration)
        if (!$student->is_student || $student->deleted_at) {
            abort(404);
        }

        $student->load('major', 'classGroups');

        return view('public.directory.student-profile', compact('student'));
    }

    /**
     * Show individual employee profile
     */
    public function showEmployeeProfile(Employee $employee)
    {
        if ($employee->deleted_at) {
            abort(404);
        }

        return view('public.directory.employee-profile', compact('employee'));
    }

    /**
     * API: Directory search
     */
    public function searchDirectory(Request $request)
    {
        $type = $request->get('type', 'student'); // student, alumni, or employee
        $query = $request->get('q', '');

        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'results' => [],
                'message' => 'Masukkan minimal 2 karakter'
            ]);
        }

        $results = [];

        if ($type === 'alumni') {
            $alumni = Student::where('is_alumni', true)
                ->whereNull('deleted_at')
                ->where(function ($q) use ($query) {
                    $q->where('full_name', 'like', "%{$query}%")
                        ->orWhere('nisn', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%");
                })
                ->limit(10)
                ->get(['id', 'full_name', 'nisn', 'email']);

            foreach ($alumni as $item) {
                $results[] = [
                    'id' => $item->id,
                    'name' => $item->full_name,
                    'info' => $item->nisn ? "NISN: {$item->nisn}" : $item->email,
                    'url' => route('public.directory.alumni.profile', $item),
                    'type' => 'alumni'
                ];
            }
        } elseif ($type === 'student') {
            $students = Student::where('is_student', true)
                ->whereNull('deleted_at')
                ->where(function ($q) use ($query) {
                    $q->where('full_name', 'like', "%{$query}%")
                        ->orWhere('nisn', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%");
                })
                ->limit(10)
                ->get(['id', 'full_name', 'nisn', 'email']);

            foreach ($students as $item) {
                $results[] = [
                    'id' => $item->id,
                    'name' => $item->full_name,
                    'info' => $item->nisn ? "NISN: {$item->nisn}" : $item->email,
                    'url' => route('public.directory.student.profile', $item),
                    'type' => 'student'
                ];
            }
        } elseif ($type === 'employee') {
            $employees = Employee::whereNull('deleted_at')
                ->where(function ($q) use ($query) {
                    $q->where('full_name', 'like', "%{$query}%")
                        ->orWhere('nik', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%");
                })
                ->limit(10)
                ->get(['id', 'full_name', 'nik', 'email']);

            foreach ($employees as $item) {
                $results[] = [
                    'id' => $item->id,
                    'name' => $item->full_name,
                    'info' => $item->nik ? "NIK: {$item->nik}" : $item->email,
                    'url' => route('public.directory.employee.profile', $item),
                    'type' => 'employee'
                ];
            }
        }

        return response()->json([
            'results' => $results,
            'count' => count($results)
        ]);
    }

    /**
     * Helper: Get active majors
     */
    private function getActiveMajors()
    {
        return \App\Models\Major::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * Helper: Get active class groups
     */
    private function getActiveClassGroups()
    {
        return \App\Models\ClassGroup::whereNull('deleted_at')
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * Helper: Get employment types
     */
    private function getEmploymentTypes()
    {
        return \App\Models\Option::where('category', 'employment_type')
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * Helper: Get employment statuses
     */
    private function getEmploymentStatuses()
    {
        return \App\Models\Option::where('category', 'employment_status')
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * Helper: Get graduation years
     */
    private function getGraduationYears()
    {
        $years = Student::where('is_alumni', true)
            ->whereNull('deleted_at')
            ->whereNotNull('end_date')
            ->selectRaw('YEAR(end_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return $years;
    }
}
