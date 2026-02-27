<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;

class AlumniController extends Controller
{
    public function index()
    {
        $alumni = Student::whereIn('is_alumni', ['true', 'unverified'])
            ->orderBy('end_date', 'desc')
            ->get();

        return view('backend.academic.alumni', compact('alumni'));
    }

    public function update(Request $request, Student $alumnus)
    {
        // Ensure student is actually an alumni record
        if (!in_array($alumnus->is_alumni, ['true', 'unverified'])) {
            return redirect()->back()->with('error', 'Invalid record type.');
        }

        $validated = $request->validate([
            'is_alumni' => 'required|in:true,false,unverified',
            'identity_number' => 'nullable|string|max:50',
            'full_name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'end_date' => 'nullable|date',
        ]);

        $alumnus->update($validated);

        return redirect()->back()->with('success', 'Alumni data updated successfully.');
    }
}
