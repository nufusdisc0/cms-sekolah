<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Photo;
use App\Models\Album;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function index(Request $request)
    {
        $album_id = $request->get('album_id');
        $query = Photo::query();

        if ($album_id) {
            $query->where('photo_album_id', $album_id);
            $album = Album::findOrFail($album_id);
        }
        else {
            $album = null;
        }

        $photos = $query->orderBy('created_at', 'desc')->get();
        $albums = Album::orderBy('album_title', 'asc')->get();

        return view('backend.media.photos', compact('photos', 'albums', 'album', 'album_id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'photo_album_id' => 'required|exists:albums,id',
            'photo_name' => 'required|image|mimes:jpg,jpeg,png,gif|max:5120'
        ]);

        $data = [
            'photo_album_id' => $validated['photo_album_id']
        ];

        if ($request->hasFile('photo_name')) {
            $data['photo_name'] = $request->file('photo_name')->store('photos', 'public');
        }

        Photo::create($data);

        return redirect()->back()->with('success', 'Photo uploaded successfully.');
    }

    public function destroy(Photo $photo)
    {
        if ($photo->photo_name && Storage::disk('public')->exists($photo->photo_name)) {
            Storage::disk('public')->delete($photo->photo_name);
        }
        $photo->delete();
        return redirect()->back()->with('success', 'Photo deleted successfully.');
    }
}
