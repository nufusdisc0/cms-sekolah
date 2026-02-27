<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('tag')->get();
        return view('backend.blog.tags', compact('tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tag' => 'required|string|max:255|unique:tags,tag'
        ]);

        Tag::create([
            'tag' => $validated['tag'],
            'slug' => Str::slug($validated['tag'])
        ]);

        return redirect()->back()->with('success', 'Tag created successfully.');
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'tag' => 'required|string|max:255|unique:tags,tag,' . $tag->id
        ]);

        $tag->update([
            'tag' => $validated['tag'],
            'slug' => Str::slug($validated['tag'])
        ]);

        return redirect()->back()->with('success', 'Tag updated successfully.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->back()->with('success', 'Tag deleted successfully.');
    }
}
