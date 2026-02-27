<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;

class EmploymentStatusController extends Controller
{
    public function index()
    {
        $employment_statuses = Option::where('option_group', 'employment_status')->get();
        return view('backend.reference.employment_statuses', compact('employment_statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'option_name' => 'required|string|max:255'
        ]);

        Option::create([
            'option_group' => 'employment_status',
            'option_name' => $validated['option_name']
        ]);

        return redirect()->back()->with('success', 'Employment Status created successfully.');
    }

    public function update(Request $request, Option $employment_status)
    {
        if ($employment_status->option_group !== 'employment_status') {
            return redirect()->back()->with('error', 'Invalid option group.');
        }

        $validated = $request->validate([
            'option_name' => 'required|string|max:255'
        ]);

        $employment_status->update([
            'option_name' => $validated['option_name']
        ]);

        return redirect()->back()->with('success', 'Employment Status updated successfully.');
    }

    public function destroy(Option $employment_status)
    {
        if ($employment_status->option_group !== 'employment_status') {
            return redirect()->back()->with('error', 'Invalid option group.');
        }

        $employment_status->delete();
        return redirect()->back()->with('success', 'Employment Status deleted successfully.');
    }
}
