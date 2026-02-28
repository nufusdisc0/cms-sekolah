<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registrant;
use App\Models\Major;
use App\Services\AdmissionService;
use App\Services\RegistrationNumberService;

class RegistrantController extends Controller
{
    protected $admissionService;

    public function __construct(AdmissionService $admissionService)
    {
        $this->admissionService = $admissionService;
    }

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

    /**
     * Download filled PDF admission form for a registrant
     *
     * @param Registrant $registrant
     * @return Response
     */
    public function downloadPDF(Registrant $registrant)
    {
        try {
            return $this->admissionService->generateFilledPDF($registrant, true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Stream PDF for viewing in browser
     *
     * @param Registrant $registrant
     * @return Response
     */
    public function viewPDF(Registrant $registrant)
    {
        try {
            $content = $this->admissionService->generateFilledPDF($registrant, false);
            return response($content, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Admission_Form_' . $registrant->registration_number . '.pdf"',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to view PDF: ' . $e->getMessage());
        }
    }

    /**
     * Download blank admission form template
     *
     * @return Response
     */
    public function downloadBlankForm()
    {
        try {
            return $this->admissionService->generateBlankPDF(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate form template: ' . $e->getMessage());
        }
    }

    /**
     * Regenerate registration number for a registrant
     *
     * @param Registrant $registrant
     * @return Response
     */
    public function regenerateRegistrationNumber(Registrant $registrant)
    {
        try {
            $newNumber = RegistrationNumberService::generate();
            $registrant->update(['registration_number' => $newNumber]);

            return redirect()->back()->with('success', 'Registration number regenerated: ' . $newNumber);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to regenerate registration number: ' . $e->getMessage());
        }
    }
}
