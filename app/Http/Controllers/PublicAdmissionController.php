<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicAdmissionFormRequest;
use App\Models\Registrant;
use App\Models\AdmissionPhase;
use App\Models\Major;
use App\Services\RegistrationNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicAdmissionController extends Controller
{
    /**
     * Show the admission form (Step 1)
     */
    public function showForm()
    {
        $phases = AdmissionPhase::where('is_active', true)
            ->orderBy('start_date', 'asc')
            ->get();

        return view('public.admission.form', compact('phases'));
    }

    /**
     * Show Step 1: Personal Information
     */
    public function showStep1()
    {
        return view('public.admission.steps.step1');
    }

    /**
     * Validate and show Step 2: Contact Information
     */
    public function validateAndShowStep2(PublicAdmissionFormRequest $request)
    {
        $validated = $request->validated();

        // Store in session
        session(['admission_form' => array_merge(session('admission_form') ?? [], $validated)]);

        return view('public.admission.steps.step2');
    }

    /**
     * Validate and show Step 3: Parent Information
     */
    public function validateAndShowStep3(PublicAdmissionFormRequest $request)
    {
        $validated = $request->validated();

        // Store in session
        session(['admission_form' => array_merge(session('admission_form') ?? [], $validated)]);

        return view('public.admission.steps.step3');
    }

    /**
     * Validate and show Step 4: Academic Information & Documents
     */
    public function validateAndShowStep4(PublicAdmissionFormRequest $request)
    {
        $validated = $request->validated();

        // Store in session
        session(['admission_form' => array_merge(session('admission_form') ?? [], $validated)]);

        $phases = AdmissionPhase::where('is_active', true)->get();
        $majors = Major::where('is_active', true)->get();

        return view('public.admission.steps.step4', compact('phases', 'majors'));
    }

    /**
     * Process the complete admission form submission
     */
    public function submitForm(PublicAdmissionFormRequest $request)
    {
        $validated = $request->validated();

        // Get all data from session and current request
        $formData = array_merge(session('admission_form') ?? [], $validated);

        try {
            // Handle photo upload
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('admission/photos', 'public');
                $formData['photo_path'] = $photoPath;
            }

            // Handle documents upload
            $documentPaths = [];
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $document) {
                    $path = $document->store('admission/documents', 'public');
                    $documentPaths[] = $path;
                }
                $formData['documents'] = json_encode($documentPaths);
            }

            // Generate registration number
            $formData['registration_number'] = RegistrationNumberService::generate();
            $formData['registration_date'] = now();
            $formData['registration_token'] = Str::random(40);
            $formData['application_status'] = 'submitted';
            $formData['selection_status'] = 'pending';

            // Create registrant
            $registrant = Registrant::create($formData);

            // Clear session
            session()->forget('admission_form');

            // Dispatch event for email notification (Phase 4)
            // event(new AdmissionFormSubmitted($registrant));

            return redirect()->route('public.admission.confirmation', $registrant)
                ->with('success', 'Pendaftaran berhasil! Silakan periksa email Anda untuk konfirmasi.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memproses pendaftaran: ' . $e->getMessage());
        }
    }

    /**
     * Show confirmation page after successful submission
     */
    public function showConfirmation(Registrant $registrant)
    {
        // Verify that registrant was just created and belongs to current session
        if ($registrant->created_at->diffInMinutes(now()) > 5) {
            return redirect()->route('public.admission.form')
                ->with('warning', 'Sesi konfirmasi telah berakhir.');
        }

        return view('public.admission.confirmation', compact('registrant'));
    }

    /**
     * Download admission form PDF (after submission)
     */
    public function downloadFormPDF(Registrant $registrant)
    {
        // Allow downloading if registrant was created recently or user is admin
        if (!auth()->check() && $registrant->created_at->diffInMinutes(now()) > 30) {
            return redirect()->route('public.admission.form')
                ->with('error', 'Anda tidak memiliki akses ke dokumen ini.');
        }

        try {
            $admissionService = app(\App\Services\AdmissionService::class);
            return $admissionService->generateFilledPDF($registrant, true);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghasilkan PDF: ' . $e->getMessage());
        }
    }

    /**
     * Download blank form template
     */
    public function downloadBlankForm()
    {
        try {
            $admissionService = app(\App\Services\AdmissionService::class);
            return $admissionService->generateBlankPDF(true);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghasilkan template: ' . $e->getMessage());
        }
    }

    /**
     * Show admission results lookup page
     */
    public function showResultsLookup()
    {
        return view('public.admission.results-lookup');
    }

    /**
     * Check admission results
     */
    public function checkResults(Request $request)
    {
        $request->validate([
            'registration_number' => 'required|string',
            'email' => 'required|email',
        ]);

        $registrant = Registrant::where('registration_number', $request->registration_number)
            ->where('email', $request->email)
            ->first();

        if (!$registrant) {
            return back()->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        return view('public.admission.results', compact('registrant'));
    }

    /**
     * Get available admission phases (API endpoint)
     */
    public function getAdmissionPhases()
    {
        $phases = AdmissionPhase::where('is_active', true)
            ->select('id', 'name', 'start_date', 'end_date', 'description')
            ->get();

        return response()->json($phases);
    }

    /**
     * Get majors for a specific phase (API endpoint)
     */
    public function getMajorsForPhase($phaseId)
    {
        $majors = Major::where('is_active', true)
            ->select('id', 'name', 'description')
            ->get();

        return response()->json($majors);
    }

    /**
     * Preview form data before submission
     */
    public function previewForm()
    {
        $formData = session('admission_form');

        if (!$formData) {
            return redirect()->route('public.admission.form')
                ->with('error', 'Tidak ada data formulir untuk ditampilkan.');
        }

        return view('public.admission.preview', compact('formData'));
    }
}
