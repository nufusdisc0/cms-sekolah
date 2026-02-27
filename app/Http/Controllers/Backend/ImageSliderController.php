<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ImageSlider;

class ImageSliderController extends Controller
{
    public function index()
    {
        $sliders = ImageSlider::orderBy('id', 'desc')->get();
        return view('backend.blog.image_sliders', compact('sliders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'caption' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:5120',
            'is_active' => 'required|in:true,false'
        ]);

        $data = [
            'caption' => $validated['caption'],
            'is_active' => $validated['is_active']
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('sliders', 'public');
        }

        ImageSlider::create($data);
        return redirect()->back()->with('success', 'Image Slider created successfully.');
    }

    public function update(Request $request, ImageSlider $image_slider)
    {
        $validated = $request->validate([
            'caption' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
            'is_active' => 'required|in:true,false'
        ]);

        $data = [
            'caption' => $validated['caption'],
            'is_active' => $validated['is_active']
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('sliders', 'public');
        }

        $image_slider->update($data);
        return redirect()->back()->with('success', 'Image Slider updated successfully.');
    }

    public function destroy(ImageSlider $image_slider)
    {
        $image_slider->delete();
        return redirect()->back()->with('success', 'Image Slider deleted successfully.');
    }
}
