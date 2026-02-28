<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\StudentReportService;
use App\Services\AdmissionReportService;
use App\Services\EmployeeReportService;
use App\Services\DashboardAnalyticsService;
use Illuminate\Http\Request;

class ReportingController extends Controller
{
    protected $studentReport;
    protected $admissionReport;
    protected $employeeReport;
    protected $dashboardAnalytics;

    public function __construct(
        StudentReportService $studentReport,
        AdmissionReportService $admissionReport,
        EmployeeReportService $employeeReport,
        DashboardAnalyticsService $dashboardAnalytics
    ) {
        $this->studentReport = $studentReport;
        $this->admissionReport = $admissionReport;
        $this->employeeReport = $employeeReport;
        $this->dashboardAnalytics = $dashboardAnalytics;
    }

    /**
     * Show main reporting dashboard
     */
    public function dashboard()
    {
        $data = $this->dashboardAnalytics->getComprehensiveDashboard();

        return view('backend.reports.dashboard', $data);
    }

    /**
     * Show student statistics report
     */
    public function studentStatistics()
    {
        $data = $this->studentReport->getComprehensiveReport();

        return view('backend.reports.students', [
            'statistics' => $data,
            'overallStats' => $this->studentReport->getOverallStatistics(),
            'byClassGroup' => $this->studentReport->getStudentsByClassGroup(),
            'byMajor' => $this->studentReport->getStudentsByMajor(),
            'genderDistribution' => $this->studentReport->getGenderDistribution(),
            'ageDistribution' => $this->studentReport->getAgeDistribution(),
            'enrollmentRate' => $this->studentReport->getEnrollmentRate(),
            'byStatus' => $this->studentReport->getStudentsByStatus(),
        ]);
    }

    /**
     * Show admission analytics report
     */
    public function admissionAnalytics()
    {
        $data = $this->admissionReport->getComprehensiveReport();

        return view('backend.reports.admissions', [
            'statistics' => $data,
            'overallStats' => $this->admissionReport->getOverallStatistics(),
            'acceptanceRate' => $this->admissionReport->getAcceptanceRate(),
            'byPhase' => $this->admissionReport->getRegistrantsByPhase(),
            'byMajor' => $this->admissionReport->getRegistrantsByMajor(),
            'byGender' => $this->admissionReport->getRegistrantsByGender(),
            'majorPopularity' => $this->admissionReport->getMajorPopularity(),
        ]);
    }

    /**
     * Show employee statistics report
     */
    public function employeeStatistics()
    {
        $data = $this->employeeReport->getComprehensiveReport();

        return view('backend.reports.employees', [
            'statistics' => $data,
            'overallStats' => $this->employeeReport->getOverallStatistics(),
            'byType' => $this->employeeReport->getEmployeesByType(),
            'byRank' => $this->employeeReport->getEmployeesByRank(),
            'byStatus' => $this->employeeReport->getEmployeesByStatus(),
            'genderDistribution' => $this->employeeReport->getGenderDistribution(),
            'ageDistribution' => $this->employeeReport->getAgeDistribution(),
            'tenureDistribution' => $this->employeeReport->getTenureDistribution(),
            'certificationRates' => $this->employeeReport->getCertificationRates(),
        ]);
    }

    /**
     * Show academic analysis report
     */
    public function academicAnalysis()
    {
        $studentVsQuota = $this->dashboardAnalytics->getStudentVsQuotaComparison();
        $admissionFunnel = $this->dashboardAnalytics->getAdmissionFunnel();

        return view('backend.reports.academic', [
            'studentVsQuota' => $studentVsQuota,
            'admissionFunnel' => $admissionFunnel,
            'studentStats' => $this->studentReport->getComprehensiveReport(),
        ]);
    }

    /**
     * Export student report to CSV
     */
    public function exportStudentReport()
    {
        $stats = $this->studentReport->getComprehensiveReport();

        $filename = 'student_report_' . now()->format('YmdHis') . '.csv';

        return response()->stream(function () use ($stats) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Overall Statistics
            fputcsv($file, ['Statistik Siswa']);
            fputcsv($file, ['Total', $stats['overall_statistics']['total']]);
            fputcsv($file, ['Aktif', $stats['overall_statistics']['active']]);
            fputcsv($file, ['Alumni', $stats['overall_statistics']['alumni']]);
            fputcsv($file, []);

            // By Class Group
            fputcsv($file, ['Siswa Per Rombongan Belajar']);
            fputcsv($file, ['Rombongan Belajar', 'Jumlah']);
            foreach ($stats['by_class_group'] as $item) {
                fputcsv($file, [(array)$item]);
            }
            fputcsv($file, []);

            // By Major
            fputcsv($file, ['Siswa Per Jurusan']);
            fputcsv($file, ['Jurusan', 'Jumlah']);
            foreach ($stats['by_major'] as $item) {
                fputcsv($file, [(array)$item]);
            }

            fclose($file);
        }, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Export admission report to CSV
     */
    public function exportAdmissionReport()
    {
        $stats = $this->admissionReport->getComprehensiveReport();

        $filename = 'admission_report_' . now()->format('YmdHis') . '.csv';

        return response()->stream(function () use ($stats) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Overall Statistics
            fputcsv($file, ['Statistik Seleksi Pendaftaran']);
            fputcsv($file, ['Total Pendaftar', $stats['overall_statistics']['total_registrants']]);
            fputcsv($file, ['Diterima', $stats['overall_statistics']['passed']]);
            fputcsv($file, ['Ditolak', $stats['overall_statistics']['failed']]);
            fputcsv($file, ['Menunggu', $stats['overall_statistics']['pending']]);
            fputcsv($file, []);

            // Acceptance Rate
            fputcsv($file, ['Hasil Seleksi']);
            fputcsv($file, ['Tingkat Penerimaan (%)', $stats['acceptance_rate']['acceptance_rate']]);
            fputcsv($file, ['Tingkat Penolakan (%)', $stats['acceptance_rate']['rejection_rate']]);
            fputcsv($file, []);

            // By Major
            fputcsv($file, ['Pendaftar Per Jurusan']);
            fputcsv($file, ['Jurusan', 'Jumlah']);
            foreach ($stats['by_major'] as $item) {
                fputcsv($file, [(array)$item]);
            }

            fclose($file);
        }, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Export employee report to CSV
     */
    public function exportEmployeeReport()
    {
        $stats = $this->employeeReport->getComprehensiveReport();

        $filename = 'employee_report_' . now()->format('YmdHis') . '.csv';

        return response()->stream(function () use ($stats) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Overall Statistics
            fputcsv($file, ['Statistik Karyawan']);
            fputcsv($file, ['Total', $stats['overall_statistics']['total']]);
            fputcsv($file, ['Laki-laki', $stats['overall_statistics']['male']]);
            fputcsv($file, ['Perempuan', $stats['overall_statistics']['female']]);
            fputcsv($file, []);

            // By Type
            fputcsv($file, ['Karyawan Per Jenis Kepegawaian']);
            fputcsv($file, ['Jenis', 'Jumlah']);
            foreach ($stats['by_type'] as $item) {
                fputcsv($file, [(array)$item]);
            }
            fputcsv($file, []);

            // Certification
            fputcsv($file, ['Sertifikasi']);
            fputcsv($file, ['Tingkat Sertifikasi (%)', $stats['certification_rates']['certification_rate']]);

            fclose($file);
        }, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
