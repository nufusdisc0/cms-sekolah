<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Major;

class MajorController extends Controller
{
    public function index()
    {
        $majors = Major::all();
        return view('backend.academic.majors', compact('majors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'major_name' => 'required|string|max:255',
            'major_short_name' => 'required|string|max:50',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Major::create($validated);

        return redirect()->back()->with('success', 'Major created successfully.');
    }

    public function update(Request $request, Major $major)
    {
        $validated = $request->validate([
            'major_name' => 'required|string|max:255',
            'major_short_name' => 'required|string|max:50',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $major->update($validated);

        return redirect()->back()->with('success', 'Major updated successfully.');
    }

    public function destroy(Major $major)
    {
        $major->delete();
        return redirect()->back()->with('success', 'Major deleted successfully.');
    }
}
