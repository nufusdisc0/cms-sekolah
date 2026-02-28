<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImportError extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'import_log_id',
        'row_number',
        'error_type',
        'error_code',
        'error_message',
        'row_data',
        'failed_fields',
        'validation_errors',
        'is_resolved',
        'resolution_notes',
    ];

    protected $casts = [
        'row_number' => 'integer',
        'is_resolved' => 'boolean',
        'row_data' => 'array',
        'failed_fields' => 'array',
        'validation_errors' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function importLog()
    {
        return $this->belongsTo(ImportLog::class);
    }

    /**
     * Scopes
     */
    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    public function scopeResolved($query)
    {
        return $query->where('is_resolved', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('error_type', $type);
    }

    public function scopeValidation($query)
    {
        return $query->where('error_type', 'validation');
    }

    public function scopeDuplicate($query)
    {
        return $query->where('error_type', 'duplicate');
    }

    /**
     * Methods
     */
    public function getErrorTypeLabel()
    {
        return match($this->error_type) {
            'validation' => 'Validasi Gagal',
            'duplicate' => 'Data Duplikat',
            'email_exists' => 'Email Sudah Ada',
            'nik_exists' => 'NIK Sudah Ada',
            'nisn_exists' => 'NISN Sudah Ada',
            'null_required_field' => 'Field Wajib Kosong',
            'invalid_format' => 'Format Tidak Valid',
            'invalid_date' => 'Tanggal Tidak Valid',
            default => $this->error_type,
        };
    }

    public function markResolved($notes = null)
    {
        $this->update([
            'is_resolved' => true,
            'resolution_notes' => $notes,
        ]);
    }
}
