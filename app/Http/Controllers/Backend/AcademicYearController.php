<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicYear;

class AcademicYearController extends Controller
{
    public function index()
    {
        $academic_years = AcademicYear::orderBy('academic_year', 'desc')->orderBy('semester', 'asc')->get();
        return view('backend.academic.academic_years', compact('academic_years'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => ['required', 'string', 'size:9', 'regex:/^\d{4}-\d{4}$/'],
            'semester' => 'required|in:1,2,3',
            'current_semester' => 'boolean',
            'admission_semester' => 'boolean'
        ]);

        $current = $request->boolean('current_semester');
        $admission = $request->boolean('admission_semester');

        if ($current)
            AcademicYear::where('current_semester', true)->update(['current_semester' => false]);
        if ($admission)
            AcademicYear::where('admission_semester', true)->update(['admission_semester' => false]);

        $validated['current_semester'] = $current;
        $validated['admission_semester'] = $admission;

        AcademicYear::create($validated);

        return redirect()->back()->with('success', 'Academic Year created successfully.');
    }

    public function update(Request $request, AcademicYear $academic_year)
    {
        $validated = $request->validate([
            'academic_year' => ['required', 'string', 'size:9', 'regex:/^\d{4}-\d{4}$/'],
            'semester' => 'required|in:1,2,3',
            'current_semester' => 'boolean',
            'admission_semester' => 'boolean'
        ]);

        $current = $request->boolean('current_semester');
        $admission = $request->boolean('admission_semester');

        if ($current && !$academic_year->current_semester) {
            AcademicYear::where('current_semester', true)->update(['current_semester' => false]);
        }
        if ($admission && !$academic_year->admission_semester) {
            AcademicYear::where('admission_semester', true)->update(['admission_semester' => false]);
        }

        $validated['current_semester'] = $current;
        $validated['admission_semester'] = $admission;

        $academic_year->update($validated);

        return redirect()->back()->with('success', 'Academic Year updated successfully.');
    }

    public function destroy(AcademicYear $academic_year)
    {
        if ($academic_year->current_semester || $academic_year->admission_semester) {
            return redirect()->back()->with('error', 'Cannot delete an active or admission semester. Change the active semester first.');
        }

        $academic_year->delete();
        return redirect()->back()->with('success', 'Academic Year deleted successfully.');
    }
}
