<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdmissionPhase;
use App\Models\AcademicYear;

class AdmissionPhaseController extends Controller
{
    public function index()
    {
        $phases = AdmissionPhase::orderBy('id', 'desc')->get();
        $academic_years = AcademicYear::orderBy('academic_year', 'desc')->get();
        return view('backend.admission.phases', compact('phases', 'academic_years'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'phase_name' => 'required|string|max:255',
            'phase_start_date' => 'required|date',
            'phase_end_date' => 'required|date|after_or_equal:phase_start_date'
        ]);

        AdmissionPhase::create($validated);
        return redirect()->back()->with('success', 'Admission Phase created successfully.');
    }

    public function update(Request $request, AdmissionPhase $admission_phase)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'phase_name' => 'required|string|max:255',
            'phase_start_date' => 'required|date',
            'phase_end_date' => 'required|date|after_or_equal:phase_start_date'
        ]);

        $admission_phase->update($validated);
        return redirect()->back()->with('success', 'Admission Phase updated successfully.');
    }

    public function destroy(AdmissionPhase $admission_phase)
    {
        $admission_phase->delete();
        return redirect()->back()->with('success', 'Admission Phase deleted successfully.');
    }
}
