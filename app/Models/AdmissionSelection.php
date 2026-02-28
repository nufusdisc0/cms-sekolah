<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmissionSelection extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'selection_criteria' => 'json',
        'selection_started_at' => 'datetime',
        'selection_completed_at' => 'datetime',
        'results_announced_at' => 'datetime',
        'allow_multiple_choices' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'admission_phase_id',
        'name',
        'description',
        'selection_criteria',
        'batch_size',
        'status',
        'selection_started_at',
        'selection_completed_at',
        'results_announced_at',
        'total_quota',
        'accepted_count',
        'rejected_count',
        'processed_count',
        'ranking_method',
        'allow_multiple_choices',
        'choice_method',
        'created_by',
        'updated_by',
    ];

    // Relationships
    public function admissionPhase()
    {
        return $this->belongsTo(AdmissionPhase::class);
    }

    public function selectionResults()
    {
        return $this->hasMany(SelectionResult::class);
    }

    public function acceptedResults()
    {
        return $this->selectionResults()->where('result', 'passed');
    }

    public function rejectedResults()
    {
        return $this->selectionResults()->where('result', 'failed');
    }

    public function waitlistedResults()
    {
        return $this->selectionResults()->where('result', 'waitlisted');
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
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeAnnounced($query)
    {
        return $query->where('status', 'announced');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['in_progress', 'completed', 'announced']);
    }

    // Mutators
    public function getQuotaCoverageAttribute()
    {
        if ($this->total_quota == 0) {
            return 0;
        }
        return round(($this->accepted_count / $this->total_quota) * 100, 2);
    }

    public function getProgressPercentageAttribute()
    {
        $total = $this->admissionPhase->registrants()->submitted()->count();
        if ($total == 0) {
            return 0;
        }
        return round(($this->processed_count / $total) * 100, 2);
    }

    // Methods
    public function canStartSelection(): bool
    {
        return $this->status === 'draft' &&
               $this->admissionPhase->registrants()->submitted()->count() > 0;
    }

    public function canAnnounceResults(): bool
    {
        return $this->status === 'completed' && !$this->results_announced_at;
    }

    public function startSelection()
    {
        $this->update([
            'status' => 'in_progress',
            'selection_started_at' => now(),
            'processed_count' => 0,
        ]);
    }

    public function completeSelection()
    {
        $this->update([
            'status' => 'completed',
            'selection_completed_at' => now(),
        ]);
    }

    public function announceResults()
    {
        $this->update([
            'status' => 'announced',
            'results_announced_at' => now(),
        ]);

        // Update registrant selection statuses
        $this->selectionResults()->each(function (SelectionResult $result) {
            $result->registrant->update([
                'selection_status' => $result->result,
                'selection_date' => now(),
            ]);
            $result->update(['announced_at' => now()]);
        });
    }
}
