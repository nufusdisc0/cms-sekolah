@extends('layouts.backend')

@section('title', 'Pratinjau Impor Data Siswa')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-eye text-primary me-2"></i>Pratinjau Data Siswa
            </h1>
            <p class="text-muted small">Periksa data sebelum mengonfirmasi impor - {{ $totalRows }} baris ditemukan</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('backend.import.students.form') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted small mb-1">Total Baris</h6>
                            <h3 class="mb-0">{{ $totalRows }}</h3>
                        </div>
                        <i class="fas fa-list text-primary fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted small mb-1">Pratinjau</h6>
                            <h3 class="mb-0">{{ count($data) }}</h3>
                        </div>
                        <i class="fas fa-eye text-info fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted small mb-1">Kolom</h6>
                            <h3 class="mb-0">{{ count($columnMapping) }}</h3>
                        </div>
                        <i class="fas fa-columns text-success fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted small mb-1">ukuran Batch</h6>
                            <h3 class="mb-0">{{ $importLog->batch_size }}</h3>
                        </div>
                        <i class="fas fa-cube text-warning fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Preview -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Pratinjau {{ count($data) }} Baris Pertama</h5>
            <span class="badge bg-secondary">{{ count($data) }} / {{ $totalRows }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px">#</th>
                            @foreach(array_keys($columnMapping) as $column)
                                <th>{{ ucfirst(str_replace('_', ' ', $column)) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                            <tr>
                                <td><small class="text-muted">{{ $row['row_number'] }}</small></td>
                                @foreach($row['data'] as $value)
                                    <td><small>{{ $value ?: '-' }}</small></td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($columnMapping) + 1 }}" class="text-center text-muted py-4">
                                    Tidak ada data untuk ditampilkan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Confirmation Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="alert alert-info">
                <strong>Siap untuk impor?</strong> Klik tombol di bawah untuk melanjutkan. Data akan divalidasi dan diimpor ke database.
            </div>

            <form action="{{ route('backend.import.students.process') }}" method="POST">
                @csrf
                <input type="hidden" name="import_log_id" value="{{ $importLog->id }}">

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-check-circle me-2"></i> Konfirmasi & Impor Data
                    </button>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <!-- Column Mapping Info -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Pemetaan Kolom</h5>
                </div>
                <div class="card-body">
                    <div class="small">
                        @forelse($columnMapping as $csvCol => $dbField)
                            <div class="mb-2 pb-2 border-bottom">
                                <span class="badge bg-secondary">{{ $csvCol }}</span>
                                <i class="fas fa-arrow-right text-muted mx-2"></i>
                                <span class="badge bg-primary">{{ $dbField }}</span>
                            </div>
                        @empty
                            <p class="text-muted">Tidak ada pemetaan</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
