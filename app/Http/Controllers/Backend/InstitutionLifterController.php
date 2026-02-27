<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;

class InstitutionLifterController extends Controller
{
    public function index()
    {
        $institution_lifters = Option::where('option_group', 'institution_lifter')->get();
        return view('backend.reference.institution_lifters', compact('institution_lifters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'option_name' => 'required|string|max:255'
        ]);

        Option::create([
            'option_group' => 'institution_lifter',
            'option_name' => $validated['option_name']
        ]);

        return redirect()->back()->with('success', 'Institution Lifter created successfully.');
    }

    public function update(Request $request, Option $institution_lifter)
    {
        if ($institution_lifter->option_group !== 'institution_lifter') {
            return redirect()->back()->with('error', 'Invalid option group.');
        }

        $validated = $request->validate([
            'option_name' => 'required|string|max:255'
        ]);

        $institution_lifter->update([
            'option_name' => $validated['option_name']
        ]);

        return redirect()->back()->with('success', 'Institution Lifter updated successfully.');
    }

    public function destroy(Option $institution_lifter)
    {
        if ($institution_lifter->option_group !== 'institution_lifter') {
            return redirect()->back()->with('error', 'Invalid option group.');
        }

        $institution_lifter->delete();
        return redirect()->back()->with('success', 'Institution Lifter deleted successfully.');
    }
}
