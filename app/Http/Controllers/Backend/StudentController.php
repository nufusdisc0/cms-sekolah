<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index()
    {
        $users = User::where('user_type', 'student')->get();
        return view('backend.users.students', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        // For students, the legacy system only allowed updating passwords
        if ($user->user_type !== 'student') {
            return redirect()->back()->with('error', 'Invalid user type.');
        }

        $validated = $request->validate([
            'user_password' => 'required|min:6'
        ]);

        $user->update([
            'user_password' => Hash::make($validated['user_password'])
        ]);

        return redirect()->back()->with('success', 'Student password updated successfully.');
    }
}
