@extends('layouts.app')

@section('title', 'Direktori Alumni')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="h2 mb-2">
                <i class="fas fa-graduation-cap me-2 text-primary"></i>Direktori Alumni
            </h1>
            <p class="text-muted">Cari dan lihat informasi alumni sekolah kami</p>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('public.directory.alumni') }}" method="GET" class="row g-3">
                        <!-- Search -->
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Cari Nama / NISN / Email</label>
                            <input type="text" name="search" class="form-control"
                                   placeholder="Ketik nama alumni..."
                                   value="{{ request('search') }}">
                        </div>

                        <!-- Major Filter -->
                        <div class="col-md-4">
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

                        <!-- Year Filter -->
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Tahun Lulus</label>
                            <select name="year" class="form-select">
                                <option value="">Semua Tahun</option>
                                @foreach($graduationYears as $year)
                                    <option value="{{ $year }}"
                                            {{ request('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Search Button -->
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-1"></i> Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Alumni Grid -->
    <div class="row">
        @forelse($alumni as $alumnus)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100 transition-all hover-shadow">
                    <div class="card-body">
                        <!-- Photo -->
                        <div class="text-center mb-3">
                            @if($alumnus->photo)
                                <img src="{{ asset('storage/' . $alumnus->photo) }}"
                                     alt="{{ $alumnus->full_name }}"
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
                        <h5 class="card-title text-center mb-1">{{ $alumnus->full_name }}</h5>
                        <p class="text-muted text-center small mb-3">
                            @if($alumnus->major)
                                {{ $alumnus->major->name }}
                            @else
                                <em>Jurusan tidak tersedia</em>
                            @endif
                        </p>

                        <!-- Details -->
                        <div class="small mb-3">
                            @if($alumnus->nisn)
                                <div class="mb-2">
                                    <span class="text-muted">NISN:</span>
                                    <strong>{{ $alumnus->nisn }}</strong>
                                </div>
                            @endif

                            @if($alumnus->email)
                                <div class="mb-2">
                                    <span class="text-muted">Email:</span><br>
                                    <a href="mailto:{{ $alumnus->email }}" class="text-primary text-decoration-none">
                                        {{ $alumnus->email }}
                                    </a>
                                </div>
                            @endif

                            @if($alumnus->phone)
                                <div class="mb-0">
                                    <span class="text-muted">Telepon:</span><br>
                                    <a href="tel:{{ $alumnus->phone }}" class="text-primary text-decoration-none">
                                        {{ $alumnus->phone }}
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- View Profile Button -->
                        <a href="{{ route('public.directory.alumni.profile', $alumnus) }}"
                           class="btn btn-sm btn-outline-primary w-100">
                            <i class="fas fa-eye me-1"></i> Lihat Profil
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-info-circle fa-2x mb-3 d-block text-info"></i>
                    <p class="mb-0">Tidak ada alumni yang ditemukan</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($alumni->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                {{ $alumni->links() }}
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
