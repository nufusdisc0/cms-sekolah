<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Employee;
use App\Models\Registrant;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class DashboardAnalyticsService
{
    protected $studentReportService;
    protected $admissionReportService;
    protected $employeeReportService;

    public function __construct(
        StudentReportService $studentReportService,
        AdmissionReportService $admissionReportService,
        EmployeeReportService $employeeReportService
    ) {
        $this->studentReportService = $studentReportService;
        $this->admissionReportService = $admissionReportService;
        $this->employeeReportService = $employeeReportService;
    }

    /**
     * Get key metrics for dashboard
     */
    public function getKeyMetrics(): array
    {
        $studentStats = $this->studentReportService->getOverallStatistics();
        $admissionStats = $this->admissionReportService->getOverallStatistics();
        $employeeStats = $this->employeeReportService->getOverallStatistics();

        return [
            'students' => [
                'total' => $studentStats['total'],
                'active' => $studentStats['active'],
                'alumni' => $studentStats['alumni'],
                'prospective' => $studentStats['prospective'],
            ],
            'admission' => [
                'total_registrants' => $admissionStats['total_registrants'],
                'passed' => $admissionStats['passed'],
                'failed' => $admissionStats['failed'],
                'pending' => $admissionStats['pending'],
            ],
            'employees' => [
                'total' => $employeeStats['total'],
                'male' => $employeeStats['male'],
                'female' => $employeeStats['female'],
            ],
            'content' => $this->getContentMetrics(),
        ];
    }

    /**
     * Get content metrics (posts, comments)
     */
    public function getContentMetrics(): array
    {
        $posts = Post::whereNull('deleted_at')->count();
        $comments = Comment::whereNull('deleted_at')->count();
        $postsThisMonth = Post::whereNull('deleted_at')
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();

        return [
            'total_posts' => $posts,
            'total_comments' => $comments,
            'posts_this_month' => $postsThisMonth,
            'average_comments' => $posts > 0 ? round($comments / $posts, 2) : 0,
        ];
    }

    /**
     * Get recent activities
     */
    public function getRecentActivities(int $limit = 10): array
    {
        $activities = [];

        // Recent new students
        $recentStudents = Student::whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get(['id', 'full_name', 'created_at']);

        foreach ($recentStudents as $student) {
            $activities[] = [
                'type' => 'student',
                'title' => "Siswa Baru: {$student->full_name}",
                'timestamp' => $student->created_at,
            ];
        }

        // Recent registrants
        $recentRegistrants = Registrant::whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get(['id', 'full_name', 'created_at']);

        foreach ($recentRegistrants as $registrant) {
            $activities[] = [
                'type' => 'registrant',
                'title' => "Pendaftar Baru: {$registrant->full_name}",
                'timestamp' => $registrant->created_at,
            ];
        }

        // Recent posts
        $recentPosts = Post::whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get(['id', 'title', 'created_at']);

        foreach ($recentPosts as $post) {
            $activities[] = [
                'type' => 'post',
                'title' => "Posting Baru: {$post->title}",
                'timestamp' => $post->created_at,
            ];
        }

        // Sort by timestamp descending and limit
        usort($activities, function ($a, $b) {
            return $b['timestamp']->getTimestamp() <=> $a['timestamp']->getTimestamp();
        });

        return array_slice($activities, 0, $limit);
    }

    /**
     * Get student vs quota comparison
     */
    public function getStudentVsQuotaComparison(): array
    {
        $totalQuota = DB::table('class_groups')
            ->whereNull('deleted_at')
            ->sum('quota') ?? 0;

        $totalEnrolled = Student::where('is_student', true)
            ->whereNull('deleted_at')
            ->count();

        $classGroups = DB::table('class_groups')
            ->select(
                'id',
                'name',
                'quota',
                DB::raw('(SELECT COUNT(*) FROM class_group_students WHERE class_group_id = class_groups.id) as enrolled')
            )
            ->whereNull('deleted_at')
            ->limit(10)
            ->get();

        return [
            'total_quota' => $totalQuota,
            'total_enrolled' => $totalEnrolled,
            'available_seats' => max(0, $totalQuota - $totalEnrolled),
            'enrollment_rate' => $totalQuota > 0 ? round(($totalEnrolled / $totalQuota) * 100, 2) : 0,
            'class_groups' => $classGroups->toArray(),
        ];
    }

    /**
     * Get admission funnel (application to acceptance)
     */
    public function getAdmissionFunnel(): array
    {
        $totalRegistrants = Registrant::whereNull('deleted_at')->count();
        $submitted = Registrant::where('application_status', 'submitted')
            ->whereNull('deleted_at')
            ->count();
        $passed = Registrant::where('selection_status', 'passed')
            ->whereNull('deleted_at')
            ->count();
        $confirmed = Registrant::where('application_status', 'confirmed')
            ->whereNull('deleted_at')
            ->count();
        $enrolled = Registrant::where('application_status', 'enrolled')
            ->whereNull('deleted_at')
            ->count();

        return [
            'total_registrants' => $totalRegistrants,
            'submitted' => [
                'count' => $submitted,
                'rate' => $totalRegistrants > 0 ? round(($submitted / $totalRegistrants) * 100, 2) : 0,
            ],
            'passed_selection' => [
                'count' => $passed,
                'rate' => $totalRegistrants > 0 ? round(($passed / $totalRegistrants) * 100, 2) : 0,
            ],
            'confirmed' => [
                'count' => $confirmed,
                'rate' => $totalRegistrants > 0 ? round(($confirmed / $totalRegistrants) * 100, 2) : 0,
            ],
            'enrolled' => [
                'count' => $enrolled,
                'rate' => $totalRegistrants > 0 ? round(($enrolled / $totalRegistrants) * 100, 2) : 0,
            ],
        ];
    }

    /**
     * Get comprehensive dashboard data
     */
    public function getComprehensiveDashboard(): array
    {
        return [
            'key_metrics' => $this->getKeyMetrics(),
            'student_vs_quota' => $this->getStudentVsQuotaComparison(),
            'admission_funnel' => $this->getAdmissionFunnel(),
            'recent_activities' => $this->getRecentActivities(),
            'student_stats' => $this->studentReportService->getComprehensiveReport(),
            'admission_stats' => $this->admissionReportService->getComprehensiveReport(),
            'employee_stats' => $this->employeeReportService->getComprehensiveReport(),
            'generated_at' => now()->toIso8601String(),
        ];
    }
}
