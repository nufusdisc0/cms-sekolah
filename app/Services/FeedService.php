<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Student;
use App\Models\Employee;
use Illuminate\Support\Collection;

class FeedService
{
    /**
     * Default feed limit
     */
    private const DEFAULT_LIMIT = 50;

    /**
     * Generate blog feed (posts + pages)
     */
    public function generateBlogFeed(string $format = 'rss', int $limit = self::DEFAULT_LIMIT): string
    {
        $items = Post::where('post_type', 'post')
            ->where('post_status', 'publish')
            ->where('post_visibility', 'public')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get(['id', 'post_title', 'post_slug', 'post_content', 'post_image', 'post_author', 'post_categories', 'post_tags', 'created_at', 'updated_at']);

        $title = config('app.name') . ' - Blog';
        $description = 'Latest posts and articles from ' . config('app.name');
        $link = url('/');

        return match($format) {
            'atom' => $this->formatAtomFeed($items, $title, $description, $link),
            'json' => $this->formatJsonFeed($items, $title, $description),
            default => $this->formatRss2Feed($items, $title, $description, $link),
        };
    }

    /**
     * Generate category-specific feed
     */
    public function generateCategoryFeed(Category $category, string $format = 'rss', int $limit = self::DEFAULT_LIMIT): string
    {
        $items = Post::where('post_type', 'post')
            ->where('post_status', 'publish')
            ->where('post_visibility', 'public')
            ->where('post_categories', 'LIKE', "%+{$category->id}+%")
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get(['id', 'post_title', 'post_slug', 'post_content', 'post_image', 'post_author', 'post_categories', 'post_tags', 'created_at', 'updated_at']);

        $title = config('app.name') . ' - ' . $category->category_name;
        $description = $category->category_description ?? 'Posts in ' . $category->category_name;
        $link = route('public.category', $category->category_slug);

        return match($format) {
            'atom' => $this->formatAtomFeed($items, $title, $description, $link),
            'json' => $this->formatJsonFeed($items, $title, $description),
            default => $this->formatRss2Feed($items, $title, $description, $link),
        };
    }

    /**
     * Generate tag-specific feed
     */
    public function generateTagFeed(Tag $tag, string $format = 'rss', int $limit = self::DEFAULT_LIMIT): string
    {
        $items = Post::where('post_type', 'post')
            ->where('post_status', 'publish')
            ->where('post_visibility', 'public')
            ->where('post_tags', 'LIKE', '%' . $tag->tag . '%')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get(['id', 'post_title', 'post_slug', 'post_content', 'post_image', 'post_author', 'post_categories', 'post_tags', 'created_at', 'updated_at']);

        $title = config('app.name') . ' - #' . $tag->tag;
        $description = 'Posts tagged with ' . $tag->tag;
        $link = route('public.tag', $tag->slug);

        return match($format) {
            'atom' => $this->formatAtomFeed($items, $title, $description, $link),
            'json' => $this->formatJsonFeed($items, $title, $description),
            default => $this->formatRss2Feed($items, $title, $description, $link),
        };
    }

    /**
     * Generate alumni directory feed
     */
    public function generateAlumniFeed(string $format = 'rss', int $limit = self::DEFAULT_LIMIT): string
    {
        $items = Student::where('is_alumni', true)
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->with('major')
            ->get(['id', 'full_name', 'major_id', 'photo', 'email', 'updated_at']);

        $title = config('app.name') . ' - Alumni Directory';
        $description = 'Latest alumni from ' . config('app.name');
        $link = route('public.directory.alumni');

        return match($format) {
            'atom' => $this->formatAtomFeed($items, $title, $description, $link, 'alumni'),
            'json' => $this->formatJsonFeed($items, $title, $description, 'alumni'),
            default => $this->formatRss2Feed($items, $title, $description, $link, 'alumni'),
        };
    }

    /**
     * Generate students directory feed
     */
    public function generateStudentFeed(string $format = 'rss', int $limit = self::DEFAULT_LIMIT): string
    {
        $items = Student::where('is_student', true)
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->with('major', 'classGroups')
            ->get(['id', 'full_name', 'major_id', 'photo', 'email', 'updated_at']);

        $title = config('app.name') . ' - Student Directory';
        $description = 'Current students of ' . config('app.name');
        $link = route('public.directory.students');

        return match($format) {
            'atom' => $this->formatAtomFeed($items, $title, $description, $link, 'student'),
            'json' => $this->formatJsonFeed($items, $title, $description, 'student'),
            default => $this->formatRss2Feed($items, $title, $description, $link, 'student'),
        };
    }

