@extends('layouts.admin')

@section('title', 'Analisis Akademik')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-bar text-success me-2"></i>Analisis Akademik
            </h1>
            <p class="text-muted small">Analisis perbandingan dan tren akademik sekolah</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('backend.reports.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Student vs Quota Comparison -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Perbandingan Siswa vs Kuota</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Tingkat Pengisian</strong>
                            <span class="badge bg-success">{{ $studentVsQuota['enrollment_rate'] }}%</span>
                        </div>
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar" style="width: {{ $studentVsQuota['enrollment_rate'] }}%">
                                <strong class="position-absolute start-50 translate-middle-x text-white">
                                    {{ $studentVsQuota['total_enrolled'] }}/{{ $studentVsQuota['total_quota'] }}
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Class Group Details -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Rincian Pengisian Per Rombongan Belajar</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Rombongan Belajar</th>
                                    <th class="text-center">Kuota</th>
                                    <th class="text-center">Terisi</th>
                                    <th class="text-center">Kosong</th>
                                    <th class="text-center">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($studentVsQuota['class_groups'] as $cg)
                                    <tr>
                                        <td>{{ $cg->name }}</td>
                                        <td class="text-center">{{ $cg->quota }}</td>
                                        <td class="text-center"><span class="badge bg-success">{{ $cg->enrolled }}</span></td>
                                        <td class="text-center"><span class="badge bg-warning">{{ $cg->quota - $cg->enrolled }}</span></td>
                                        <td class="text-center">
                                            <strong>
                                                {{ $cg->quota > 0 ? round(($cg->enrolled / $cg->quota) * 100, 2) : 0 }}%
                                            </strong>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">Tidak ada data rombongan belajar</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="fas fa-graduation-cap fa-2x"></i>
                    </div>
                    <h3 class="mb-1">{{ $studentStats['overall_statistics']['total'] }}</h3>
                    <p class="text-muted small mb-0">Total Siswa</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                    <h3 class="mb-1">{{ $studentStats['overall_statistics']['active'] }}</h3>
                    <p class="text-muted small mb-0">Siswa Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="fas fa-history fa-2x"></i>
                    </div>
                    <h3 class="mb-1">{{ $studentStats['overall_statistics']['alumni'] }}</h3>
                    <p class="text-muted small mb-0">Alumni</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="fas fa-user-clock fa-2x"></i>
                    </div>
                    <h3 class="mb-1">{{ $studentStats['enrollment_rate']['enrollment_rate'] }}%</h3>
                    <p class="text-muted small mb-0">Tingkat Pengisian</p>
                </div>
            </div>
        </div>
    </div>

    <!-- By Class Group & Major -->
    <div class="row mb-4">
        <!-- Students by Class Group -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Sebaran Siswa Per Rombongan Belajar</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-sm">
                        @forelse($studentStats['by_class_group'] as $item)
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

        <!-- Students by Major -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Sebaran Siswa Per Jurusan</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-sm">
                        @forelse($studentStats['by_major'] as $item)
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

    <!-- Gender & Age Distribution -->
    <div class="row mb-4">
        <!-- Gender Distribution -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Distribusi Jenis Kelamin Siswa</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-sm">
                        @forelse($studentStats['gender_distribution'] as $gender => $count)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $gender }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $count }}</span>
                            </div>
                        @empty
                            <div class="text-center text-muted py-3">Tidak ada data</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Age Distribution -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Distribusi Usia Siswa</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-sm">
                        @forelse($studentStats['age_distribution'] as $ageGroup => $count)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $ageGroup }} tahun</span>
                                <span class="badge bg-warning rounded-pill">{{ $count }}</span>
                            </div>
                        @empty
                            <div class="text-center text-muted py-3">Tidak ada data</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Info -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info mb-0">
                <strong>Catatan:</strong> Analisis akademik ini menggabungkan data siswa aktif, rombongan belajar, dan perbandingan dengan kuota yang tersedia.
                Data diperbarui berdasarkan informasi terkini di sistem.
            </div>
        </div>
    </div>
</div>
@endsection
