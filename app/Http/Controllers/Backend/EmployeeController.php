<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $users = User::where('user_type', 'employee')->get();
        return view('backend.users.employees', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        // For employees, the legacy system only allowed updating passwords
        if ($user->user_type !== 'employee') {
            return redirect()->back()->with('error', 'Invalid user type.');
        }

        $validated = $request->validate([
            'user_password' => 'required|min:6'
        ]);

        $user->update([
            'user_password' => Hash::make($validated['user_password'])
        ]);

        return redirect()->back()->with('success', 'Employee password updated successfully.');
    }
}
