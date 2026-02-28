<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registrant extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'birth_date' => 'date',
        'registration_date' => 'datetime',
        'selection_date' => 'datetime',
        'pdf_generated_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'accepted_at' => 'datetime',
        'deleted_at' => 'datetime',
        'restored_at' => 'datetime',
        'acceptance_letter_date' => 'date',
        'documents' => 'json',
        'email_verified' => 'boolean',
    };

    protected $fillable = [
        'full_name', 'nisn', 'nik', 'gender', 'birth_place', 'birth_date',
        'email', 'phone', 'address', 'district', 'city', 'province', 'postal_code',
        'parent_name', 'parent_email', 'parent_phone', 'parent_address',
        'admission_phase_id', 'admission_quota_id', 'admission_type', 'major',
        'previous_school', 'previous_gpa', 'graduation_year',
        'application_status', 'selection_status', 'selection_date',
        'registration_number', 'registration_date', 'registration_token',
        'email_verified', 'email_verified_at',
        'photo_path', 'documents', 'pdf_generated_at', 'pdf_path',
        'acceptance_letter_number', 'acceptance_letter_date', 'accepted_at',
        'created_by', 'updated_by', 'deleted_by', 'restored_by', 'restored_at',
    ];

    // Relationships
    public function admissionPhase()
    {
        return $this->belongsTo(AdmissionPhase::class);
    }

    public function admissionQuota()
    {
        return $this->belongsTo(AdmissionQuota::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function restoredBy()
    {
        return $this->belongsTo(User::class, 'restored_by');
    }

    // Scopes
    public function scopeSubmitted($query)
    {
        return $query->whereNotNull('registration_number');
    }

    public function scopeApproved($query)
    {
        return $query->where('selection_status', 'passed');
    }

    public function scopeRejected($query)
    {
        return $query->where('selection_status', 'failed');
    }

    public function scopePending($query)
    {
        return $query->where('selection_status', 'pending');
    }

    public function scopeByPhase($query, $phaseId)
    {
        return $query->where('admission_phase_id', $phaseId);
    }
}
