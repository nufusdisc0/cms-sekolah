@extends('layouts.admin')

@section('title', 'Manajemen Seleksi Pendaftaran')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-graduation-cap text-primary"></i> Manajemen Seleksi
            </h1>
            <p class="text-muted small">Kelola proses seleksi calon siswa</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('backend.selection.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Buat Seleksi Baru
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ implode(' ', $errors->all()) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted small mb-1">Total Seleksi</h6>
                            <h3 class="mb-0">{{ $selections->count() }}</h3>
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
                            <h6 class="text-muted small mb-1">Draft</h6>
                            <h3 class="mb-0">{{ $selections->where('status', 'draft')->count() }}</h3>
                        </div>
                        <i class="fas fa-file text-secondary fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted small mb-1">Sedang Berjalan</h6>
                            <h3 class="mb-0">{{ $selections->where('status', 'in_progress')->count() }}</h3>
                        </div>
                        <i class="fas fa-play-circle text-warning fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted small mb-1">Diumumkan</h6>
                            <h3 class="mb-0">{{ $selections->where('status', 'announced')->count() }}</h3>
                        </div>
                        <i class="fas fa-bullhorn text-success fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Selections Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Daftar Seleksi</h5>
        </div>
        <div class="card-body p-0">
            @if ($selections->isEmpty())
                <div class="p-4 text-center text-muted">
                    <i class="fas fa-inbox fa-3x opacity-50 mb-3 d-block"></i>
                    <p>Belum ada seleksi. <a href="{{ route('backend.selection.create') }}">Buat yang baru</a></p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Seleksi</th>
                                <th>Fase Pendaftaran</th>
                                <th>Status</th>
                                <th>Kuota</th>
                                <th>Diterima/Ditolak</th>
                                <th>Metode</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($selections as $selection)
                                <tr>
                                    <td>
                                        <strong>{{ $selection->name }}</strong>
                                        @if ($selection->description)
                                            <br><small class="text-muted">{{ Str::limit($selection->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $selection->admissionPhase->name ?? '-' }}</td>
                                    <td>
                                        @switch($selection->status)
                                            @case('draft')
                                                <span class="badge bg-secondary">Draft</span>
                                                @break
                                            @case('in_progress')
                                                <span class="badge bg-warning">Sedang Berjalan</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-info">Selesai</span>
                                                @break
                                            @case('announced')
                                                <span class="badge bg-success">Diumumkan</span>
                                                @break
                                            @case('canceled')
                                                <span class="badge bg-danger">Dibatalkan</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>{{ $selection->total_quota }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ $selection->accepted_count }}</span>
                                        <span class="badge bg-danger">{{ $selection->rejected_count }}</span>
                                    </td>
                                    <td>
                                        <small>{{ ucfirst(str_replace('_', ' ', $selection->ranking_method)) }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('backend.selection.show', $selection) }}" class="btn btn-outline-primary" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if ($selection->status === 'draft')
                                                <a href="{{ route('backend.selection.edit', $selection) }}" class="btn btn-outline-secondary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('backend.selection.destroy', $selection) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus seleksi ini?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                        <i class="fas fa-trash"></i>
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
</div>
@endsection
