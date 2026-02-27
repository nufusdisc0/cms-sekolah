<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Link;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Link::where('link_type', 'banner')->latest()->get();
        return view('backend.plugins.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'link_title' => 'required',
            'link_url' => 'required|url',
            'link_target' => 'required|in:_blank,_self,_parent,_top',
            'link_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $validated['link_type'] = 'banner';

        if ($request->hasFile('link_image')) {
            $path = $request->file('link_image')->store('images/banners', 'public');
            $validated['link_image'] = $path;
        }

        Link::create($validated);

        return redirect()->back()->with('success', 'Banner created successfully.');
    }

    public function update(Request $request, Link $banner)
    {
        if ($banner->link_type !== 'banner') {
            abort(404);
        }

        $validated = $request->validate([
            'link_title' => 'required',
            'link_url' => 'required|url',
            'link_target' => 'required|in:_blank,_self,_parent,_top',
            'link_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('link_image')) {
            // Delete old image
            if ($banner->link_image && Storage::disk('public')->exists($banner->link_image)) {
                Storage::disk('public')->delete($banner->link_image);
            }
            $path = $request->file('link_image')->store('images/banners', 'public');
            $validated['link_image'] = $path;
        }

        $banner->update($validated);

        return redirect()->back()->with('success', 'Banner updated successfully.');
    }

    public function destroy(Link $banner)
    {
        if ($banner->link_type !== 'banner') {
            abort(404);
        }

        if ($banner->link_image && Storage::disk('public')->exists($banner->link_image)) {
            Storage::disk('public')->delete($banner->link_image);
        }

        $banner->delete();

        return redirect()->back()->with('success', 'Banner deleted successfully.');
    }
}
