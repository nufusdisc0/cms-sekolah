<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportEmployeesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('import-employees');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:csv,txt,xls,xlsx|max:10240', // 10MB max
            'batch_size' => 'required|integer|min:10|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'file.required' => 'File harus dilampirkan',
            'file.file' => 'File tidak valid',
            'file.mimes' => 'File harus berformat CSV atau Excel',
            'file.max' => 'File maksimal 10MB',
            'batch_size.required' => 'Ukuran batch harus ditentukan',
            'batch_size.integer' => 'Ukuran batch harus angka',
            'batch_size.min' => 'Ukuran batch minimal 10',
            'batch_size.max' => 'Ukuran batch maksimal 1000',
        ];
    }
}
