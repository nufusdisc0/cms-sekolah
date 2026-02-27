<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Option;
use Illuminate\Support\Facades\Auth;

class EmployeeProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->user_type !== 'employee') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Anda bukan Karyawan.');
        }

        $employee = Employee::find($user->user_profile_id);
        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Data biodata karyawan tidak ditemukan.');
        }

        $religions = Option::where('option_group', 'religion')->get();
        $marriage_status = Option::where('option_group', 'marriage_status')->get();
        $employment_status = Option::where('option_group', 'employment_status')->get();
        $employments = Option::where('option_group', 'employment')->get();
        $employment_types = Option::where('option_group', 'employment_type')->get();
        $institution_lifters = Option::where('option_group', 'institution_lifter')->get();
        $salary_sources = Option::where('option_group', 'salary_source')->get();
        $laboratory_skills = Option::where('option_group', 'laboratory_skill')->get();
        $special_needs = Option::where('option_group', 'special_needs')->get();
        $ranks = Option::where('option_group', 'rank')->get();

        return view('backend.users.employee_profile', compact(
            'employee', 'religions', 'marriage_status', 'employment_status',
            'employments', 'employment_types', 'institution_lifters',
            'salary_sources', 'laboratory_skills', 'special_needs', 'ranks'
        ));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if ($user->user_type !== 'employee') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $employee = Employee::find($user->user_profile_id);
        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Data biodata karyawan tidak ditemukan.');
        }

        $validated = $request->validate([
            'full_name' => 'required|string',
            'nik' => 'required|string|unique:employees,nik,' . $employee->id,
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'rt' => 'nullable|numeric',
            'rw' => 'nullable|numeric',
            'postal_code' => 'nullable|numeric',
            // simple validation that captures standard fields without strictly failing on strings.
        ]);

        $data = $request->except(['_token', '_method']);

        $employee->update($data);

        if ($user->user_name !== $employee->nik && $employee->nik) {
            $user->update(['user_name' => $employee->nik]);
        }
        if ($user->user_email !== $employee->email && $employee->email) {
            $user->update(['user_email' => $employee->email]);
        }

        return redirect()->back()->with('success', 'Biodata Karyawan berhasil diperbarui.');
    }
}
