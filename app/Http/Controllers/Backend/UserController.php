<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $currentUser = \Illuminate\Support\Facades\Auth::user();

        // Only show super_user if the current user is a super_user
        if ($currentUser->user_type === 'super_user') {
            $users = User::whereIn('user_type', ['super_user', 'administrator'])->get();
        }
        else {
            $users = User::where('user_type', 'administrator')->get();
        }

        $user_groups = UserGroup::all();

        return view('backend.users.administrator', compact('users', 'user_groups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_name' => 'required|unique:users,user_name',
            'user_password' => 'required|min:6',
            'user_full_name' => 'required',
            'user_email' => 'required|email|unique:users,user_email',
            'user_url' => 'nullable|url',
            'user_group_id' => 'required',
            'user_biography' => 'nullable'
        ]);

        $validated['user_password'] = Hash::make($validated['user_password']);
        $validated['user_type'] = 'administrator';

        User::create($validated);

        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'user_name' => ['required', Rule::unique('users')->ignore($user->id)],
            'user_password' => 'nullable|min:6',
            'user_full_name' => 'required',
            'user_email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'user_url' => 'nullable|url',
            'user_group_id' => 'required',
            'user_biography' => 'nullable'
        ]);

        if (!empty($validated['user_password'])) {
            $validated['user_password'] = Hash::make($validated['user_password']);
        }
        else {
            unset($validated['user_password']);
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->user_type == 'super_user') {
            return redirect()->back()->with('error', 'Cannot delete a super user.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
