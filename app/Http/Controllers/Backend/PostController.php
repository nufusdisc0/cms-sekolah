<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('post_type', 'post')->orderBy('created_at', 'desc')->get();
        $categories = Category::where('category_type', 'post')->get();
        return view('backend.blog.posts', compact('posts', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_title' => 'required|string|max:255',
            'post_content' => 'required|string',
            'post_categories' => 'nullable|string',
            'post_status' => 'required|in:publish,draft',
            'post_visibility' => 'required|in:public,private',
            'post_comment_status' => 'required|in:open,close',
            'post_tags' => 'nullable|string',
            'post_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120'
        ]);

        $data = [
            'post_title' => $validated['post_title'],
            'post_content' => $validated['post_content'],
            'post_author' => auth()->id(),
            'post_categories' => $validated['post_categories'] ?? null,
            'post_type' => 'post',
            'post_status' => $validated['post_status'],
            'post_visibility' => $validated['post_visibility'],
            'post_comment_status' => $validated['post_comment_status'],
            'post_slug' => Str::slug($validated['post_title']),
            'post_tags' => $validated['post_tags'] ?? null
        ];

        if ($request->hasFile('post_image')) {
            $data['post_image'] = $request->file('post_image')->store('posts', 'public');
        }

        Post::create($data);

        return redirect()->back()->with('success', 'Post created successfully.');
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'post_title' => 'required|string|max:255',
            'post_content' => 'required|string',
            'post_categories' => 'nullable|string',
            'post_status' => 'required|in:publish,draft',
            'post_visibility' => 'required|in:public,private',
            'post_comment_status' => 'required|in:open,close',
            'post_tags' => 'nullable|string',
            'post_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120'
        ]);

        $data = [
            'post_title' => $validated['post_title'],
            'post_content' => $validated['post_content'],
            'post_categories' => $validated['post_categories'] ?? null,
            'post_status' => $validated['post_status'],
            'post_visibility' => $validated['post_visibility'],
            'post_comment_status' => $validated['post_comment_status'],
            'post_slug' => Str::slug($validated['post_title']),
            'post_tags' => $validated['post_tags'] ?? null
        ];

        if ($request->hasFile('post_image')) {
            $data['post_image'] = $request->file('post_image')->store('posts', 'public');
        }

        $post->update($data);

        return redirect()->back()->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->back()->with('success', 'Post deleted successfully.');
    }
}
