<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Student;
use App\Models\Employee;
use App\Models\SearchLog;
use Illuminate\Support\Collection;

class SearchService
{
    /**
     * Minimum search query length
     */
    private const MIN_QUERY_LENGTH = 2;

    /**
     * Maximum search query length
     */
    private const MAX_QUERY_LENGTH = 255;

    /**
     * Default results per page for content searches
     */
    private const DEFAULT_PER_PAGE = 10;

    /**
     * Validate search query
     *
     * @param string $query The search query
     * @return bool
     */
    private function validateQuery(string $query): bool
    {
        $query = trim($query);
        $length = strlen($query);

        return $length >= self::MIN_QUERY_LENGTH && $length <= self::MAX_QUERY_LENGTH;
    }

    /**
     * Sanitize query for SQL
     *
     * @param string $query
     * @return string
     */
    private function sanitizeQuery(string $query): string
    {
        return trim($query);
    }

    /**
     * Search all content types
     *
     * @param string $query The search query
     * @param int $perPage Results per type
     * @return array Associative array with content types as keys
     */
    public function searchAll(string $query, int $perPage = 10): array
    {
        if (!$this->validateQuery($query)) {
            return [
                'posts' => collect(),
                'pages' => collect(),
                'categories' => collect(),
                'tags' => collect(),
                'students' => collect(),
                'alumni' => collect(),
                'employees' => collect(),
            ];
        }

        $query = $this->sanitizeQuery($query);

        $results = [
            'posts' => $this->searchPosts($query, $perPage),
            'pages' => $this->searchPages($query, $perPage),
            'categories' => $this->searchCategories($query),
            'tags' => $this->searchTags($query),
            'students' => $this->searchStudents($query, 10),
            'alumni' => $this->searchAlumni($query, 10),
            'employees' => $this->searchEmployees($query, 10),
        ];

        // Count total results
        $totalResults = array_sum([
            $results['posts']->count(),
            $results['pages']->count(),
            $results['categories']->count(),
            $results['tags']->count(),
            $results['students']->count(),
            $results['alumni']->count(),
            $results['employees']->count(),
        ]);

        // Log the search
        SearchLog::logSearch($query, 'all', $totalResults);

        return $results;
    }

    /**
     * Search posts using full-text search
     *
     * @param string $query The search query
     * @param int $perPage Results per page
     * @return Collection
     */
    public function searchPosts(string $query, int $perPage = 10): Collection
    {
        if (!$this->validateQuery($query)) {
            return collect();
        }

        $query = $this->sanitizeQuery($query);

        try {
            $posts = Post::where('post_type', 'post')
                ->where('post_status', 'publish')
                ->where('post_visibility', 'public')
                ->whereRaw(
                    "MATCH(post_title, post_content) AGAINST(? IN BOOLEAN MODE)",
                    [$query]
                )
                ->orderByRaw(
                    "MATCH(post_title, post_content) AGAINST(? IN BOOLEAN MODE) DESC",
                    [$query]
                )
                ->limit($perPage)
                ->get([
                    'id',
                    'post_title',
                    'post_content',
                    'post_image',
                    'post_author',
                    'post_slug',
                    'created_at'
                ]);

            return $posts;
        } catch (\Exception $e) {
            // Fallback to LIKE search if full-text fails
            return $this->searchPostsLike($query, $perPage);
        }
    }

