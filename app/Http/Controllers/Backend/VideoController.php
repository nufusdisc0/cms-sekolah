<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Post::where('post_type', 'video')->orderBy('created_at', 'desc')->get();
        return view('backend.media.videos', compact('videos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_title' => 'required|string|max:255',
            'post_content' => 'required|string|max:1000' // YouTube URL or ID
        ]);

        Post::create([
            'post_title' => $validated['post_title'],
            'post_content' => $validated['post_content'],
            'post_type' => 'video',
            'post_author' => auth()->id(),
            'post_status' => 'publish',
            'post_visibility' => 'public',
            'post_comment_status' => 'close'
        ]);

        return redirect()->back()->with('success', 'Video added successfully.');
    }

    public function update(Request $request, $id)
    {
        $video = Post::where('post_type', 'video')->findOrFail($id);

        $validated = $request->validate([
            'post_title' => 'required|string|max:255',
            'post_content' => 'required|string|max:1000'
        ]);

        $video->update([
            'post_title' => $validated['post_title'],
            'post_content' => $validated['post_content']
        ]);

        return redirect()->back()->with('success', 'Video updated successfully.');
    }

    public function destroy($id)
    {
        $video = Post::where('post_type', 'video')->findOrFail($id);
        $video->delete();
        return redirect()->back()->with('success', 'Video deleted successfully.');
    }
}
