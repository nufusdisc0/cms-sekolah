<?php

namespace App\Http\Controllers;

use App\Services\FeedService;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FeedController extends Controller
{
    protected FeedService $feedService;

    public function __construct(FeedService $feedService)
    {
        $this->feedService = $feedService;
    }

    /**
     * Main blog RSS feed
     */
    public function feedBlog(Request $request): Response
    {
        $format = $request->query('format', 'rss');

        // Determine format from request path
        if ($request->path() === 'feed.json') {
            $format = 'json';
        } elseif ($request->path() === 'feed/atom') {
            $format = 'atom';
        }

        // Get from cache or generate
        $cacheKey = "feed:blog:{$format}";
        $feed = cache()->remember($cacheKey, 3600, function () use ($format) {
            return $this->feedService->generateBlogFeed($format);
        });

        return response($feed, 200)
            ->header('Content-Type', $this->getContentType($format))
            ->header('Cache-Control', 'public, max-age=3600, immutable')
            ->header('X-Content-Type-Options', 'nosniff');
    }

    /**
     * Atom blog feed
     */
    public function feedAtom(Request $request): Response
    {
        return $this->feedBlog($request);
    }

    /**
     * Category-specific feed
     */
    public function feedCategory(Category $category, Request $request): Response
    {
        $format = $request->query('format', 'rss');

        // Determine format from path
        if (str_ends_with($request->path(), '.json')) {
            $format = 'json';
        } elseif (str_contains($request->path(), 'atom')) {
            $format = 'atom';
        }

        // Get from cache or generate
        $cacheKey = "feed:category:{$category->id}:{$format}";
        $feed = cache()->remember($cacheKey, 3600, function () use ($category, $format) {
            return $this->feedService->generateCategoryFeed($category, $format);
        });

        return response($feed, 200)
            ->header('Content-Type', $this->getContentType($format))
            ->header('Cache-Control', 'public, max-age=3600, immutable')
            ->header('X-Content-Type-Options', 'nosniff');
    }

    /**
     * Tag-specific feed
     */
    public function feedTag(Tag $tag, Request $request): Response
    {
        $format = $request->query('format', 'rss');

        // Determine format from path
        if (str_ends_with($request->path(), '.json')) {
            $format = 'json';
        } elseif (str_contains($request->path(), 'atom')) {
            $format = 'atom';
        }

        // Get from cache or generate
        $cacheKey = "feed:tag:{$tag->id}:{$format}";
        $feed = cache()->remember($cacheKey, 3600, function () use ($tag, $format) {
            return $this->feedService->generateTagFeed($tag, $format);
        });

        return response($feed, 200)
            ->header('Content-Type', $this->getContentType($format))
            ->header('Cache-Control', 'public, max-age=3600, immutable')
            ->header('X-Content-Type-Options', 'nosniff');
    }

    /**
     * Alumni directory feed
     */
    public function feedAlumni(Request $request): Response
    {
        $format = $request->query('format', 'rss');

        // Determine format from path
        if (str_ends_with($request->path(), '.json')) {
            $format = 'json';
        } elseif (str_contains($request->path(), 'atom')) {
            $format = 'atom';
        }

        // Get from cache or generate
        $cacheKey = "feed:alumni:{$format}";
        $feed = cache()->remember($cacheKey, 3600, function () use ($format) {
            return $this->feedService->generateAlumniFeed($format);
        });

        return response($feed, 200)
            ->header('Content-Type', $this->getContentType($format))
            ->header('Cache-Control', 'public, max-age=3600, immutable')
            ->header('X-Content-Type-Options', 'nosniff');
    }

    /**
     * Students directory feed
     */
    public function feedStudents(Request $request): Response
    {
        $format = $request->query('format', 'rss');

        // Determine format from path
        if (str_ends_with($request->path(), '.json')) {
            $format = 'json';
        } elseif (str_contains($request->path(), 'atom')) {
            $format = 'atom';
        }

        // Get from cache or generate
        $cacheKey = "feed:students:{$format}";
        $feed = cache()->remember($cacheKey, 3600, function () use ($format) {
            return $this->feedService->generateStudentFeed($format);
        });

        return response($feed, 200)
            ->header('Content-Type', $this->getContentType($format))
            ->header('Cache-Control', 'public, max-age=3600, immutable')
            ->header('X-Content-Type-Options', 'nosniff');
    }

    /**
     * Employees/Staff directory feed
     */
    public function feedEmployees(Request $request): Response
    {
        $format = $request->query('format', 'rss');

        // Determine format from path
        if (str_ends_with($request->path(), '.json')) {
            $format = 'json';
        } elseif (str_contains($request->path(), 'atom')) {
            $format = 'atom';
        }

        // Get from cache or generate
        $cacheKey = "feed:employees:{$format}";
        $feed = cache()->remember($cacheKey, 3600, function () use ($format) {
            return $this->feedService->generateEmployeeFeed($format);
        });

        return response($feed, 200)
            ->header('Content-Type', $this->getContentType($format))
            ->header('Cache-Control', 'public, max-age=3600, immutable')
            ->header('X-Content-Type-Options', 'nosniff');
    }

    /**
     * Get Content-Type header for feed format
     */
    private function getContentType(string $format): string
    {
        return match($format) {
            'json' => 'application/feed+json; charset=utf-8',
            'atom' => 'application/atom+xml; charset=utf-8',
            default => 'application/rss+xml; charset=utf-8',
        };
    }
}