    /**
     * Generate employees/staff directory feed
     */
    public function generateEmployeeFeed(string $format = 'rss', int $limit = self::DEFAULT_LIMIT): string
    {
        $items = Employee::whereNull('deleted_at')
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->with('employmentType')
            ->get(['id', 'full_name', 'employment_type_id', 'photo', 'email', 'nip', 'updated_at']);

        $title = config('app.name') . ' - Staff Directory';
        $description = 'Staff and employees of ' . config('app.name');
        $link = route('public.directory.employees');

        return match($format) {
            'atom' => $this->formatAtomFeed($items, $title, $description, $link, 'employee'),
            'json' => $this->formatJsonFeed($items, $title, $description, 'employee'),
            default => $this->formatRss2Feed($items, $title, $description, $link, 'employee'),
        };
    }

    /**
     * Format as RSS 2.0
     */
    public function formatRss2Feed(Collection $items, string $title, string $description, string $link, string $type = 'post'): string
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
        $xml .= "<rss version=\"2.0\" xmlns:content=\"http://purl.org/rss/1.0/modules/content/\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n";
        $xml .= "  <channel>\n";
        $xml .= "    <title>" . htmlspecialchars($title) . "</title>\n";
        $xml .= "    <link>" . htmlspecialchars($link) . "</link>\n";
        $xml .= "    <description>" . htmlspecialchars($description) . "</description>\n";
        $xml .= "    <language>id-ID</language>\n";
        $xml .= "    <lastBuildDate>" . date('r') . "</lastBuildDate>\n";
        $xml .= "    <atom:link href=\"" . htmlspecialchars(request()->url()) . "\" rel=\"self\" type=\"application/rss+xml\"/>\n";

        foreach ($items as $item) {
            $itemTitle = $type === 'post' ? $item->post_title : $item->full_name;
            $itemLink = $type === 'post' ? route('public.post', $item->post_slug) : (
                $type === 'alumni' ? route('public.directory.alumni.profile', $item) : (
                    $type === 'student' ? route('public.directory.student.profile', $item) : route('public.directory.employee.profile', $item)
                )
            );
            $itemDate = $type === 'post' ? $item->created_at : $item->updated_at;

            if ($type === 'post') {
                $description = strip_tags(substr($item->post_content, 0, 200)) . '...';
                $author = $item->author ? $item->author->name : 'Admin';
            } else {
                $description = $type === 'alumni' ? ($item->major?->name ?? 'Alumni') :
                              ($type === 'student' ? ($item->major?->name ?? 'Student') :
                              ($item->employmentType?->name ?? 'Staff'));
                $author = $item->email ?? config('app.name');
            }

            $xml .= "    <item>\n";
            $xml .= "      <title>" . htmlspecialchars($itemTitle) . "</title>\n";
            $xml .= "      <link>" . htmlspecialchars($itemLink) . "</link>\n";
            $xml .= "      <guid isPermaLink=\"true\">" . htmlspecialchars($itemLink) . "</guid>\n";
            $xml .= "      <description><![CDATA[" . htmlspecialchars($description) . "]]></description>\n";

            if ($type === 'post' && $item->post_content) {
                $xml .= "      <content:encoded><![CDATA[" . $item->post_content . "]]></content:encoded>\n";
            }

            $xml .= "      <pubDate>" . $itemDate->format('r') . "</pubDate>\n";
            $xml .= "      <dc:creator>" . htmlspecialchars($author) . "</dc:creator>\n";

            if ($type === 'post') {
                $categories = $this->extractCategories($item->post_categories);
                foreach ($categories as $cat) {
                    $xml .= "      <category>" . htmlspecialchars($cat) . "</category>\n";
                }
                $tags = $this->extractTags($item->post_tags);
                foreach ($tags as $t) {
                    $xml .= "      <category>" . htmlspecialchars($t) . "</category>\n";
                }
            }

            $xml .= "    </item>\n";
        }

        $xml .= "  </channel>\n";
        $xml .= "</rss>";

