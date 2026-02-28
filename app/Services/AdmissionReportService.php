<?php

namespace App\Services;

use App\Models\Registrant;
use App\Models\AdmissionPhase;
use App\Models\AdmissionSelection;
use App\Models\SelectionResult;
use Illuminate\Support\Facades\DB;

class AdmissionReportService
{
    /**
     * Get overall admission statistics
     */
    public function getOverallStatistics(): array
    {
        $totalRegistrants = Registrant::whereNull('deleted_at')->count();
        $submittedRegistrants = Registrant::where('application_status', 'submitted')->whereNull('deleted_at')->count();
        $passedRegistrants = Registrant::where('selection_status', 'passed')->whereNull('deleted_at')->count();
        $failedRegistrants = Registrant::where('selection_status', 'failed')->whereNull('deleted_at')->count();

        return [
            'total_registrants' => $totalRegistrants,
            'submitted' => $submittedRegistrants,
            'passed' => $passedRegistrants,
            'failed' => $failedRegistrants,
            'pending' => $totalRegistrants - $passedRegistrants - $failedRegistrants,
        ];
    }

    /**
     * Get registrants by admission phase
     */
    public function getRegistrantsByPhase(): array
    {
        $data = DB::table('registrants')
            ->join('admission_phases', 'registrants.admission_phase_id', '=', 'admission_phases.id')
            ->select('admission_phases.name', DB::raw('COUNT(registrants.id) as count'))
            ->where('registrants.deleted_at', null)
            ->groupBy('admission_phases.id', 'admission_phases.name')
            ->orderBy('count', 'desc')
            ->get();

        return $data->toArray();
    }

    /**
     * Get registrants by major
     */
    public function getRegistrantsByMajor(): array
    {
        $data = DB::table('registrants')
            ->join('majors', 'registrants.major_id', '=', 'majors.id')
            ->select('majors.name', DB::raw('COUNT(registrants.id) as count'))
            ->where('registrants.deleted_at', null)
            ->groupBy('majors.id', 'majors.name')
            ->orderBy('count', 'desc')
            ->get();

        return $data->toArray();
    }

    /**
     * Get acceptance rate
     */
    public function getAcceptanceRate(): array
    {
        $total = Registrant::whereNull('deleted_at')->count();
        $accepted = Registrant::where('selection_status', 'passed')->whereNull('deleted_at')->count();
        $rejected = Registrant::where('selection_status', 'failed')->whereNull('deleted_at')->count();

        $acceptanceRate = $total > 0 ? round(($accepted / $total) * 100, 2) : 0;
        $rejectionRate = $total > 0 ? round(($rejected / $total) * 100, 2) : 0;

        return [
            'total' => $total,
            'accepted' => $accepted,
            'rejected' => $rejected,
            'pending' => $total - $accepted - $rejected,
            'acceptance_rate' => $acceptanceRate,
            'rejection_rate' => $rejectionRate,
        ];
    }

    /**
     * Get registrants by gender
     */
    public function getRegistrantsByGender(): array
    {
        $data = DB::table('registrants')
            ->select('gender', DB::raw('COUNT(*) as count'))
            ->where('deleted_at', null)
            ->groupBy('gender')
            ->get();

        $distribution = [];
        foreach ($data as $item) {
            $gender = $item->gender === 'M' ? 'Laki-laki' : 'Perempuan';
            $distribution[$gender] = $item->count;
        }

        return $distribution;
    }

    /**
     * Get selection results statistics
     */
    public function getSelectionStatistics(AdmissionSelection $selection): array
    {
        $results = $selection->selectionResults;

        $passed = $results->where('result', 'passed')->count();
        $failed = $results->where('result', 'failed')->count();
        $waitlisted = $results->where('result', 'waitlisted')->count();
        $pending = $results->where('result', 'pending')->count();

        $avgScore = $results->avg('total_score') ?? 0;
        $highestScore = $results->max('total_score') ?? 0;
        $lowestScore = $results->min('total_score') ?? 0;

        return [
            'total_processed' => $results->count(),
            'passed' => $passed,
            'failed' => $failed,
            'waitlisted' => $waitlisted,
            'pending' => $pending,
            'average_score' => round($avgScore, 2),
            'highest_score' => $highestScore,
            'lowest_score' => $lowestScore,
            'quota_coverage' => $selection->total_quota > 0
                ? round(($passed / $selection->total_quota) * 100, 2)
                : 0,
        ];
    }

    /**
     * Get registration trend (by month)
     */
    public function getRegistrationTrend(): array
    {
        $data = DB::table('registrants')
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
     * Get major popularity ranking
     */
    public function getMajorPopularity(int $limit = 10): array
    {
        return DB::table('registrants')
            ->join('majors', 'registrants.major_id', '=', 'majors.id')
            ->select('majors.name', DB::raw('COUNT(registrants.id) as registrant_count'))
            ->where('registrants.deleted_at', null)
            ->groupBy('majors.id', 'majors.name')
            ->orderBy('registrant_count', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get geographic distribution
     */
    public function getGeographicDistribution(): array
    {
        $data = DB::table('registrants')
            ->select('sub_district', DB::raw('COUNT(*) as count'))
            ->where('deleted_at', null)
            ->whereNotNull('sub_district')
            ->groupBy('sub_district')
            ->orderBy('count', 'desc')
            ->limit(15)
            ->get();

        return $data->toArray();
    }

    /**
     * Get selection performance comparison
     */
    public function getSelectionPerformance(): array
    {
        $selections = AdmissionSelection::with('selectionResults')
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->limit(5)
            ->get();

        $data = [];
        foreach ($selections as $selection) {
            $stats = $this->getSelectionStatistics($selection);
            $data[] = [
                'name' => $selection->name,
                'stats' => $stats,
            ];
        }

        return $data;
    }

    /**
     * Get comprehensive admission report
     */
    public function getComprehensiveReport(): array
    {
        return [
            'overall_statistics' => $this->getOverallStatistics(),
            'acceptance_rate' => $this->getAcceptanceRate(),
            'by_phase' => $this->getRegistrantsByPhase(),
            'by_major' => $this->getRegistrantsByMajor(),
            'by_gender' => $this->getRegistrantsByGender(),
            'registration_trend' => $this->getRegistrationTrend(),
            'major_popularity' => $this->getMajorPopularity(),
            'geographic_distribution' => $this->getGeographicDistribution(),
            'selection_performance' => $this->getSelectionPerformance(),
        ];
    }
}
