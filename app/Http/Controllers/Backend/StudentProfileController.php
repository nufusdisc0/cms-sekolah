<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Option;
use Illuminate\Support\Facades\Auth;

class StudentProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->user_type !== 'student') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Anda bukan Siswa.');
        }

        $student = Student::find($user->user_profile_id);
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Data biodata siswa tidak ditemukan.');
        }

        $religions = Option::where('option_group', 'religion')->get();
        $special_needs = Option::where('option_group', 'special_needs')->get();
        $residences = Option::where('option_group', 'residence')->get();
        $transportations = Option::where('option_group', 'transportation')->get();
        $educations = Option::where('option_group', 'education')->get();
        $employments = Option::where('option_group', 'employment')->get();
        $monthly_incomes = Option::where('option_group', 'monthly_income')->get();

        return view('backend.users.student_profile', compact(
            'student', 'religions', 'special_needs', 'residences',
            'transportations', 'educations', 'employments', 'monthly_incomes'
        ));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if ($user->user_type !== 'student') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $student = Student::find($user->user_profile_id);
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Data biodata siswa tidak ditemukan.');
        }

        $validated = $request->validate([
            'email' => 'required|email|unique:students,email,' . $student->id,
            'father_birth_year' => 'nullable|integer|digits:4',
            'mother_birth_year' => 'nullable|integer|digits:4',
            'guardian_birth_year' => 'nullable|integer|digits:4',
            'sibling_number' => 'nullable|integer|min:0|max:99',
            'rt' => 'nullable|numeric',
            'rw' => 'nullable|numeric',
            'postal_code' => 'nullable|numeric',
            'mileage' => 'nullable|numeric',
            'traveling_time' => 'nullable|numeric',
            'height' => 'nullable|numeric|digits_between:2,3',
            'weight' => 'nullable|numeric|digits_between:2,3',
            // other fields are strings and integers generally
        ]);

        $data = $request->except(['_token', '_method']);
        // Sanitize or adjust fields if necessary

        $student->update($data);

        // Update User email as well
        if ($user->user_email !== $student->email && $student->email) {
            $user->update(['user_email' => $student->email]);
        }

        return redirect()->back()->with('success', 'Biodata berhasil diperbarui.');
    }
}
