<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Major;

class AcademicStudentController extends Controller
{
    public function index()
    {
        $students = Student::where('is_student', 'true')->with('major')->orderBy('full_name', 'asc')->get();
        $majors = Major::all();

        return view('backend.academic.students', compact('students', 'majors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'identity_number' => 'required|string|max:50|unique:students,identity_number',
            'nisn' => 'nullable|string|max:50',
            'full_name' => 'required|string|max:150',
            'gender' => 'required|in:M,F',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'major_id' => 'nullable|exists:majors,id',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150|unique:students,email'
        ]);

        $validated['is_student'] = 'true';
        $validated['is_prospective_student'] = 'false';

        Student::create($validated);

        return redirect()->back()->with('success', 'Student data created successfully.');
    }

    public function update(Request $request, Student $academic_student)
    {
        $validated = $request->validate([
            'identity_number' => 'required|string|max:50|unique:students,identity_number,' . $academic_student->id,
            'nisn' => 'nullable|string|max:50',
            'full_name' => 'required|string|max:150',
            'gender' => 'required|in:M,F',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'major_id' => 'nullable|exists:majors,id',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150|unique:students,email,' . $academic_student->id
        ]);

        $academic_student->update($validated);

        return redirect()->back()->with('success', 'Student data updated successfully.');
    }

    public function destroy(Student $academic_student)
    {
        $academic_student->delete();
        return redirect()->back()->with('success', 'Student deleted successfully.');
    }
}
