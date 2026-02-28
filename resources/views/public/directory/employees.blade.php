@extends('layouts.app')

@section('title', 'Direktori Karyawan')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="h2 mb-2">
                <i class="fas fa-users me-2 text-info"></i>Direktori Karyawan
            </h1>
            <p class="text-muted">Cari dan lihat informasi karyawan sekolah kami</p>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('public.directory.employees') }}" method="GET" class="row g-3">
                        <!-- Search -->
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Cari Nama / NIK / Email</label>
                            <input type="text" name="search" class="form-control"
                                   placeholder="Ketik nama karyawan..."
                                   value="{{ request('search') }}">
                        </div>

                        <!-- Employment Type Filter -->
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Jenis Kepegawaian</label>
                            <select name="employment_type" class="form-select">
                                <option value="">Semua Jenis</option>
                                @foreach($employmentTypes as $type)
                                    <option value="{{ $type->id }}"
                                            {{ request('employment_type') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                @foreach($employmentStatuses as $st)
                                    <option value="{{ $st->id }}"
                                            {{ request('status') == $st->id ? 'selected' : '' }}>
                                        {{ $st->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Gender Filter -->
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Jenis Kelamin</label>
                            <select name="gender" class="form-select">
                                <option value="">Semua</option>
                                <option value="M" {{ request('gender') == 'M' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="F" {{ request('gender') == 'F' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <!-- Search Button -->
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-1"></i> Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Employees Grid -->
    <div class="row">
        @forelse($employees as $employee)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100 transition-all hover-shadow">
                    <div class="card-body">
                        <!-- Photo -->
                        <div class="text-center mb-3">
                            @if($employee->photo)
                                <img src="{{ asset('storage/' . $employee->photo) }}"
                                     alt="{{ $employee->full_name }}"
                                     class="rounded-circle"
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center"
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-user fa-2x text-muted"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <h5 class="card-title text-center mb-1">{{ $employee->full_name }}</h5>
                        <p class="text-muted text-center small mb-3">
                            @if($employee->employmentType)
                                <i class="fas fa-briefcase me-1"></i>{{ $employee->employmentType->name }}
                            @else
                                <em>Jenis tidak tersedia</em>
                            @endif
                        </p>

                        <!-- Details -->
                        <div class="small mb-3">
                            @if($employee->nik)
                                <div class="mb-2">
                                    <span class="text-muted">NIK:</span>
                                    <strong>{{ $employee->nik }}</strong>
                                </div>
                            @endif

                            @if($employee->nip)
                                <div class="mb-2">
                                    <span class="text-muted">NIP:</span>
                                    <strong>{{ $employee->nip }}</strong>
                                </div>
                            @endif

                            @if($employee->email)
                                <div class="mb-2">
                                    <span class="text-muted">Email:</span><br>
                                    <a href="mailto:{{ $employee->email }}" class="text-primary text-decoration-none">
                                        {{ $employee->email }}
                                    </a>
                                </div>
                            @endif

                            @if($employee->phone)
                                <div class="mb-0">
                                    <span class="text-muted">Telepon:</span><br>
                                    <a href="tel:{{ $employee->phone }}" class="text-primary text-decoration-none">
                                        {{ $employee->phone }}
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- View Profile Button -->
                        <a href="{{ route('public.directory.employee.profile', $employee) }}"
                           class="btn btn-sm btn-outline-info w-100">
                            <i class="fas fa-eye me-1"></i> Lihat Profil
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-info-circle fa-2x mb-3 d-block text-info"></i>
                    <p class="mb-0">Tidak ada karyawan yang ditemukan</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($employees->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                {{ $employees->links() }}
            </div>
        </div>
    @endif
</div>

<style>
.transition-all {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
    transform: translateY(-5px);
}
</style>
@endsection
