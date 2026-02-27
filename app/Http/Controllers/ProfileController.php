<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('backend.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'user_full_name' => 'required|string|max:100',
            'user_email' => ['required', 'email', 'max:100', Rule::unique('users')->ignore($user->id)],
            'user_url' => 'nullable|url|max:100',
            'user_biography' => 'nullable|string',
        ]);

        $user->update($validated);

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
    }

    public function changePassword()
    {
        return view('backend.profile.change_password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->update([
            'user_password' => \Illuminate\Support\Facades\Hash::make($request->new_password)
        ]);

        return redirect()->back()->with('success', 'Password changed successfully.');
    }
}
