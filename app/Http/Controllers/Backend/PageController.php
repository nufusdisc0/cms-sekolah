<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Post::where('post_type', 'page')->orderBy('created_at', 'desc')->get();
        return view('backend.blog.pages', compact('pages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_title' => 'required|string|max:255',
            'post_content' => 'required|string',
            'post_status' => 'required|in:publish,draft',
            'post_visibility' => 'required|in:public,private',
            'post_comment_status' => 'required|in:open,close'
        ]);

        Post::create([
            'post_title' => $validated['post_title'],
            'post_content' => $validated['post_content'],
            'post_author' => auth()->id(),
            'post_type' => 'page',
            'post_status' => $validated['post_status'],
            'post_visibility' => $validated['post_visibility'],
            'post_comment_status' => $validated['post_comment_status'],
            'post_slug' => Str::slug($validated['post_title'])
        ]);

        return redirect()->back()->with('success', 'Page created successfully.');
    }

    public function update(Request $request, Post $page)
    {
        $validated = $request->validate([
            'post_title' => 'required|string|max:255',
            'post_content' => 'required|string',
            'post_status' => 'required|in:publish,draft',
            'post_visibility' => 'required|in:public,private',
            'post_comment_status' => 'required|in:open,close'
        ]);

        $page->update([
            'post_title' => $validated['post_title'],
            'post_content' => $validated['post_content'],
            'post_status' => $validated['post_status'],
            'post_visibility' => $validated['post_visibility'],
            'post_comment_status' => $validated['post_comment_status'],
            'post_slug' => Str::slug($validated['post_title'])
        ]);

        return redirect()->back()->with('success', 'Page updated successfully.');
    }

    public function destroy(Post $page)
    {
        $page->delete();
        return redirect()->back()->with('success', 'Page deleted successfully.');
    }
}
