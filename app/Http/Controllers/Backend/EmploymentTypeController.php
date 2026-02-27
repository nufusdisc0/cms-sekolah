<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;

class EmploymentTypeController extends Controller
{
    public function index()
    {
        $employment_types = Option::where('option_group', 'employment_type')->get();
        return view('backend.reference.employment_types', compact('employment_types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'option_name' => 'required|string|max:255'
        ]);

        Option::create([
            'option_group' => 'employment_type',
            'option_name' => $validated['option_name']
        ]);

        return redirect()->back()->with('success', 'Employment Type created successfully.');
    }

    public function update(Request $request, Option $employment_type)
    {
        if ($employment_type->option_group !== 'employment_type') {
            return redirect()->back()->with('error', 'Invalid option group.');
        }

        $validated = $request->validate([
            'option_name' => 'required|string|max:255'
        ]);

        $employment_type->update([
            'option_name' => $validated['option_name']
        ]);

        return redirect()->back()->with('success', 'Employment Type updated successfully.');
    }

    public function destroy(Option $employment_type)
    {
        if ($employment_type->option_group !== 'employment_type') {
            return redirect()->back()->with('error', 'Invalid option group.');
        }

        $employment_type->delete();
        return redirect()->back()->with('success', 'Employment Type deleted successfully.');
    }
}
