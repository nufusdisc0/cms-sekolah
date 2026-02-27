<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Option;

class AcademicEmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy('full_name')->get();

        // Get options for dropdowns
        $employment_statuses = Option::where('option_group', 'employment_status')->get();
        $employment_types = Option::where('option_group', 'employment_type')->get();

        return view('backend.employees.index', compact('employees', 'employment_statuses', 'employment_types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|max:50|unique:employees,nik',
            'full_name' => 'required|string|max:150',
            'gender' => 'required|in:M,F',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'employment_status_id' => 'nullable|exists:options,id',
            'employment_type_id' => 'nullable|exists:options,id',
            'phone' => 'nullable|string|max:50',
            'email' => 'required|email|max:150|unique:employees,email',
        ]);

        Employee::create($validated);

        return redirect()->back()->with('success', 'Employee created successfully.');
    }

    public function update(Request $request, Employee $academic_employee)
    {
        $validated = $request->validate([
            'nik' => 'required|string|max:50|unique:employees,nik,' . $academic_employee->id,
            'full_name' => 'required|string|max:150',
            'gender' => 'required|in:M,F',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'employment_status_id' => 'nullable|exists:options,id',
            'employment_type_id' => 'nullable|exists:options,id',
            'phone' => 'nullable|string|max:50',
            'email' => 'required|email|max:150|unique:employees,email,' . $academic_employee->id,
        ]);

        $academic_employee->update($validated);

        return redirect()->back()->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $academic_employee)
    {
        $academic_employee->delete();
        return redirect()->back()->with('success', 'Employee deleted successfully.');
    }
}
