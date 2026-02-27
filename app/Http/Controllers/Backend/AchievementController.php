<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Achievement;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->user_type !== 'student') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Anda bukan Siswa.');
        }

        $achievements = Achievement::where('student_id', $user->user_profile_id)->orderBy('achievement_year', 'desc')->get();
        return view('backend.users.achievements', compact('achievements'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->user_type !== 'student') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $validated = $request->validate([
            'achievement_description' => 'required|string|max:255',
            'achievement_type' => 'required|integer',
            'achievement_level' => 'required|integer',
            'achievement_year' => 'required|integer|digits:4',
            'achievement_organizer' => 'nullable|string|max:255'
        ]);

        $validated['student_id'] = $user->user_profile_id;
        $validated['created_by'] = $user->id;

        Achievement::create($validated);

        return redirect()->back()->with('success', 'Prestasi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->user_type !== 'student') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $achievement = Achievement::where('student_id', $user->user_profile_id)->findOrFail($id);

        $validated = $request->validate([
            'achievement_description' => 'required|string|max:255',
            'achievement_type' => 'required|integer',
            'achievement_level' => 'required|integer',
            'achievement_year' => 'required|integer|digits:4',
            'achievement_organizer' => 'nullable|string|max:255'
        ]);

        $validated['updated_by'] = $user->id;

        $achievement->update($validated);

        return redirect()->back()->with('success', 'Prestasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $achievement = Achievement::where('student_id', $user->user_profile_id)->findOrFail($id);
        $achievement->delete();

        return redirect()->back()->with('success', 'Prestasi berhasil dihapus.');
    }
}
