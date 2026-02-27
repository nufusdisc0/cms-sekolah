<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;

class SalarySourceController extends Controller
{
    public function index()
    {
        $salary_sources = Option::where('option_group', 'salary_source')->get();
        return view('backend.reference.salary_sources', compact('salary_sources'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'option_name' => 'required|string|max:255'
        ]);

        Option::create([
            'option_group' => 'salary_source',
            'option_name' => $validated['option_name']
        ]);

        return redirect()->back()->with('success', 'Salary Source created successfully.');
    }

    public function update(Request $request, Option $salary_source)
    {
        if ($salary_source->option_group !== 'salary_source') {
            return redirect()->back()->with('error', 'Invalid option group.');
        }

        $validated = $request->validate([
            'option_name' => 'required|string|max:255'
        ]);

        $salary_source->update([
            'option_name' => $validated['option_name']
        ]);

        return redirect()->back()->with('success', 'Salary Source updated successfully.');
    }

    public function destroy(Option $salary_source)
    {
        if ($salary_source->option_group !== 'salary_source') {
            return redirect()->back()->with('error', 'Invalid option group.');
        }

        $salary_source->delete();
        return redirect()->back()->with('success', 'Salary Source deleted successfully.');
    }
}
