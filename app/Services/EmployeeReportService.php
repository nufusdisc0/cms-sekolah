<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class EmployeeReportService
{
    /**
     * Get overall employee statistics
     */
    public function getOverallStatistics(): array
    {
        $totalEmployees = Employee::whereNull('deleted_at')->count();
        $maleEmployees = Employee::where('gender', 'M')->whereNull('deleted_at')->count();
        $femaleEmployees = Employee::where('gender', 'F')->whereNull('deleted_at')->count();

        return [
            'total' => $totalEmployees,
            'male' => $maleEmployees,
            'female' => $femaleEmployees,
        ];
    }

    /**
     * Get employees by employment type
     */
    public function getEmployeesByType(): array
    {
        $data = DB::table('employees')
            ->join('options', 'employees.employment_type_id', '=', 'options.id')
            ->select('options.name', DB::raw('COUNT(employees.id) as count'))
            ->where('employees.deleted_at', null)
            ->groupBy('options.id', 'options.name')
            ->orderBy('count', 'desc')
            ->get();

        return $data->toArray();
    }

    /**
     * Get employees by rank
     */
    public function getEmployeesByRank(): array
    {
        $data = DB::table('employees')
            ->leftJoin('options', 'employees.rank_id', '=', 'options.id')
            ->select(
                DB::raw("COALESCE(options.name, 'Tanpa Pangkat') as rank_name"),
                DB::raw('COUNT(employees.id) as count')
            )
            ->where('employees.deleted_at', null)
            ->groupBy('employees.rank_id', 'options.name')
            ->orderBy('count', 'desc')
            ->get();

        return $data->toArray();
    }

    /**
     * Get employees by status
     */
    public function getEmployeesByStatus(): array
    {
        $data = DB::table('employees')
            ->join('options', 'employees.employment_status_id', '=', 'options.id')
            ->select('options.name', DB::raw('COUNT(employees.id) as count'))
            ->where('employees.deleted_at', null)
            ->groupBy('options.id', 'options.name')
            ->orderBy('count', 'desc')
            ->get();

        return $data->toArray();
    }

    /**
     * Get gender distribution
     */
    public function getGenderDistribution(): array
    {
        $male = Employee::where('gender', 'M')->whereNull('deleted_at')->count();
        $female = Employee::where('gender', 'F')->whereNull('deleted_at')->count();

        return [
            'Laki-laki' => $male,
            'Perempuan' => $female,
        ];
    }

    /**
     * Get age distribution
     */
    public function getAgeDistribution(): array
    {
        $employees = Employee::whereNull('deleted_at')
            ->where('birth_date', '!=', null)
            ->get();

        $ageGroups = [
            '< 30' => 0,
            '30-40' => 0,
            '40-50' => 0,
            '> 50' => 0,
        ];

        foreach ($employees as $employee) {
            $age = now()->diffInYears($employee->birth_date);
            if ($age < 30) {
                $ageGroups['< 30']++;
            } elseif ($age < 40) {
                $ageGroups['30-40']++;
            } elseif ($age < 50) {
                $ageGroups['40-50']++;
            } else {
                $ageGroups['> 50']++;
            }
        }

        return $ageGroups;
    }

    /**
     * Get tenure distribution
     */
    public function getTenureDistribution(): array
    {
        $employees = Employee::whereNull('deleted_at')
            ->where('appointment_start_date', '!=', null)
            ->get();

        $tenureGroups = [
            '< 5 Tahun' => 0,
            '5-10 Tahun' => 0,
            '10-20 Tahun' => 0,
            '> 20 Tahun' => 0,
        ];

        foreach ($employees as $employee) {
            $tenure = now()->diffInYears($employee->appointment_start_date);
            if ($tenure < 5) {
                $tenureGroups['< 5 Tahun']++;
            } elseif ($tenure < 10) {
                $tenureGroups['5-10 Tahun']++;
            } elseif ($tenure < 20) {
                $tenureGroups['10-20 Tahun']++;
            } else {
                $tenureGroups['> 20 Tahun']++;
            }
        }

        return $tenureGroups;
    }

    /**
     * Get new hire trend
     */
    public function getNewHireTrend(): array
    {
        $data = DB::table('employees')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as count')
            )
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
     * Get certification rates
     */
    public function getCertificationRates(): array
    {
        $total = Employee::whereNull('deleted_at')->count();
        $withLicense = Employee::whereNotNull('headmaster_license')
            ->whereNull('deleted_at')
            ->count();

        $rate = $total > 0 ? round(($withLicense / $total) * 100, 2) : 0;

        return [
            'total_employees' => $total,
            'with_license' => $withLicense,
            'certification_rate' => $rate,
        ];
    }

    /**
     * Get salary distribution by source
     */
    public function getSalaryDistribution(): array
    {
        $data = DB::table('employees')
            ->join('options', 'employees.salary_source_id', '=', 'options.id')
            ->select('options.name', DB::raw('COUNT(employees.id) as count'))
            ->where('employees.deleted_at', null)
            ->groupBy('options.id', 'options.name')
            ->orderBy('count', 'desc')
            ->get();

        return $data->toArray();
    }

    /**
     * Get top departments by employee count
     */
    public function getTopEmploymentTypes(int $limit = 5): array
    {
        return DB::table('employees')
            ->join('options', 'employees.employment_type_id', '=', 'options.id')
            ->select('options.name', DB::raw('COUNT(employees.id) as count'))
            ->where('employees.deleted_at', null)
            ->groupBy('options.id', 'options.name')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get comprehensive employee report
     */
    public function getComprehensiveReport(): array
    {
        return [
            'overall_statistics' => $this->getOverallStatistics(),
            'gender_distribution' => $this->getGenderDistribution(),
            'age_distribution' => $this->getAgeDistribution(),
            'tenure_distribution' => $this->getTenureDistribution(),
            'by_type' => $this->getEmployeesByType(),
            'by_rank' => $this->getEmployeesByRank(),
            'by_status' => $this->getEmployeesByStatus(),
            'new_hire_trend' => $this->getNewHireTrend(),
            'certification_rates' => $this->getCertificationRates(),
            'salary_distribution' => $this->getSalaryDistribution(),
            'top_employment_types' => $this->getTopEmploymentTypes(),
        ];
    }
}
