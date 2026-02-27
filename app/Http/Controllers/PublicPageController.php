<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Setting;
use App\Models\ImageSlider;
use App\Models\Link;
use App\Models\Quote;
use App\Models\Category;

use App\Models\Album;
use App\Models\Video;
use App\Models\Option;
use App\Models\Question;
use App\Models\Answer;

class PublicPageController extends Controller
{
    public function home()
    {
        // MAIN CONTENT
        $posts = Post::where('post_type', 'post')
            ->where('post_status', 'publish')
            ->where('post_visibility', 'public')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $albums = Album::orderBy('created_at', 'desc')->take(4)->get();
        $videos = Video::orderBy('created_at', 'desc')->take(2)->get();
        $sliders = ImageSlider::all();
        $quotes = Quote::all();

        // SIDEBAR
        $headmaster = Setting::where('setting_variable', 'headmaster')->value('setting_value')
            ?? Option::where('option_name', 'headmaster')->value('option_name');
        $headmaster_photo = Setting::where('setting_variable', 'headmaster_photo')->value('setting_value');
        $opening_speech = Post::where('post_type', 'opening_speech')->first()
            ?? Post::where('post_type', 'page')->where('post_slug', 'sambutan-kepala-sekolah')->first();

        $links = Link::where('is_active', 'true')->where('link_type', 'link')->get();
        $banners = Link::where('is_active', 'true')->where('link_type', 'banner')->get();

        $most_commented = Post::where('post_type', 'post')
            ->where('post_status', 'publish')
            ->orderBy('post_counter', 'desc')
            ->take(5)
            ->get();

        $active_question = Question::where('is_active', 'true')->first();
        $answers = $active_question ?Answer::where('question_id', $active_question->id)->get() : [];

        // Archives - monthly post counts for current year
        $archives = Post::where('post_type', 'post')
            ->where('post_status', 'publish')
            ->whereYear('created_at', date('Y'))
            ->selectRaw('CAST(strftime("%m", created_at) AS INTEGER) as month_num, COUNT(*) as count')
            ->groupByRaw('strftime("%m", created_at)')
            ->orderByRaw('strftime("%m", created_at)')
            ->get();

        return view('public.home', compact(
            'posts', 'albums', 'videos', 'sliders', 'quotes',
            'headmaster', 'headmaster_photo', 'opening_speech',
            'links', 'banners', 'most_commented', 'active_question', 'answers',
            'archives'
        ));
    }

    public function post($slug)
    {
        $post = Post::where('post_slug', $slug)
            ->where('post_type', 'post')
            ->where('post_status', 'publish')
            ->firstOrFail();

        return view('public.post', compact('post'));
    }

    public function page($slug)
    {
        $page = Post::where('post_slug', $slug)
            ->where('post_type', 'page')
            ->where('post_status', 'publish')
            ->firstOrFail();

        return view('public.page', compact('page'));
    }

    public function category($slug)
    {
        $category = Category::where('category_slug', $slug)->firstOrFail();
        $posts = Post::where('post_type', 'post')
            ->where('post_status', 'publish')
            ->where('post_categories', $category->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('public.category', compact('category', 'posts'));
    }

    public function tag($slug)
    {
        $tag = \App\Models\Tag::where('slug', $slug)->firstOrFail();
        $posts = Post::where('post_type', 'post')
            ->where('post_status', 'publish')
            ->where('post_tags', 'LIKE', '%' . $tag->tag . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('public.tag', compact('tag', 'posts'));
    }

    public function contact()
    {
        return view('public.contact');
    }
}
