<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassGroup;
use App\Models\Major;

class ClassGroupController extends Controller
{
    public function index()
    {
        $class_groups = ClassGroup::with('major')->get();
        $majors = Major::all();
        return view('backend.academic.class_groups', compact('class_groups', 'majors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_group' => 'required|string|max:255',
            'sub_class_group' => 'nullable|string|max:255',
            'major_id' => 'nullable|exists:majors,id'
        ]);

        ClassGroup::create($validated);

        return redirect()->back()->with('success', 'Class Group created successfully.');
    }

    public function update(Request $request, ClassGroup $class_group)
    {
        $validated = $request->validate([
            'class_group' => 'required|string|max:255',
            'sub_class_group' => 'nullable|string|max:255',
            'major_id' => 'nullable|exists:majors,id'
        ]);

        $class_group->update($validated);

        return redirect()->back()->with('success', 'Class Group updated successfully.');
    }

    public function destroy(ClassGroup $class_group)
    {
        $class_group->delete();
        return redirect()->back()->with('success', 'Class Group deleted successfully.');
    }
}
