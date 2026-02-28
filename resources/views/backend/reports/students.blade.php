@extends('layouts.admin')

@section('title', 'Laporan Statistik Siswa')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-graduation-cap text-primary me-2"></i>Laporan Statistik Siswa
            </h1>
            <p class="text-muted small">Analisis lengkap data siswa dan distribusinya</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('backend.reports.students.export') }}" class="btn btn-outline-primary btn-sm">
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
                    <h3 class="text-primary mb-1">{{ $overallStats['total'] }}</h3>
                    <p class="text-muted mb-0">Total Siswa</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h3 class="text-success mb-1">{{ $overallStats['active'] }}</h3>
                    <p class="text-muted mb-0">Siswa Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h3 class="text-warning mb-1">{{ $overallStats['alumni'] }}</h3>
                    <p class="text-muted mb-0">Alumni</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h3 class="text-info mb-1">{{ $overallStats['prospective'] }}</h3>
                    <p class="text-muted mb-0">Calon Siswa</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Grids -->
    <div class="row mb-4">
        <!-- By Status -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Siswa Berdasarkan Status</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-sm">
                        @foreach($byStatus as $status => $count)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $status }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $count }}</span>
                            </div>
                        @endforeach
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
                        @foreach($genderDistribution as $gender => $count)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $gender }}</span>
                                <span class="badge bg-info rounded-pill">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrollment Rate & Age Distribution -->
    <div class="row mb-4">
        <!-- Enrollment Rate -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Tingkat Pengisian Kuota</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Pengisian</strong>
                            <span class="badge bg-success">{{ $enrollmentRate['enrollment_rate'] }}%</span>
                        </div>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar" style="width: {{ $enrollmentRate['enrollment_rate'] }}%">
                                {{ $enrollmentRate['total_enrolled'] }}/{{ $enrollmentRate['total_quota'] }}
                            </div>
                        </div>
                    </div>
                    <div class="row text-center mt-3">
                        <div class="col-4">
                            <p class="text-muted small mb-1">Kuota</p>
                            <h5 class="mb-0">{{ $enrollmentRate['total_quota'] }}</h5>
                        </div>
                        <div class="col-4">
                            <p class="text-muted small mb-1">Terisi</p>
                            <h5 class="mb-0">{{ $enrollmentRate['total_enrolled'] }}</h5>
                        </div>
                        <div class="col-4">
                            <p class="text-muted small mb-1">Tersedia</p>
                            <h5 class="mb-0">{{ $enrollmentRate['available_seats'] }}</h5>
                        </div>
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
                                <span>{{ $ageGroup }} tahun</span>
                                <span class="badge bg-warning rounded-pill">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- By Class Group -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Siswa Per Rombongan Belajar</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Rombongan Belajar</th>
                                    <th class="text-end">Jumlah Siswa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($byClassGroup as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td class="text-end"><span class="badge bg-primary">{{ $item->count }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-3">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- By Major -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Siswa Per Jurusan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Jurusan</th>
                                    <th class="text-end">Jumlah Siswa</th>
                                    <th class="text-end">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($byMajor as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td class="text-end"><span class="badge bg-info">{{ $item->count }}</span></td>
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