        return $xml;
    }

    /**
     * Format as Atom 1.0
     */
    public function formatAtomFeed(Collection $items, string $title, string $description, string $link, string $type = 'post'): string
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
        $xml .= "<feed xmlns=\"http://www.w3.org/2005/Atom\">\n";
        $xml .= "  <title>" . htmlspecialchars($title) . "</title>\n";
        $xml .= "  <link href=\"" . htmlspecialchars($link) . "\" rel=\"alternate\"/>\n";
        $xml .= "  <link href=\"" . htmlspecialchars(request()->url()) . "\" rel=\"self\"/>\n";
        $xml .= "  <id>urn:uuid:" . md5($link) . "</id>\n";
        $xml .= "  <updated>" . now()->toAtomString() . "</updated>\n";

        foreach ($items as $item) {
            $itemTitle = $type === 'post' ? $item->post_title : $item->full_name;
            $itemUrl = $type === 'post' ? route('public.post', $item->post_slug) : (
                $type === 'alumni' ? route('public.directory.alumni.profile', $item) : (
                    $type === 'student' ? route('public.directory.student.profile', $item) : route('public.directory.employee.profile', $item)
                )
            );
            $itemDate = $type === 'post' ? $item->created_at : $item->updated_at;

            if ($type === 'post') {
                $summary = strip_tags(substr($item->post_content, 0, 200)) . '...';
                $author = $item->author ? $item->author->name : config('app.name');
            } else {
                $summary = $type === 'alumni' ? ($item->major?->name ?? 'Alumni') :
                          ($type === 'student' ? ($item->major?->name ?? 'Student') :
                          ($item->employmentType?->name ?? 'Staff'));
                $author = $item->full_name;
            }

            $xml .= "  <entry>\n";
            $xml .= "    <title>" . htmlspecialchars($itemTitle) . "</title>\n";
            $xml .= "    <link href=\"" . htmlspecialchars($itemUrl) . "\" rel=\"alternate\"/>\n";
            $xml .= "    <id>urn:uuid:" . md5($itemUrl) . "</id>\n";
            $xml .= "    <published>" . $itemDate->toAtomString() . "</published>\n";
            $xml .= "    <updated>" . $itemDate->toAtomString() . "</updated>\n";
            $xml .= "    <author><name>" . htmlspecialchars($author) . "</name></author>\n";
            $xml .= "    <summary>" . htmlspecialchars($summary) . "</summary>\n";

            if ($type === 'post' && $item->post_content) {
                $xml .= "    <content type=\"html\"><![CDATA[" . $item->post_content . "]]></content>\n";
            }

            if ($type === 'post') {
                $categories = $this->extractCategories($item->post_categories);
                foreach ($categories as $cat) {
                    $xml .= "    <category term=\"" . htmlspecialchars(str_slug($cat)) . "\" label=\"" . htmlspecialchars($cat) . "\"/>\n";
                }
                $tags = $this->extractTags($item->post_tags);
                foreach ($tags as $t) {
                    $xml .= "    <category term=\"" . htmlspecialchars(str_slug($t)) . "\" label=\"" . htmlspecialchars($t) . "\"/>\n";
                }
            }

            $xml .= "  </entry>\n";
        }

        $xml .= "</feed>";

        return $xml;
    }

    /**
     * Format as JSON Feed 1.1
     */
    public function formatJsonFeed(Collection $items, string $title, string $description, string $type = 'post'): string
    {
        $feedItems = [];

        foreach ($items as $item) {
            $itemTitle = $type === 'post' ? $item->post_title : $item->full_name;
            $itemUrl = $type === 'post' ? route('public.post', $item->post_slug) : (
                $type === 'alumni' ? route('public.directory.alumni.profile', $item) : (
                    $type === 'student' ? route('public.directory.student.profile', $item) : route('public.directory.employee.profile', $item)
                )
            );
            $itemDate = $type === 'post' ? $item->created_at : $item->updated_at;

            if ($type === 'post') {
                $summary = strip_tags(substr($item->post_content, 0, 200)) . '...';
                $author = $item->author ? $item->author->name : config('app.name');
                $content = $item->post_content;
            } else {
                $summary = $type === 'alumni' ? ($item->major?->name ?? 'Alumni') :
                          ($type === 'student' ? ($item->major?->name ?? 'Student') :
                          ($item->employmentType?->name ?? 'Staff'));
                $author = $item->full_name;
                $content = null;
            }

            $feedItem = [
                'id' => $itemUrl,
                'url' => $itemUrl,
                'title' => $itemTitle,
                'summary' => $summary,
                'date_published' => $itemDate->toIso8601String(),
                'author' => ['name' => $author],
            ];

            if ($content && $type === 'post') {
                $feedItem['content_html'] = $content;
            }

            if ($type === 'post') {
                $tags = [];
                $categories = $this->extractCategories($item->post_categories);
                $tags = array_merge($tags, $categories);
                $itemTags = $this->extractTags($item->post_tags);
                $tags = array_merge($tags, $itemTags);
                if (!empty($tags)) {
                    $feedItem['tags'] = $tags;
                }
            }

            $feedItems[] = $feedItem;
        }

        $feed = [
            'version' => 'https://jsonfeed.org/version/1.1',
            'title' => $title,
            'home_page_url' => url('/'),
            'feed_url' => request()->url(),
            'description' => $description,
            'items' => $feedItems,
        ];

        return json_encode($feed, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Extract category names from comma-separated IDs
     */
    private function extractCategories(?string $categories): array
    {
        if (!$categories) {
            return [];
        }

        // Categories stored as "+id1+,+id2+" format
        $ids = array_filter(array_map('trim', str_replace('+', '', explode(',', $categories))));

        if (empty($ids)) {
            return [];
        }

        return Category::whereIn('id', $ids)->pluck('category_name')->toArray();
    }

    /**
     * Extract tags from space/comma-separated string
     */
    private function extractTags(?string $tags): array
    {
        if (!$tags) {
            return [];
        }

        // Tags typically space-separated or comma-separated
        $tagArray = array_filter(array_map('trim', preg_split('/[\s,]+/', $tags)));

        return array_values($tagArray);
    }
}
