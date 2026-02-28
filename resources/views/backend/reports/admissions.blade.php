@extends('layouts.admin')

@section('title', 'Laporan Analitik Seleksi')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-clipboard-list text-info me-2"></i>Laporan Analitik Seleksi
            </h1>
            <p class="text-muted small">Analisis lengkap data pendaftaran dan seleksi</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('backend.reports.admissions.export') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-download me-1"></i> Unduh CSV
            </a>
            <a href="{{ route('backend.reports.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Overall Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h3 class="text-primary mb-1">{{ $overallStats['total_registrants'] }}</h3>
                    <p class="text-muted mb-0">Total Pendaftar</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h3 class="text-success mb-1">{{ $overallStats['passed'] }}</h3>
                    <p class="text-muted mb-0">Diterima</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h3 class="text-danger mb-1">{{ $overallStats['failed'] }}</h3>
                    <p class="text-muted mb-0">Ditolak</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h3 class="text-warning mb-1">{{ $overallStats['pending'] }}</h3>
                    <p class="text-muted mb-0">Menunggu</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Acceptance Rate -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Tingkat Penerimaan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Diterima</strong>
                            <span class="badge bg-success">{{ $acceptanceRate['acceptance_rate'] }}%</span>
                        </div>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-success" style="width: {{ $acceptanceRate['acceptance_rate'] }}%">
                                {{ $acceptanceRate['accepted'] }}
                            </div>
                        </div>
                    </div>
                    <div class="row text-center mt-3">
                        <div class="col-4">
                            <p class="text-muted small mb-1">Total</p>
                            <h5 class="mb-0">{{ $acceptanceRate['total'] }}</h5>
                        </div>
                        <div class="col-4">
                            <p class="text-muted small mb-1">Diterima</p>
                            <h5 class="mb-0 text-success">{{ $acceptanceRate['accepted'] }}</h5>
                        </div>
                        <div class="col-4">
                            <p class="text-muted small mb-1">Ditolak</p>
                            <h5 class="mb-0 text-danger">{{ $acceptanceRate['rejected'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gender Distribution -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Distribusi Jenis Kelamin</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-sm">
                        @foreach($byGender as $gender => $count)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $gender }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- By Phase -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Pendaftar Per Fase Pendaftaran</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-sm">
                        @forelse($byPhase as $item)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $item->name }}</span>
                                <span class="badge bg-info rounded-pill">{{ $item->count }}</span>
                            </div>
                        @empty
                            <div class="text-center text-muted py-3">Tidak ada data</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- By Major -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Pendaftar Per Jurusan</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-sm">
                        @forelse($byMajor as $item)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $item->name }}</span>
                                <span class="badge bg-warning rounded-pill">{{ $item->count }}</span>
                            </div>
                        @empty
                            <div class="text-center text-muted py-3">Tidak ada data</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Major Popularity -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Popularitas Jurusan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Jurusan</th>
                                    <th class="text-end">Jumlah Pendaftar</th>
                                    <th class="text-end">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($majorPopularity as $index => $major)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $major->name }}</td>
                                        <td class="text-end"><span class="badge bg-primary">{{ $major->registrant_count }}</span></td>
                                        <td class="text-end">
                                            <span class="text-muted small">
                                                {{ $overallStats['total_registrants'] > 0 ? round(($major->registrant_count / $overallStats['total_registrants']) * 100, 2) : 0 }}%
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
