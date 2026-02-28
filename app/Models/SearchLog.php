<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SearchLog extends Model
{
    protected $table = 'search_logs';

    protected $fillable = [
        'search_query',
        'search_type',
        'results_count',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'results_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get trending searches from past N days
     *
     * @param int $days Number of days to look back
     * @param int $limit Maximum results to return
     * @return array Array of trending searches
     */
    public static function getTrending(int $days = 7, int $limit = 10): array
    {
        $since = now()->subDays($days);

        $trending = self::where('created_at', '>=', $since)
            ->groupBy('search_query')
            ->selectRaw('search_query, COUNT(*) as count')
            ->orderByRaw('COUNT(*) DESC')
            ->limit($limit)
            ->get()
            ->toArray();

        return $trending;
    }

    /**
     * Get most searched queries overall
     *
     * @param int $limit Maximum results to return
     * @return array Array of most searched queries
     */
    public static function getMostSearched(int $limit = 10): array
    {
        $mostSearched = self::groupBy('search_query')
            ->selectRaw('search_query, COUNT(*) as count')
            ->orderByRaw('COUNT(*) DESC')
            ->limit($limit)
            ->get()
            ->toArray();

        return $mostSearched;
    }

    /**
     * Log a search query
     *
     * @param string $query The search query
     * @param string $type The type of search (all, post, page, etc.)
     * @param int $count Number of results found
     * @param string|null $ipAddress IP address of searcher
     * @param string|null $userAgent User agent string
     * @return SearchLog The created log entry
     */
    public static function logSearch(
        string $query,
        string $type = 'all',
        int $count = 0,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): self {
        return self::create([
            'search_query' => $query,
            'search_type' => $type,
            'results_count' => $count,
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
        ]);
    }

    /**
     * Get top search queries for a specific type
     *
     * @param string $type Search type (post, page, student, etc.)
     * @param int $days Number of days to look back
     * @param int $limit Maximum results
     * @return array
     */
    public static function getTrendingByType(string $type, int $days = 7, int $limit = 10): array
    {
        $since = now()->subDays($days);

        return self::where('search_type', $type)
            ->where('created_at', '>=', $since)
            ->groupBy('search_query')
            ->selectRaw('search_query, COUNT(*) as count')
            ->orderByRaw('COUNT(*) DESC')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get search statistics
     *
     * @return array Statistics about searches
     */
    public static function getStatistics(): array
    {
        $total = self::count();
        $unique = self::distinct('search_query')->count();
        $today = self::whereDate('created_at', today())->count();

        return [
            'total_searches' => $total,
            'unique_queries' => $unique,
            'searches_today' => $today,
        ];
    }
}
