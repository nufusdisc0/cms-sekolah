<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;

class EducationController extends Controller
{
    public function index()
    {
        $educations = Option::where('option_group', 'education')->get();
        return view('backend.reference.educations', compact('educations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'option_name' => 'required|string|max:255'
        ]);

        Option::create([
            'option_group' => 'education',
            'option_name' => $validated['option_name']
        ]);

        return redirect()->back()->with('success', 'Education created successfully.');
    }

    public function update(Request $request, Option $education)
    {
        if ($education->option_group !== 'education') {
            return redirect()->back()->with('error', 'Invalid option group.');
        }

        $validated = $request->validate([
            'option_name' => 'required|string|max:255'
        ]);

        $education->update([
            'option_name' => $validated['option_name']
        ]);

        return redirect()->back()->with('success', 'Education updated successfully.');
    }

    public function destroy(Option $education)
    {
        if ($education->option_group !== 'education') {
            return redirect()->back()->with('error', 'Invalid option group.');
        }

        $education->delete();
        return redirect()->back()->with('success', 'Education deleted successfully.');
    }
}
