@extends('layouts.backend')

@section('title', 'Riwayat Impor')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-history text-primary me-2"></i>Riwayat Impor Data
            </h1>
            <p class="text-muted small">Pantau semua aktivitas impor siswa dan karyawan</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group" role="group">
                <a href="{{ route('backend.import.students.form') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Impor Siswa
                </a>
                <a href="{{ route('backend.import.employees.form') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Impor Karyawan
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small text-muted">Jenis</label>
                    <select name="type" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="student" {{ request('type') === 'student' ? 'selected' : '' }}>Siswa</option>
                        <option value="employee" {{ request('type') === 'employee' ? 'selected' : '' }}>Karyawan</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Sedang Diproses</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted">&nbsp;</label>
                    <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Imports Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Daftar Impor ({{ $imports->total() }} total)</h5>
        </div>
        <div class="card-body p-0">
            @if($imports->isEmpty())
                <div class="p-4 text-center text-muted">
                    <i class="fas fa-inbox fa-3x opacity-50 mb-3 d-block"></i>
                    <p>Belum ada impor. <a href="{{ route('backend.import.students.form') }}">Mulai impor</a></p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama File</th>
                                <th>Jenis</th>
                                <th>Status</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Berhasil</th>
                                <th class="text-center">Gagal</th>
                                <th class="text-center">Tingkat</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($imports as $import)
                                <tr>
                                    <td>
                                        <strong>{{ Str::limit($import->filename, 30) }}</strong>
                                        @if($import->notes)
                                            <br><small class="text-muted">{{ Str::limit($import->notes, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($import->import_type === 'student')
                                            <span class="badge bg-info">Siswa</span>
                                        @else
                                            <span class="badge bg-secondary">Karyawan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($import->status)
                                            @case('pending')
                                                <span class="badge bg-warning">Menunggu</span>
                                                @break
                                            @case('processing')
                                                <span class="badge bg-info">Sedang Diproses</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-success">Selesai</span>
                                                @break
                                            @case('failed')
                                                <span class="badge bg-danger">Gagal</span>
                                                @break
                                            @case('rolled_back')
                                                <span class="badge bg-secondary">Dibatalkan</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="text-center">
                                        <strong>{{ $import->total_rows }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $import->successful_rows }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ $import->failed_rows + $import->duplicate_rows }}</span>
                                    </td>
                                    <td class="text-center">
                                        <strong>{{ $import->success_rate }}%</strong>
                                        <br><small class="text-muted">({{ $import->successful_rows }}/{{ $import->total_rows }})</small>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $import->created_at->format('d/m/Y H:i') }}</small>
                                        @if($import->createdBy)
                                            <br><small class="text-muted">{{ $import->createdBy->name }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            @if($import->import_type === 'student')
                                                <a href="{{ route('backend.import.students.results', $import) }}" class="btn btn-outline-primary" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('backend.import.employees.results', $import) }}" class="btn btn-outline-primary" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif

                                            @if($import->canRollback())
                                                <form action="{{ route('backend.import.rollback', $import) }}" method="POST" style="display:inline;" onsubmit="return confirm('Batalkan impor ini?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger" title="Batalkan">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Pagination -->
    @if($imports->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $imports->links() }}
        </div>
    @endif
</div>
@endsection
