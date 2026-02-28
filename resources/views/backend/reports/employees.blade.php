@extends('layouts.admin')

@section('title', 'Laporan Statistik Karyawan')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-users text-warning me-2"></i>Laporan Statistik Karyawan
            </h1>
            <p class="text-muted small">Analisis lengkap data karyawan dan distribusinya</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('backend.reports.employees.export') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-download me-1"></i> Unduh CSV
            </a>
            <a href="{{ route('backend.reports.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Overall Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h3 class="text-primary mb-1">{{ $overallStats['total'] }}</h3>
                    <p class="text-muted mb-0">Total Karyawan</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h3 class="text-success mb-1">{{ $overallStats['male'] }}</h3>
                    <p class="text-muted mb-0">Laki-laki</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h3 class="text-info mb-1">{{ $overallStats['female'] }}</h3>
                    <p class="text-muted mb-0">Perempuan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gender & Age Distribution -->
    <div class="row mb-4">
        <!-- Gender Distribution -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Distribusi Jenis Kelamin</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-sm">
                        @foreach($genderDistribution as $gender => $count)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $gender }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Age Distribution -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Distribusi Usia</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-sm">
                        @foreach($ageDistribution as $ageGroup => $count)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $ageGroup }}</span>
                                <span class="badge bg-warning rounded-pill">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tenure & Certification -->
    <div class="row mb-4">
        <!-- Tenure Distribution -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Distribusi Masa Kerja</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-sm">
                        @foreach($tenureDistribution as $period => $count)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $period }}</span>
                                <span class="badge bg-info rounded-pill">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Certification Rate -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Tingkat Sertifikasi</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Tersertifikasi</strong>
                            <span class="badge bg-success">{{ $certificationRates['certification_rate'] }}%</span>
                        </div>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-success" style="width: {{ $certificationRates['certification_rate'] }}%">
                                {{ $certificationRates['with_license'] }}
                            </div>
                        </div>
                    </div>
                    <div class="row text-center mt-3">
                        <div class="col-6">
                            <p class="text-muted small mb-1">Total Karyawan</p>
                            <h5 class="mb-0">{{ $certificationRates['total_employees'] }}</h5>
                        </div>
                        <div class="col-6">
                            <p class="text-muted small mb-1">Tersertifikasi</p>
                            <h5 class="mb-0 text-success">{{ $certificationRates['with_license'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- By Type -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Karyawan Per Jenis Kepegawaian</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-sm">
                        @forelse($byType as $item)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $item->name }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $item->count }}</span>
                            </div>
                        @empty
                            <div class="text-center text-muted py-3">Tidak ada data</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- By Status -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Karyawan Per Status Kepegawaian</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-sm">
                        @forelse($byStatus as $item)
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
    </div>

    <!-- By Rank -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Karyawan Per Pangkat/Grade</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Pangkat/Grade</th>
                                    <th class="text-end">Jumlah Karyawan</th>
                                    <th class="text-end">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($byRank as $item)
                                    <tr>
                                        <td>{{ $item->rank_name ?? 'Tanpa Pangkat' }}</td>
                                        <td class="text-end"><span class="badge bg-primary">{{ $item->count }}</span></td>
                                        <td class="text-end">
                                            <span class="text-muted small">
                                                {{ $overallStats['total'] > 0 ? round(($item->count / $overallStats['total']) * 100, 2) : 0 }}%
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">Tidak ada data</td>
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
