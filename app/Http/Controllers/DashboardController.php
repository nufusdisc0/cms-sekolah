<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Link;
use App\Models\Quote;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $widget_box = (object)[
            'messages' => Comment::where('comment_type', 'message')->count(),
            'comments' => Comment::where('comment_type', 'post')->count(),
            'posts' => Post::where('post_type', 'post')->count(),
            'pages' => Post::where('post_type', 'page')->count(),
            'categories' => Category::where('category_type', 'post')->count(),
            'tags' => Tag::count(),
            'links' => Link::where('link_type', 'link')->count(),
            'quotes' => Quote::count(),
        ];

        $recent_comments = Comment::where('comment_type', 'post')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('backend.dashboard.index', compact('widget_box', 'recent_comments'));
    }
}
