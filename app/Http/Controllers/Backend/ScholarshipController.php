<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Scholarship;
use Illuminate\Support\Facades\Auth;

class ScholarshipController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->user_type !== 'student') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Anda bukan Siswa.');
        }

        $scholarships = Scholarship::where('student_id', $user->user_profile_id)->orderBy('scholarship_start_year', 'desc')->get();
        return view('backend.users.scholarships', compact('scholarships'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->user_type !== 'student') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $validated = $request->validate([
            'scholarship_description' => 'required|string|max:255',
            'scholarship_type' => 'required|integer',
            'scholarship_start_year' => 'required|integer|digits:4',
            'scholarship_end_year' => 'required|integer|digits:4'
        ]);

        $validated['student_id'] = $user->user_profile_id;
        $validated['created_by'] = $user->id;

        Scholarship::create($validated);

        return redirect()->back()->with('success', 'Beasiswa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->user_type !== 'student') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $scholarship = Scholarship::where('student_id', $user->user_profile_id)->findOrFail($id);

        $validated = $request->validate([
            'scholarship_description' => 'required|string|max:255',
            'scholarship_type' => 'required|integer',
            'scholarship_start_year' => 'required|integer|digits:4',
            'scholarship_end_year' => 'required|integer|digits:4'
        ]);

        $validated['updated_by'] = $user->id;

        $scholarship->update($validated);

        return redirect()->back()->with('success', 'Beasiswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $scholarship = Scholarship::where('student_id', $user->user_profile_id)->findOrFail($id);
        $scholarship->delete();

        return redirect()->back()->with('success', 'Beasiswa berhasil dihapus.');
    }
}
