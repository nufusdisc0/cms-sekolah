@extends('layouts.app')

@section('title', $student->full_name . ' - Alumni')

@section('content')
<div class="container py-5">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('public.directory.alumni') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Direktori
        </a>
    </div>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-body text-center">
                    <!-- Photo -->
                    @if($student->photo)
                        <img src="{{ asset('storage/' . $student->photo) }}"
                             alt="{{ $student->full_name }}"
                             class="rounded-circle mb-3"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 150px; height: 150px;">
                            <i class="fas fa-user fa-4x text-muted"></i>
                        </div>
                    @endif

                    <h3 class="h4 mb-1">{{ $student->full_name }}</h3>
                    <p class="text-muted mb-3">Alumni</p>

                    @if($student->major)
                        <div class="badge bg-primary mb-3" style="font-size: 0.9rem;">
                            {{ $student->major->name }}
                        </div>
                    @endif

                    <!-- Contact -->
                    <div class="text-start">
                        @if($student->email)
                            <div class="mb-3 p-2 bg-light rounded">
                                <small class="text-muted d-block mb-1">Email</small>
                                <a href="mailto:{{ $student->email }}"
                                   class="text-primary text-decoration-none">
                                    {{ $student->email }}
                                </a>
                            </div>
                        @endif

                        @if($student->phone)
                            <div class="mb-3 p-2 bg-light rounded">
                                <small class="text-muted d-block mb-1">Telepon</small>
                                <a href="tel:{{ $student->phone }}"
                                   class="text-primary text-decoration-none">
                                    {{ $student->phone }}
                                </a>
                            </div>
                        @endif

                        @if($student->mobile_phone)
                            <div class="p-2 bg-light rounded">
                                <small class="text-muted d-block mb-1">Telepon Genggam</small>
                                <a href="tel:{{ $student->mobile_phone }}"
                                   class="text-primary text-decoration-none">
                                    {{ $student->mobile_phone }}
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
                            <p class="mb-0"><strong>{{ $student->full_name }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Jenis Kelamin</p>
                            <p class="mb-0"><strong>{{ $student->gender === 'M' ? 'Laki-laki' : 'Perempuan' }}</strong></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">NISN (Nomor Induk Siswa Nasional)</p>
                            <p class="mb-0"><strong>{{ $student->nisn ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">NIK (Nomor Induk Kependudukan)</p>
                            <p class="mb-0"><strong>{{ $student->nik ?? '-' }}</strong></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Tempat Lahir</p>
                            <p class="mb-0"><strong>{{ $student->birth_place ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Tanggal Lahir</p>
                            <p class="mb-0">
                                <strong>
                                    @if($student->birth_date)
                                        {{ \Carbon\Carbon::parse($student->birth_date)->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-graduate me-2 text-success"></i>Informasi Akademik</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Jurusan</p>
                            <p class="mb-0">
                                <strong>
                                    {{ $student->major->name ?? '-' }}
                                </strong>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Status Alumni</p>
                            <p class="mb-0"><span class="badge bg-success">Alumni</span></p>
                        </div>
                    </div>

                    @if($student->prev_school_name)
                        <div class="row mb-3">
                            <div class="col-12">
                                <p class="text-muted small mb-1">Sekolah Asal</p>
                                <p class="mb-0"><strong>{{ $student->prev_school_name }}</strong></p>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Tahun Masuk</p>
                            <p class="mb-0">
                                <strong>
                                    @if($student->start_date)
                                        {{ \Carbon\Carbon::parse($student->start_date)->year }}
                                    @else
                                        -
                                    @endif
                                </strong>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Tahun Lulus</p>
                            <p class="mb-0">
                                <strong>
                                    @if($student->end_date)
                                        {{ \Carbon\Carbon::parse($student->end_date)->year }}
                                    @else
                                        -
                                    @endif
                                </strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            @if($student->street_address)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2 text-danger"></i>Alamat</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">
                            <strong>{{ $student->street_address }}</strong><br>
                            <small class="text-muted">
                                @if($student->village){{ $student->village }},@endif
                                @if($student->sub_district){{ $student->sub_district }},@endif
                                @if($student->district){{ $student->district }}@endif
                                @if($student->postal_code){{ $student->postal_code }}@endif
                            </small>
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
