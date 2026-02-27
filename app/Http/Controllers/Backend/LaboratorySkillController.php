<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;

class LaboratorySkillController extends Controller
{
    public function index()
    {
        $laboratory_skills = Option::where('option_group', 'laboratory_skill')->get();
        return view('backend.reference.laboratory_skills', compact('laboratory_skills'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'option_name' => 'required|string|max:255'
        ]);

        Option::create([
            'option_group' => 'laboratory_skill',
            'option_name' => $validated['option_name']
        ]);

        return redirect()->back()->with('success', 'Laboratory Skill created successfully.');
    }

    public function update(Request $request, Option $laboratory_skill)
    {
        if ($laboratory_skill->option_group !== 'laboratory_skill') {
            return redirect()->back()->with('error', 'Invalid option group.');
        }

        $validated = $request->validate([
            'option_name' => 'required|string|max:255'
        ]);

        $laboratory_skill->update([
            'option_name' => $validated['option_name']
        ]);

        return redirect()->back()->with('success', 'Laboratory Skill updated successfully.');
    }

    public function destroy(Option $laboratory_skill)
    {
        if ($laboratory_skill->option_group !== 'laboratory_skill') {
            return redirect()->back()->with('error', 'Invalid option group.');
        }

        $laboratory_skill->delete();
        return redirect()->back()->with('success', 'Laboratory Skill deleted successfully.');
    }
}