    /**
     * Fallback LIKE search for posts
     *
     * @param string $query
     * @param int $perPage
     * @return Collection
     */
    private function searchPostsLike(string $query, int $perPage): Collection
    {
        return Post::where('post_type', 'post')
            ->where('post_status', 'publish')
            ->where('post_visibility', 'public')
            ->where(function ($q) use ($query) {
                $q->where('post_title', 'like', "%{$query}%")
                    ->orWhere('post_content', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->limit($perPage)
            ->get([
                'id',
                'post_title',
                'post_content',
                'post_image',
                'post_author',
                'post_slug',
                'created_at'
            ]);
    }

    /**
     * Search pages using full-text search
     *
     * @param string $query The search query
     * @param int $perPage Results per page
     * @return Collection
     */
    public function searchPages(string $query, int $perPage = 10): Collection
    {
        if (!$this->validateQuery($query)) {
            return collect();
        }

        $query = $this->sanitizeQuery($query);

        try {
            $pages = Post::where('post_type', 'page')
                ->where('post_status', 'publish')
                ->where('post_visibility', 'public')
                ->whereRaw(
                    "MATCH(post_title, post_content) AGAINST(? IN BOOLEAN MODE)",
                    [$query]
                )
                ->orderByRaw(
                    "MATCH(post_title, post_content) AGAINST(? IN BOOLEAN MODE) DESC",
                    [$query]
                )
                ->limit($perPage)
                ->get([
                    'id',
                    'post_title',
                    'post_content',
                    'post_image',
                    'post_slug',
                    'created_at'
                ]);

            return $pages;
        } catch (\Exception $e) {
            // Fallback to LIKE search
            return Post::where('post_type', 'page')
                ->where('post_status', 'publish')
                ->where('post_visibility', 'public')
                ->where(function ($q) use ($query) {
                    $q->where('post_title', 'like', "%{$query}%")
                        ->orWhere('post_content', 'like', "%{$query}%");
                })
                ->orderBy('created_at', 'desc')
                ->limit($perPage)
                ->get([
                    'id',
                    'post_title',
                    'post_content',
                    'post_image',
                    'post_slug',
                    'created_at'
                ]);
        }
    }

    /**
     * Search categories using full-text search
     *
     * @param string $query The search query
     * @return Collection
     */
    public function searchCategories(string $query): Collection
    {
        if (!$this->validateQuery($query)) {
            return collect();
        }

        $query = $this->sanitizeQuery($query);

        try {
            return \App\Models\Category::whereRaw(
                "MATCH(category_name, category_description) AGAINST(? IN BOOLEAN MODE)",
                [$query]
            )
                ->orderByRaw(
                    "MATCH(category_name, category_description) AGAINST(? IN BOOLEAN MODE) DESC",
                    [$query]
                )
                ->limit(20)
                ->get(['id', 'category_name', 'category_slug', 'category_type']);
        } catch (\Exception $e) {
            // Fallback to LIKE
            return \App\Models\Category::where(function ($q) use ($query) {
                $q->where('category_name', 'like', "%{$query}%")
                    ->orWhere('category_description', 'like', "%{$query}%");
            })
                ->limit(20)
                ->get(['id', 'category_name', 'category_slug', 'category_type']);
        }
    }

    /**
     * Search tags using full-text search
     *
     * @param string $query The search query
     * @return Collection
     */
    public function searchTags(string $query): Collection
    {
        if (!$this->validateQuery($query)) {
            return collect();
        }

        $query = $this->sanitizeQuery($query);

        try {
            return \App\Models\Tag::whereRaw(
                "MATCH(tag) AGAINST(? IN BOOLEAN MODE)",
                [$query]
            )
                ->orderByRaw("MATCH(tag) AGAINST(? IN BOOLEAN MODE) DESC", [$query])
                ->limit(20)
                ->get(['id', 'tag', 'slug']);
        } catch (\Exception $e) {
            // Fallback to LIKE
            return \App\Models\Tag::where('tag', 'like', "%{$query}%")
                ->limit(20)
                ->get(['id', 'tag', 'slug']);
        }
    }

    /**
     * Search students
     *
     * @param string $query The search query
     * @param int $limit Maximum results
     * @return Collection
     */
    public function searchStudents(string $query, int $limit = 10): Collection
    {
        if (!$this->validateQuery($query)) {
            return collect();
        }

        $query = $this->sanitizeQuery($query);

        return Student::where('is_student', true)
            ->whereNull('deleted_at')
            ->where(function ($q) use ($query) {
                $q->where('full_name', 'like', "%{$query}%")
                    ->orWhere('nisn', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit($limit)
            ->get([
                'id',
                'full_name',
                'nisn',
                'email',
                'photo',
                'major_id'
            ]);
    }

    /**
     * Search alumni
     *
     * @param string $query The search query
     * @param int $limit Maximum results
     * @return Collection
     */
    public function searchAlumni(string $query, int $limit = 10): Collection
    {
        if (!$this->validateQuery($query)) {
            return collect();
        }

        $query = $this->sanitizeQuery($query);

        return Student::where('is_alumni', true)
            ->whereNull('deleted_at')
            ->where(function ($q) use ($query) {
                $q->where('full_name', 'like', "%{$query}%")
                    ->orWhere('nisn', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit($limit)
            ->get([
                'id',
                'full_name',
                'nisn',
                'email',
                'photo',
                'major_id'
            ]);
    }

    /**
     * Search employees
     *
     * @param string $query The search query
     * @param int $limit Maximum results
     * @return Collection
     */
    public function searchEmployees(string $query, int $limit = 10): Collection
    {
        if (!$this->validateQuery($query)) {
            return collect();
        }

        $query = $this->sanitizeQuery($query);

        return Employee::whereNull('deleted_at')
            ->where(function ($q) use ($query) {
                $q->where('full_name', 'like', "%{$query}%")
                    ->orWhere('nik', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit($limit)
            ->get([
                'id',
                'full_name',
                'nik',
                'nip',
                'email',
                'photo',
                'employment_type_id'
            ]);
    }

    /**
     * Get trending searches
     *
     * @param int $days Number of days to look back
     * @param int $limit Maximum results
     * @return array
     */
    public function getTrending(int $days = 7, int $limit = 10): array
    {
        return SearchLog::getTrending($days, $limit);
    }

    /**
     * Get search statistics
     *
     * @return array
     */
    public function getStatistics(): array
    {
        return SearchLog::getStatistics();
    }
}
