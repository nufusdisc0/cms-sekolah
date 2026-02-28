<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SelectionResult extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'total_score' => 'float',
        'test_score' => 'float',
        'academic_score' => 'float',
        'interview_score' => 'float',
        'extra_curricular_score' => 'float',
        'other_score' => 'float',
        'ranking_details' => 'json',
        'processed_at' => 'datetime',
        'announced_at' => 'datetime',
        'acceptance_confirmed_at' => 'datetime',
        'acceptance_expired_at' => 'datetime',
        'acceptance_confirmed' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'admission_selection_id',
        'registrant_id',
        'admission_quota_id',
        'total_score',
        'test_score',
        'academic_score',
        'interview_score',
        'extra_curricular_score',
        'other_score',
        'rank',
        'ranking_details',
        'result',
        'choice_priority',
        'allocated_major',
        'processed_at',
        'announced_at',
        'remarks',
        'acceptance_confirmed',
        'acceptance_confirmed_at',
        'acceptance_expired_at',
        'created_by',
        'updated_by',
    ];

    // Relationships
    public function admissionSelection()
    {
        return $this->belongsTo(AdmissionSelection::class);
    }

    public function registrant()
    {
        return $this->belongsTo(Registrant::class);
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

    // Scopes
    public function scopePassed($query)
    {
        return $query->where('result', 'passed');
    }

    public function scopeFailed($query)
    {
        return $query->where('result', 'failed');
    }

    public function scopeWaitlisted($query)
    {
        return $query->where('result', 'waitlisted');
    }

    public function scopePending($query)
    {
        return $query->where('result', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('acceptance_confirmed', true);
    }

    public function scopeByRank($query)
    {
        return $query->orderBy('rank', 'asc');
    }

    public function scopeByScore($query)
    {
        return $query->orderBy('total_score', 'desc');
    }

    // Methods
    public function isAcceptancePending(): bool
    {
        return $this->result === 'passed' &&
               !$this->acceptance_confirmed &&
               (!$this->acceptance_expired_at || $this->acceptance_expired_at->isFuture());
    }

    public function isAcceptanceExpired(): bool
    {
        return $this->acceptance_expired_at && $this->acceptance_expired_at->isPast();
    }

    public function confirmAcceptance()
    {
        $this->update([
            'acceptance_confirmed' => true,
            'acceptance_confirmed_at' => now(),
        ]);

        // Update registrant status
        $this->registrant->update([
            'application_status' => 'confirmed',
            'accepted_at' => now(),
        ]);
    }

    public function rejectAcceptance()
    {
        $this->update([
            'acceptance_confirmed' => false,
        ]);
    }

    public function getScoreBreakdownAttribute(): array
    {
        return [
            'test' => $this->test_score,
            'academic' => $this->academic_score,
            'interview' => $this->interview_score,
            'extracurricular' => $this->extra_curricular_score,
            'other' => $this->other_score,
            'total' => $this->total_score,
        ];
    }

    public function getScoreWeightsAttribute(): array
    {
        $criteria = $this->admissionSelection->selection_criteria ?? [];

        return [
            'test_weight' => $criteria['test_weight'] ?? 30,
            'academic_weight' => $criteria['academic_weight'] ?? 20,
            'interview_weight' => $criteria['interview_weight'] ?? 20,
            'extracurricular_weight' => $criteria['extracurricular_weight'] ?? 15,
            'other_weight' => $criteria['other_weight'] ?? 15,
        ];
    }
}
