<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registrant;
use App\Models\Major;

class RegistrantController extends Controller
{
    public function index()
    {
        $registrants = Registrant::orderBy('id', 'desc')->get();
        $majors = Major::where('is_active', 'true')->get();
        return view('backend.admission.registrants', compact('registrants', 'majors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:150',
            'gender' => 'required|in:M,F',
            'nik' => 'nullable|string|max:50',
            'nisn' => 'nullable|string|max:50',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:50',
            'first_choice_id' => 'nullable|exists:majors,id',
            'second_choice_id' => 'nullable|exists:majors,id',
            'street_address' => 'nullable|string|max:500',
            'father_name' => 'nullable|string|max:150',
            'mother_name' => 'nullable|string|max:150',
        ]);

        $validated['registration_number'] = 'REG-' . date('Ymd') . '-' . str_pad(Registrant::count() + 1, 4, '0', STR_PAD_LEFT);

        Registrant::create($validated);
        return redirect()->back()->with('success', 'Registrant created successfully.');
    }

    public function update(Request $request, Registrant $registrant)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:150',
            'gender' => 'required|in:M,F',
            'nik' => 'nullable|string|max:50',
            'nisn' => 'nullable|string|max:50',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:50',
            'first_choice_id' => 'nullable|exists:majors,id',
            'second_choice_id' => 'nullable|exists:majors,id',
            'street_address' => 'nullable|string|max:500',
            'father_name' => 'nullable|string|max:150',
            'mother_name' => 'nullable|string|max:150',
            're_registration' => 'nullable|in:true,false',
        ]);

        $registrant->update($validated);
        return redirect()->back()->with('success', 'Registrant updated successfully.');
    }

    public function destroy(Registrant $registrant)
    {
        $registrant->delete();
        return redirect()->back()->with('success', 'Registrant deleted successfully.');
    }
}
