<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Link;

class LinkController extends Controller
{
    public function index()
    {
        $links = Link::orderBy('id', 'desc')->get();
        return view('backend.blog.links', compact('links'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'link_title' => 'required|string|max:255',
            'link_url' => 'required|url|max:500',
            'link_target' => 'required|in:_blank,_self',
            'link_type' => 'nullable|string|max:50',
            'is_active' => 'required|in:true,false'
        ]);

        Link::create($validated);
        return redirect()->back()->with('success', 'Link created successfully.');
    }

    public function update(Request $request, Link $link)
    {
        $validated = $request->validate([
            'link_title' => 'required|string|max:255',
            'link_url' => 'required|url|max:500',
            'link_target' => 'required|in:_blank,_self',
            'link_type' => 'nullable|string|max:50',
            'is_active' => 'required|in:true,false'
        ]);

        $link->update($validated);
        return redirect()->back()->with('success', 'Link updated successfully.');
    }

    public function destroy(Link $link)
    {
        $link->delete();
        return redirect()->back()->with('success', 'Link deleted successfully.');
    }
}
