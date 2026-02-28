@extends('layouts.app')

@section('title', 'Direktori Siswa')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="h2 mb-2">
                <i class="fas fa-book me-2 text-success"></i>Direktori Siswa
            </h1>
            <p class="text-muted">Cari dan lihat informasi siswa aktif sekolah kami</p>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('public.directory.students') }}" method="GET" class="row g-3">
                        <!-- Search -->
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Cari Nama / NISN / Email</label>
                            <input type="text" name="search" class="form-control"
                                   placeholder="Ketik nama siswa..."
                                   value="{{ request('search') }}">
                        </div>

                        <!-- Major Filter -->
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Jurusan</label>
                            <select name="major" class="form-select">
                                <option value="">Semua Jurusan</option>
                                @foreach($majors as $major)
                                    <option value="{{ $major->id }}"
                                            {{ request('major') == $major->id ? 'selected' : '' }}>
                                        {{ $major->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Class Group Filter -->
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Rombongan Belajar</label>
                            <select name="class_group" class="form-select">
                                <option value="">Semua Rombel</option>
                                @foreach($classGroups as $cg)
                                    <option value="{{ $cg->id }}"
                                            {{ request('class_group') == $cg->id ? 'selected' : '' }}>
                                        {{ $cg->name }}
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

    <!-- Students Grid -->
    <div class="row">
        @forelse($students as $student)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100 transition-all hover-shadow">
                    <div class="card-body">
                        <!-- Photo -->
                        <div class="text-center mb-3">
                            @if($student->photo)
                                <img src="{{ asset('storage/' . $student->photo) }}"
                                     alt="{{ $student->full_name }}"
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
                        <h5 class="card-title text-center mb-1">{{ $student->full_name }}</h5>
                        <p class="text-muted text-center small mb-1">
                            @if($student->major)
                                <i class="fas fa-graduation-cap me-1"></i>{{ $student->major->name }}
                            @endif
                        </p>

                        @if($student->classGroups->count() > 0)
                            <p class="text-muted text-center small mb-3">
                                <i class="fas fa-door-open me-1"></i>{{ $student->classGroups->first()->name }}
                            </p>
                        @endif

                        <!-- Details -->
                        <div class="small mb-3">
                            @if($student->nisn)
                                <div class="mb-2">
                                    <span class="text-muted">NISN:</span>
                                    <strong>{{ $student->nisn }}</strong>
                                </div>
                            @endif

                            @if($student->email)
                                <div class="mb-2">
                                    <span class="text-muted">Email:</span><br>
                                    <a href="mailto:{{ $student->email }}" class="text-primary text-decoration-none">
                                        {{ $student->email }}
                                    </a>
                                </div>
                            @endif

                            @if($student->phone)
                                <div class="mb-0">
                                    <span class="text-muted">Telepon:</span><br>
                                    <a href="tel:{{ $student->phone }}" class="text-primary text-decoration-none">
                                        {{ $student->phone }}
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- View Profile Button -->
                        <a href="{{ route('public.directory.student.profile', $student) }}"
                           class="btn btn-sm btn-outline-success w-100">
                            <i class="fas fa-eye me-1"></i> Lihat Profil
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-info-circle fa-2x mb-3 d-block text-info"></i>
                    <p class="mb-0">Tidak ada siswa yang ditemukan</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($students->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                {{ $students->links() }}
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
