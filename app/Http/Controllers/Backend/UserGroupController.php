<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserGroup;

class UserGroupController extends Controller
{
    public function index()
    {
        $user_groups = UserGroup::all();
        return view('backend.users.user_groups', compact('user_groups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_group' => 'required|string|max:255'
        ]);

        UserGroup::create($validated);

        return redirect()->back()->with('success', 'User Group created successfully.');
    }

    public function update(Request $request, UserGroup $user_group)
    {
        $validated = $request->validate([
            'user_group' => 'required|string|max:255'
        ]);

        $user_group->update($validated);

        return redirect()->back()->with('success', 'User Group updated successfully.');
    }

    public function destroy(UserGroup $user_group)
    {
        // Don't allow deletion if users exist with this group or if it's protected
        if ($user_group->users()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete group because users are assigned to it.');
        }

        $user_group->delete();
        return redirect()->back()->with('success', 'User Group deleted successfully.');
    }
}
