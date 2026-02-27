<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Album;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    public function index()
    {
        $albums = Album::orderBy('created_at', 'desc')->get();
        return view('backend.media.albums', compact('albums'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'album_title' => 'required|string|max:255',
            'album_description' => 'nullable|string|max:1000',
            'album_cover' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120'
        ]);

        $data = [
            'album_title' => $validated['album_title'],
            'album_description' => $validated['album_description'] ?? null,
            'album_slug' => Str::slug($validated['album_title'])
        ];

        if ($request->hasFile('album_cover')) {
            $data['album_cover'] = $request->file('album_cover')->store('albums', 'public');
        }

        Album::create($data);

        return redirect()->back()->with('success', 'Album created successfully.');
    }

    public function update(Request $request, Album $album)
    {
        $validated = $request->validate([
            'album_title' => 'required|string|max:255',
            'album_description' => 'nullable|string|max:1000',
            'album_cover' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120'
        ]);

        $data = [
            'album_title' => $validated['album_title'],
            'album_description' => $validated['album_description'] ?? null,
            'album_slug' => Str::slug($validated['album_title'])
        ];

        if ($request->hasFile('album_cover')) {
            if ($album->album_cover && Storage::disk('public')->exists($album->album_cover)) {
                Storage::disk('public')->delete($album->album_cover);
            }
            $data['album_cover'] = $request->file('album_cover')->store('albums', 'public');
        }

        $album->update($data);

        return redirect()->back()->with('success', 'Album updated successfully.');
    }

    public function destroy(Album $album)
    {
        if ($album->album_cover && Storage::disk('public')->exists($album->album_cover)) {
            Storage::disk('public')->delete($album->album_cover);
        }
        $album->delete();
        return redirect()->back()->with('success', 'Album deleted successfully.');
    }
}
