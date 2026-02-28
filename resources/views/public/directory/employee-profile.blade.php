@extends('layouts.app')

@section('title', $employee->full_name . ' - Direktori Karyawan')

@section('content')
<div class="container py-5">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('public.directory.employees') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Direktori
        </a>
    </div>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-body text-center">
                    <!-- Photo -->
                    @if($employee->photo)
                        <img src="{{ asset('storage/' . $employee->photo) }}"
                             alt="{{ $employee->full_name }}"
                             class="rounded-circle mb-3"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 150px; height: 150px;">
                            <i class="fas fa-user fa-4x text-muted"></i>
                        </div>
                    @endif

                    <h3 class="h4 mb-1">{{ $employee->full_name }}</h3>
                    <p class="text-muted mb-3">Karyawan</p>

                    @if($employee->employmentType)
                        <div class="badge bg-warning mb-3" style="font-size: 0.9rem;">
                            {{ $employee->employmentType->name }}
                        </div>
                    @endif

                    <!-- Contact -->
                    <div class="text-start">
                        @if($employee->email)
                            <div class="mb-3 p-2 bg-light rounded">
                                <small class="text-muted d-block mb-1">Email</small>
                                <a href="mailto:{{ $employee->email }}"
                                   class="text-primary text-decoration-none">
                                    {{ $employee->email }}
                                </a>
                            </div>
                        @endif

                        @if($employee->phone)
                            <div class="mb-3 p-2 bg-light rounded">
                                <small class="text-muted d-block mb-1">Telepon</small>
                                <a href="tel:{{ $employee->phone }}"
                                   class="text-primary text-decoration-none">
                                    {{ $employee->phone }}
                                </a>
                            </div>
                        @endif

                        @if($employee->mobile_phone)
                            <div class="p-2 bg-light rounded">
                                <small class="text-muted d-block mb-1">Telepon Genggam</small>
                                <a href="tel:{{ $employee->mobile_phone }}"
                                   class="text-primary text-decoration-none">
                                    {{ $employee->mobile_phone }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-id-card me-2 text-primary"></i>Informasi Pribadi</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Nama Lengkap</p>
                            <p class="mb-0"><strong>{{ $employee->full_name }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Jenis Kelamin</p>
                            <p class="mb-0"><strong>{{ $employee->gender === 'M' ? 'Laki-laki' : 'Perempuan' }}</strong></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">NIK (Nomor Induk Kependudukan)</p>
                            <p class="mb-0"><strong>{{ $employee->nik ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">NUPTK (Nomor Unik Pendidik dan Tenaga Kependidikan)</p>
                            <p class="mb-0"><strong>{{ $employee->nuptk ?? '-' }}</strong></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Tempat Lahir</p>
                            <p class="mb-0"><strong>{{ $employee->birth_place ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Tanggal Lahir</p>
                            <p class="mb-0">
                                <strong>
                                    @if($employee->birth_date)
                                        {{ \Carbon\Carbon::parse($employee->birth_date)->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employment Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-briefcase me-2 text-success"></i>Informasi Kepegawaian</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">NIP (Nomor Induk Pegawai)</p>
                            <p class="mb-0"><strong>{{ $employee->nip ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Jenis Kepegawaian</p>
                            <p class="mb-0">
                                <strong>
                                    {{ $employee->employmentType->name ?? '-' }}
                                </strong>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Status Kepegawaian</p>
                            <p class="mb-0">
                                @if($employee->employmentStatus)
                                    <strong>{{ $employee->employmentStatus->name }}</strong>
                                @else
                                    <strong>-</strong>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Pangkat/Grade</p>
                            <p class="mb-0">
                                @if($employee->rank)
                                    <strong>{{ $employee->rank->name }}</strong>
                                @else
                                    <strong>-</strong>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Tanggal Bergabung</p>
                            <p class="mb-0">
                                <strong>
                                    @if($employee->appointment_date)
                                        {{ \Carbon\Carbon::parse($employee->appointment_date)->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </strong>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Sertifikasi</p>
                            <p class="mb-0">
                                @if($employee->has_certificate)
                                    <span class="badge bg-success">Tersertifikasi</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Tersertifikasi</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($employee->department)
                        <div class="row">
                            <div class="col-12">
                                <p class="text-muted small mb-1">Departemen/Unit</p>
                                <p class="mb-0"><strong>{{ $employee->department }}</strong></p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Address Information -->
            @if($employee->street_address)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2 text-danger"></i>Alamat</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">
                            <strong>{{ $employee->street_address }}</strong><br>
                            <small class="text-muted">
                                @if($employee->village){{ $employee->village }},@endif
                                @if($employee->sub_district){{ $employee->sub_district }},@endif
                                @if($employee->district){{ $employee->district }}@endif
                                @if($employee->postal_code){{ $employee->postal_code }}@endif
                            </small>
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
