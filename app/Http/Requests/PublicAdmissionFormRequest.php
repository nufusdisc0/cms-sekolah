<?php

namespace App\Http\Requests;

use App\Rules\RecaptchaRule;
use Illuminate\Foundation\Http\FormRequest;

class PublicAdmissionFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public form, anyone can access
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $step = $this->input('step', 1);

        return match ($step) {
            1 => $this->stepOneRules(),
            2 => $this->stepTwoRules(),
            3 => $this->stepThreeRules(),
            4 => $this->stepFourRules(),
            default => [],
        };
    }

    /**
     * Step 1: Personal Information
     */
    private function stepOneRules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:20|unique:registrants,nisn',
            'nik' => 'nullable|string|max:20|unique:registrants,nik',
            'gender' => 'required|in:M,F',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
        ];
    }

    /**
     * Step 2: Contact & Address Information
     */
    private function stepTwoRules(): array
    {
        return [
            'email' => 'required|email|max:255|unique:registrants,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'district' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:10',
        ];
    }

    /**
     * Step 3: Parent/Guardian Information
     */
    private function stepThreeRules(): array
    {
        return [
            'parent_name' => 'required|string|max:255',
            'parent_email' => 'required|email|max:255',
            'parent_phone' => 'required|string|max:20',
            'parent_address' => 'nullable|string|max:500',
        ];
    }

    /**
     * Step 4: Academic Information & Files
     */
    private function stepFourRules(): array
    {
        return [
            'admission_phase_id' => 'required|exists:admission_phases,id',
            'major' => 'required|string|max:255',
            'admission_type' => 'nullable|string|max:255',
            'previous_school' => 'required|string|max:255',
            'previous_gpa' => 'nullable|numeric|min:0|max:4.0',
            'graduation_year' => 'required|numeric|min:' . (date('Y') - 10) . '|max:' . date('Y'),
            'photo' => 'required|image|max:2048|mimes:jpeg,png,jpg',
            'documents.*' => 'nullable|file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png',
            'g-recaptcha-response' => ['required', new RecaptchaRule()],
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Nama lengkap wajib diisi',
            'gender.required' => 'Jenis kelamin wajib dipilih',
            'birth_date.required' => 'Tanggal lahir wajib diisi',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'phone.required' => 'Nomor telepon wajib diisi',
            'address.required' => 'Alamat wajib diisi',
            'city.required' => 'Kota wajib dipilih',
            'province.required' => 'Provinsi wajib dipilih',
            'parent_name.required' => 'Nama orang tua wajib diisi',
            'parent_email.required' => 'Email orang tua wajib diisi',
            'parent_phone.required' => 'Nomor telepon orang tua wajib diisi',
            'admission_phase_id.required' => 'Fase pendaftaran wajib dipilih',
            'major.required' => 'Jurusan wajib dipilih',
            'previous_school.required' => 'Sekolah asal wajib diisi',
            'graduation_year.required' => 'Tahun lulus wajib diisi',
            'photo.required' => 'Foto wajib diunggah',
            'photo.image' => 'File harus berupa gambar',
            'photo.max' => 'Ukuran foto maksimal 2MB',
            'documents.*.max' => 'Ukuran dokumen maksimal 5MB',
            'g-recaptcha-response.required' => 'Verifikasi reCAPTCHA wajib dilakukan',
        ];
    }

    /**
     * Prepare data for validation
     */
    protected function prepareForValidation(): void
    {
        // Convert birth_date if needed
        if ($this->has('birth_date')) {
            $this->merge([
                'birth_date' => $this->birth_date,
            ]);
        }
    }
}
