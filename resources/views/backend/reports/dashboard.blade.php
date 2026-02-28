@extends('layouts.admin')

@section('title', 'Dashboard Laporan')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-line text-primary me-2"></i>Dashboard Laporan
            </h1>
            <p class="text-muted small">Ringkasan keseluruhan statistik sekolah</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('backend.reports.students') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-graduation-cap me-1"></i> Laporan Siswa
            </a>
            <a href="{{ route('backend.reports.admissions') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-clipboard-list me-1"></i> Laporan Seleksi
            </a>
            <a href="{{ route('backend.reports.employees') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-users me-1"></i> Laporan Karyawan
            </a>
        </div>
    </div>

    <!-- Key Metrics Row -->
    <div class="row mb-4">
        <!-- Total Students -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted small mb-1">Total Siswa</h6>
                            <h3 class="mb-0">{{ $key_metrics['students']['total'] }}</h3>
                        </div>
                        <i class="fas fa-graduation-cap text-primary fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Students -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted small mb-1">Siswa Aktif</h6>
                            <h3 class="mb-0">{{ $key_metrics['students']['active'] }}</h3>
                        </div>
                        <i class="fas fa-book text-success fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Registrants -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted small mb-1">Total Pendaftar</h6>
                            <h3 class="mb-0">{{ $key_metrics['admission']['total_registrants'] }}</h3>
                        </div>
                        <i class="fas fa-clipboard-list text-info fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Employees -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted small mb-1">Total Karyawan</h6>
                            <h3 class="mb-0">{{ $key_metrics['employees']['total'] }}</h3>
                        </div>
                        <i class="fas fa-users text-warning fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row mb-4">
        <!-- Student vs Quota -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Siswa vs Kuota</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Tingkat Pengisian</strong>
                            <span class="badge bg-primary">{{ $student_vs_quota['enrollment_rate'] }}%</span>
                        </div>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar" style="width: {{ $student_vs_quota['enrollment_rate'] }}%">
                                {{ $student_vs_quota['enrolled'] }}/{{ $student_vs_quota['total_quota'] }}
                            </div>
                        </div>
                    </div>
                    <div class="row text-center mt-4">
                        <div class="col-4">
                            <h5>{{ $student_vs_quota['total_quota'] }}</h5>
                            <p class="text-muted small">Kuota Total</p>
                        </div>
                        <div class="col-4">
                            <h5>{{ $student_vs_quota['enrolled'] }}</h5>
                            <p class="text-muted small">Terdaftar</p>
                        </div>
                        <div class="col-4">
                            <h5>{{ $student_vs_quota['available_seats'] }}</h5>
                            <p class="text-muted small">Tersedia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admission Funnel -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Corong Seleksi Pendaftaran</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Pendaftar</span>
                            <span class="badge bg-primary">{{ $admission_funnel['total_registrants'] }}</span>
                        </div>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Lolos Seleksi</span>
                            <span class="badge bg-success">{{ $admission_funnel['passed_selection']['count'] }} ({{ $admission_funnel['passed_selection']['rate'] }}%)</span>
                        </div>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-success" style="width: {{ $admission_funnel['passed_selection']['rate'] }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Dikonfirmasi</span>
                            <span class="badge bg-info">{{ $admission_funnel['confirmed']['count'] }} ({{ $admission_funnel['confirmed']['rate'] }}%)</span>
                        </div>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-info" style="width: {{ $admission_funnel['confirmed']['rate'] }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Daftar Ulang</span>
                            <span class="badge bg-warning">{{ $admission_funnel['enrolled']['count'] }} ({{ $admission_funnel['enrolled']['rate'] }}%)</span>
                        </div>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-warning" style="width: {{ $admission_funnel['enrolled']['rate'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Quick Access -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Akses Laporan Lengkap</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Student Reports -->
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('backend.reports.students') }}" class="card text-decoration-none border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-graduation-cap fa-3x text-primary mb-3"></i>
                                    <h6 class="card-title">Laporan Siswa</h6>
                                    <p class="text-muted small mb-0">Statistik lengkap data siswa</p>
                                </div>
                                <div class="card-footer bg-light border-top text-center small">
                                    <span class="text-primary">Lihat Laporan →</span>
                                </div>
                            </a>
                        </div>

                        <!-- Admission Reports -->
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('backend.reports.admissions') }}" class="card text-decoration-none border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-clipboard-list fa-3x text-info mb-3"></i>
                                    <h6 class="card-title">Laporan Seleksi</h6>
                                    <p class="text-muted small mb-0">Analitik pendaftaran & seleksi</p>
                                </div>
                                <div class="card-footer bg-light border-top text-center small">
                                    <span class="text-info">Lihat Laporan →</span>
                                </div>
                            </a>
                        </div>

                        <!-- Employee Reports -->
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('backend.reports.employees') }}" class="card text-decoration-none border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-3x text-warning mb-3"></i>
                                    <h6 class="card-title">Laporan Karyawan</h6>
                                    <p class="text-muted small mb-0">Statistik data karyawan</p>
                                </div>
                                <div class="card-footer bg-light border-top text-center small">
                                    <span class="text-warning">Lihat Laporan →</span>
                                </div>
                            </a>
                        </div>

                        <!-- Academic Analysis -->
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('backend.reports.academic') }}" class="card text-decoration-none border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-chart-bar fa-3x text-success mb-3"></i>
                                    <h6 class="card-title">Analisis Akademik</h6>
                                    <p class="text-muted small mb-0">Perbandingan & tren akademik</p>
                                </div>
                                <div class="card-footer bg-light border-top text-center small">
                                    <span class="text-success">Lihat Laporan →</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Aktivitas Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    @if(!empty($recent_activities))
                        <div class="list-group list-group-flush">
                            @foreach($recent_activities as $activity)
                                <div class="list-group-item d-flex justify-content-between align-items-start py-3">
                                    <div>
                                        <p class="mb-1">
                                            @switch($activity['type'])
                                                @case('student')
                                                    <span class="badge bg-success me-2">Siswa</span>
                                                @break
                                                @case('registrant')
                                                    <span class="badge bg-info me-2">Pendaftar</span>
                                                @break
                                                @case('post')
                                                    <span class="badge bg-primary me-2">Posting</span>
                                                @break
                                            @endswitch
                                            {{ $activity['title'] }}
                                        </p>
                                        <small class="text-muted">{{ $activity['timestamp']->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 text-center text-muted">
                            <p>Tidak ada aktivitas terbaru</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Ringkasan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 pb-3 border-bottom">
                        <strong class="d-block mb-2">Hasil Seleksi</strong>
                        <div class="small">
                            <div class="mb-2">
                                <span class="text-success">Diterima:</span>
                                <span class="float-end">{{ $key_metrics['admission']['passed'] }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-danger">Ditolak:</span>
                                <span class="float-end">{{ $key_metrics['admission']['failed'] }}</span>
                            </div>
                            <div>
                                <span class="text-warning">Menunggu:</span>
                                <span class="float-end">{{ $key_metrics['admission']['pending'] }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 pb-3 border-bottom">
                        <strong class="d-block mb-2">Status Siswa</strong>
                        <div class="small">
                            <div class="mb-2">
                                <span>Aktif:</span>
                                <span class="float-end">{{ $key_metrics['students']['active'] }}</span>
                            </div>
                            <div class="mb-2">
                                <span>Alumni:</span>
                                <span class="float-end">{{ $key_metrics['students']['alumni'] }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="small">
                        <strong class="d-block mb-2">Konten</strong>
                        <div class="mb-1">
                            <span>Posting:</span>
                            <span class="float-end">{{ $key_metrics['content']['total_posts'] }}</span>
                        </div>
                        <div>
                            <span>Komentar:</span>
                            <span class="float-end">{{ $key_metrics['content']['total_comments'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
