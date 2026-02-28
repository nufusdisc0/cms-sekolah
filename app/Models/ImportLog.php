<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImportLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'import_type',
        'filename',
        'total_rows',
        'successful_rows',
        'failed_rows',
        'duplicate_rows',
        'status',
        'started_at',
        'completed_at',
        'file_path',
        'mime_type',
        'file_size',
        'batch_size',
        'batches_processed',
        'total_batches',
        'column_mapping',
        'validation_rules',
        'created_by',
        'updated_by',
        'rolled_back_by',
        'notes',
        'summary',
    ];

    protected $casts = [
        'total_rows' => 'integer',
        'successful_rows' => 'integer',
        'failed_rows' => 'integer',
        'duplicate_rows' => 'integer',
        'file_size' => 'integer',
        'batch_size' => 'integer',
        'batches_processed' => 'integer',
        'total_batches' => 'integer',
        'column_mapping' => 'array',
        'validation_rules' => 'array',
        'summary' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    };

    /**
     * Relationships
     */
    public function errors()
    {
        return $this->hasMany(ImportError::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function rolledBackBy()
    {
        return $this->belongsTo(User::class, 'rolled_back_by');
    }

    /**
     * Scopes
     */
    public function scopeStudents($query)
    {
        return $query->where('import_type', 'student');
    }

    public function scopeEmployees($query)
    {
        return $query->where('import_type', 'employee');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('import_type', $type);
    }

    /**
     * Methods
     */
    public function getSuccessRateAttribute()
    {
        if ($this->total_rows == 0) {
            return 0;
        }
        return round(($this->successful_rows / $this->total_rows) * 100, 2);
    }

    public function getFailureRateAttribute()
    {
        if ($this->total_rows == 0) {
            return 0;
        }
        return round(($this->failed_rows / $this->total_rows) * 100, 2);
    }

    public function getDuplicateRateAttribute()
    {
        if ($this->total_rows == 0) {
            return 0;
        }
        return round(($this->duplicate_rows / $this->total_rows) * 100, 2);
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    public function canRollback()
    {
        return $this->status === 'completed' && !$this->rolled_back_by;
    }

    public function getTypeLabel()
    {
        return match($this->import_type) {
            'student' => 'Siswa',
            'employee' => 'Karyawan',
            default => $this->import_type,
        };
    }

    public function getStatusLabel()
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'processing' => 'Sedang Diproses',
            'completed' => 'Selesai',
            'failed' => 'Gagal',
            'rolled_back' => 'Dibatalkan',
            default => $this->status,
        };
    }
}
