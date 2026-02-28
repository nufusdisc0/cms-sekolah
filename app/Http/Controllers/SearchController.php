<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    protected SearchService $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Show unified search results page
     *
     * @param Request $request
     * @return View
     */
    public function search(Request $request): View
    {
        $query = trim($request->query('q', ''));

        // Validate query length
        if (empty($query) || strlen($query) < 2 || strlen($query) > 255) {
            return view('public.search.results', [
                'query' => $query,
                'results' => [
                    'posts' => collect(),
                    'pages' => collect(),
                    'categories' => collect(),
                    'tags' => collect(),
                    'students' => collect(),
                    'alumni' => collect(),
                    'employees' => collect(),
                ],
                'trending' => $this->searchService->getTrending(7, 5),
                'error' => empty($query) ? 'Masukkan kata kunci pencarian' : 'Kata kunci harus 2-255 karakter',
            ]);
        }

        // Perform search
        $results = $this->searchService->searchAll($query, 10);

        // Get trending searches for sidebar
        $trending = $this->searchService->getTrending(7, 5);

        return view('public.search.results', [
            'query' => $query,
            'results' => $results,
            'trending' => $trending,
            'error' => null,
        ]);
    }

    /**
     * Get autocomplete suggestions as JSON
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function autocomplete(Request $request): JsonResponse
    {
        $query = trim($request->query('q', ''));

        // Minimum 2 characters for autocomplete
        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'results' => [],
                'message' => 'Masukkan minimal 2 karakter'
            ]);
        }

        $results = [];
        $perType = 2; // Limit per content type

        // Search all types with limited results
        $posts = $this->searchService->searchPosts($query, $perType);
        foreach ($posts as $post) {
            $results[] = [
                'id' => $post->id,
                'name' => $post->post_title,
                'type' => 'post',
                'icon' => 'file-text',
                'url' => route('public.post', $post->post_slug)
            ];
        }

        $pages = $this->searchService->searchPages($query, $perType);
        foreach ($pages as $page) {
            $results[] = [
                'id' => $page->id,
                'name' => $page->post_title,
                'type' => 'page',
                'icon' => 'file',
                'url' => route('public.page', $page->post_slug)
            ];
        }

        $students = $this->searchService->searchStudents($query, $perType);
        foreach ($students as $student) {
            $results[] = [
                'id' => $student->id,
                'name' => $student->full_name,
                'type' => 'student',
                'icon' => 'user-graduate',
                'url' => route('public.directory.student.profile', $student)
            ];
        }

        $alumni = $this->searchService->searchAlumni($query, $perType);
        foreach ($alumni as $alumnus) {
            $results[] = [
                'id' => $alumnus->id,
                'name' => $alumnus->full_name,
                'type' => 'alumni',
                'icon' => 'graduation-cap',
                'url' => route('public.directory.alumni.profile', $alumnus)
            ];
        }

        $employees = $this->searchService->searchEmployees($query, $perType);
        foreach ($employees as $employee) {
            $results[] = [
                'id' => $employee->id,
                'name' => $employee->full_name,
                'type' => 'employee',
                'icon' => 'briefcase',
                'url' => route('public.directory.employee.profile', $employee)
            ];
        }

        // Limit total results to 10
        $results = array_slice($results, 0, 10);

        return response()->json([
            'results' => $results,
            'count' => count($results)
        ]);
    }

    /**
     * Get trending searches as JSON
     *
     * @return JsonResponse
     */
    public function trending(): JsonResponse
    {
        $trending = $this->searchService->getTrending(7, 10);

        return response()->json([
            'trending' => $trending,
            'count' => count($trending)
        ]);
    }
}
