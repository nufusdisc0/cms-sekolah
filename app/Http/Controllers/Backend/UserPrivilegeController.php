<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserGroup;
use App\Models\Module;
use App\Models\UserPrivilege;

class UserPrivilegeController extends Controller
{
    public function index()
    {
        $user_privileges = UserPrivilege::with(['userGroup', 'module'])->get();
        $user_groups = UserGroup::all();
        $modules = Module::all();

        return view('backend.users.user_privileges', compact('user_privileges', 'user_groups', 'modules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_group_id' => 'required|exists:user_groups,id',
            'module_id' => 'required|exists:modules,id'
        ]);

        // Check if privilege already exists
        $exists = UserPrivilege::where('user_group_id', $validated['user_group_id'])
            ->where('module_id', $validated['module_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'This privilege is already assigned to the select group.');
        }

        UserPrivilege::create($validated);

        return redirect()->back()->with('success', 'Privilege assigned successfully.');
    }

    public function destroy(UserPrivilege $user_privilege)
    {
        $user_privilege->delete();
        return redirect()->back()->with('success', 'Privilege revoked successfully.');
    }
}
