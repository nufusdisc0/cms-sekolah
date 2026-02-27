<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdmissionQuota;
use App\Models\AcademicYear;
use App\Models\Major;

class AdmissionQuotaController extends Controller
{
    public function index()
    {
        $quotas = AdmissionQuota::orderBy('id', 'desc')->get();
        $academic_years = AcademicYear::orderBy('academic_year', 'desc')->get();
        $majors = Major::where('is_active', 'true')->get();
        return view('backend.admission.quotas', compact('quotas', 'academic_years', 'majors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'major_id' => 'nullable|exists:majors,id',
            'quota' => 'required|integer|min:1'
        ]);

        AdmissionQuota::create($validated);
        return redirect()->back()->with('success', 'Admission Quota created successfully.');
    }

    public function update(Request $request, AdmissionQuota $admission_quota)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'major_id' => 'nullable|exists:majors,id',
            'quota' => 'required|integer|min:1'
        ]);

        $admission_quota->update($validated);
        return redirect()->back()->with('success', 'Admission Quota updated successfully.');
    }

    public function destroy(AdmissionQuota $admission_quota)
    {
        $admission_quota->delete();
        return redirect()->back()->with('success', 'Admission Quota deleted successfully.');
    }
}
