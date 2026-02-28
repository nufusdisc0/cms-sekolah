<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentReportService
{
    /**
     * Get overall student statistics
     */
    public function getOverallStatistics(): array
    {
        $totalStudents = Student::whereNull('deleted_at')->count();
        $activeStudents = Student::where('is_student', true)->whereNull('deleted_at')->count();
        $prospectiveStudents = Student::where('is_prospective_student', true)->whereNull('deleted_at')->count();
        $alumni = Student::where('is_alumni', true)->whereNull('deleted_at')->count();

        return [
            'total' => $totalStudents,
            'active' => $activeStudents,
            'prospective' => $prospectiveStudents,
            'alumni' => $alumni,
        ];
    }

    /**
     * Get students by class group
     */
    public function getStudentsByClassGroup(): array
    {
        $data = DB::table('students')
            ->join('class_group_students', 'students.id', '=', 'class_group_students.student_id')
            ->join('class_groups', 'class_group_students.class_group_id', '=', 'class_groups.id')
            ->select('class_groups.name', DB::raw('COUNT(students.id) as count'))
            ->where('students.deleted_at', null)
            ->where('students.is_student', true)
            ->groupBy('class_groups.id', 'class_groups.name')
            ->orderBy('count', 'desc')
            ->get();

        return $data->toArray();
    }

    /**
     * Get students by major
     */
    public function getStudentsByMajor(): array
    {
        $data = DB::table('students')
            ->join('majors', 'students.major_id', '=', 'majors.id')
            ->select('majors.name', DB::raw('COUNT(students.id) as count'))
            ->where('students.deleted_at', null)
            ->where('students.is_student', true)
            ->groupBy('majors.id', 'majors.name')
            ->orderBy('count', 'desc')
            ->get();

        return $data->toArray();
    }

    /**
     * Get gender distribution
     */
    public function getGenderDistribution(): array
    {
        $data = DB::table('students')
            ->select('gender', DB::raw('COUNT(*) as count'))
            ->where('deleted_at', null)
            ->where('is_student', true)
            ->groupBy('gender')
            ->get();

        $distribution = [];
        foreach ($data as $item) {
            $distribution[$item->gender === 'M' ? 'Laki-laki' : 'Perempuan'] = $item->count;
        }

        return $distribution;
    }

    /**
     * Get enrollment rate vs quota
     */
    public function getEnrollmentRate(): array
    {
        // This would connect to class group quotas if available
        $totalQuota = DB::table('class_groups')
            ->whereNull('deleted_at')
            ->sum('quota') ?? 0;

        $totalEnrolled = Student::where('is_student', true)
            ->whereNull('deleted_at')
            ->count();

        $rate = $totalQuota > 0 ? round(($totalEnrolled / $totalQuota) * 100, 2) : 0;

        return [
            'total_quota' => $totalQuota,
            'total_enrolled' => $totalEnrolled,
            'enrollment_rate' => $rate,
            'available_seats' => max(0, $totalQuota - $totalEnrolled),
        ];
    }

    /**
     * Get new students by month (for current academic year)
     */
    public function getNewStudentsTrend(): array
    {
        $data = DB::table('students')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as count')
            )
            ->where('is_student', true)
            ->where('deleted_at', null)
            ->where('created_at', '>=', now()->subYear())
            ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $trend = [];
        foreach ($data as $item) {
            $label = $monthNames[$item->month] . ' ' . $item->year;
            $trend[$label] = $item->count;
        }

        return $trend;
    }

    /**
     * Get student by status distribution
     */
    public function getStudentsByStatus(): array
    {
        $active = Student::where('is_student', true)->whereNull('deleted_at')->count();
        $prospective = Student::where('is_prospective_student', true)->whereNull('deleted_at')->count();
        $alumni = Student::where('is_alumni', true)->whereNull('deleted_at')->count();
        $transfer = Student::where('is_transfer', true)->whereNull('deleted_at')->count();

        return [
            'Aktif' => $active,
            'Calon Siswa' => $prospective,
            'Alumni' => $alumni,
            'Pindahan' => $transfer,
        ];
    }

    /**
     * Get age distribution
     */
    public function getAgeDistribution(): array
    {
        $students = Student::whereNull('deleted_at')
            ->where('birth_date', '!=', null)
            ->get();

        $ageGroups = [
            '< 15' => 0,
            '15-17' => 0,
            '18-20' => 0,
            '> 20' => 0,
        ];

        foreach ($students as $student) {
            $age = now()->diffInYears($student->birth_date);
            if ($age < 15) {
                $ageGroups['< 15']++;
            } elseif ($age <= 17) {
                $ageGroups['15-17']++;
            } elseif ($age <= 20) {
                $ageGroups['18-20']++;
            } else {
                $ageGroups['> 20']++;
            }
        }

        return $ageGroups;
    }

    /**
     * Get top performing class groups
     */
    public function getTopClassGroups(int $limit = 5): array
    {
        return DB::table('class_groups')
            ->leftJoin('class_group_students', 'class_groups.id', '=', 'class_group_students.class_group_id')
            ->select('class_groups.name', DB::raw('COUNT(class_group_students.student_id) as student_count'))
            ->whereNull('class_groups.deleted_at')
            ->groupBy('class_groups.id', 'class_groups.name')
            ->orderBy('student_count', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get comprehensive student report
     */
    public function getComprehensiveReport(): array
    {
        return [
            'overall_statistics' => $this->getOverallStatistics(),
            'by_status' => $this->getStudentsByStatus(),
            'by_class_group' => $this->getStudentsByClassGroup(),
            'by_major' => $this->getStudentsByMajor(),
            'gender_distribution' => $this->getGenderDistribution(),
            'age_distribution' => $this->getAgeDistribution(),
            'enrollment_rate' => $this->getEnrollmentRate(),
            'new_students_trend' => $this->getNewStudentsTrend(),
            'top_class_groups' => $this->getTopClassGroups(),
        ];
    }
}
