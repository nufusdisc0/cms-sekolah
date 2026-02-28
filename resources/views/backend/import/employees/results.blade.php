@extends('layouts.backend')

@section('title', 'Hasil Impor Data Karyawan')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-list text-primary me-2"></i>Hasil Impor Data Karyawan
            </h1>
            <p class="text-muted small">{{ $importLog->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('backend.import.employees.form') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-plus me-1"></i> Impor Baru
            </a>
            <a href="{{ route('backend.import.history') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-history me-1"></i> Riwayat
            </a>
        </div>
    </div>

    <!-- Success Alert -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <h3 class="mb-0">{{ $importLog->successful_rows }}</h3>
                    <p class="text-muted small mb-0">Berhasil</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-danger mb-2">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                    <h3 class="mb-0">{{ $importLog->failed_rows }}</h3>
                    <p class="text-muted small mb-0">Gagal</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="fas fa-exclamation-circle fa-2x"></i>
                    </div>
                    <h3 class="mb-0">{{ $importLog->duplicate_rows }}</h3>
                    <p class="text-muted small mb-0">Duplikat</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="fas fa-chart-pie fa-2x"></i>
                    </div>
                    <h3 class="mb-0">{{ $importLog->success_rate }}%</h3>
                    <p class="text-muted small mb-0">Tingkat Keberhasilan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-2">
                    <strong>Progres Keseluruhan</strong>
                    <small class="text-muted">{{ $importLog->successful_rows + $importLog->failed_rows + $importLog->duplicate_rows }} / {{ $importLog->total_rows }}</small>
                </div>
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar bg-success" style="width: {{ $importLog->success_rate }}%">
                        <small class="text-white">Berhasil {{ $importLog->success_rate }}%</small>
                    </div>
                    <div class="progress-bar bg-danger" style="width: {{ $importLog->failure_rate }}%">
                        <small>Gagal {{ $importLog->failure_rate }}%</small>
                    </div>
                    <div class="progress-bar bg-warning" style="width: {{ $importLog->duplicate_rate }}%">
                        <small>Duplikat {{ $importLog->duplicate_rate }}%</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Details -->
    @if($importLog->failed_rows > 0 || $importLog->duplicate_rows > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                    Detail Kesalahan ({{ $importLog->failed_rows + $importLog->duplicate_rows }} item)
                </h5>
                <a href="{{ route('backend.import.employees.download-errors', $importLog) }}" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-download me-1"></i> Unduh Laporan
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Baris</th>
                                <th>Tipe Error</th>
                                <th>Pesan</th>
                                <th>Field yang Error</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($errors as $error)
                                <tr>
                                    <td>
                                        <small class="badge bg-secondary">{{ $error->row_number }}</small>
                                    </td>
                                    <td>
                                        @switch($error->error_type)
                                            @case('validation')
                                                <span class="badge bg-danger">Validasi</span>
                                                @break
                                            @case('duplicate')
                                                <span class="badge bg-warning">Duplikat</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $error->getErrorTypeLabel() }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <small>{{ $error->error_message }}</small>
                                    </td>
                                    <td>
                                        @if($error->failed_fields && is_array($error->failed_fields))
                                            <small>{{ implode(', ', $error->failed_fields) }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Tidak ada kesalahan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($errors->hasPages())
                    <div class="d-flex justify-content-center p-3 border-top">
                        {{ $errors->links() }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Summary -->
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="alert alert-info mb-0">
                <strong>Status Impor:</strong> {{ $importLog->getStatusLabel() }}<br>
                <strong>Total Baris:</strong> {{ $importLog->total_rows }}<br>
                <strong>Tanggal Impor:</strong> {{ $importLog->created_at->format('d/m/Y H:i:s') }}
            </div>
        </div>
        <div class="col-lg-4 text-end">
            @if($importLog->canRollback())
                <form action="{{ route('backend.import.rollback', $importLog) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Batalkan impor ini?');">
                        <i class="fas fa-undo me-2"></i> Batalkan Impor
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
