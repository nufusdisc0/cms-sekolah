@extends('layouts.admin')

@section('title', 'Hasil Seleksi - ' . $selection->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-list text-primary"></i> Hasil Seleksi
            </h1>
            <p class="text-muted small">{{ $selection->name }} • Total: {{ $results->total() }} hasil</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('backend.selection.export', $selection) }}" class="btn btn-outline-success me-2">
                <i class="fas fa-download me-1"></i> Ekspor CSV
            </a>
            <a href="{{ route('backend.selection.show', $selection) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Filter & Pengurutan</h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <!-- Filter by Result Status -->
                <div class="col-md-3">
                    <label for="result" class="form-label">Status Hasil</label>
                    <select class="form-select form-select-sm" id="result" name="result">
                        <option value="">Semua Status</option>
                        <option value="passed" {{ request('result') === 'passed' ? 'selected' : '' }}>
                            <i class="fas fa-check"></i> Diterima
                        </option>
                        <option value="failed" {{ request('result') === 'failed' ? 'selected' : '' }}>
                            <i class="fas fa-times"></i> Ditolak
                        </option>
                        <option value="waitlisted" {{ request('result') === 'waitlisted' ? 'selected' : '' }}>
                            <i class="fas fa-clock"></i> Dalam Antrian
                        </option>
                        <option value="pending" {{ request('result') === 'pending' ? 'selected' : '' }}>
                            <i class="fas fa-hourglass"></i> Menunggu
                        </option>
                    </select>
                </div>

                <!-- Filter by Major -->
                <div class="col-md-3">
                    <label for="major" class="form-label">Jurusan</label>
                    <input type="text"
                           class="form-control form-control-sm"
                           id="major"
                           name="major"
                           placeholder="Filter jurusan..."
                           value="{{ request('major') }}">
                </div>

                <!-- Sort By -->
                <div class="col-md-3">
                    <label for="sort" class="form-label">Urutkan Berdasarkan</label>
                    <select class="form-select form-select-sm" id="sort" name="sort">
                        <option value="rank" {{ request('sort') === 'rank' ? 'selected' : '' }}>Ranking</option>
                        <option value="score" {{ request('sort') === 'score' ? 'selected' : '' }}>Nilai</option>
                    </select>
                </div>

                <!-- Sort Direction -->
                <div class="col-md-2">
                    <label for="direction" class="form-label">Arah</label>
                    <select class="form-select form-select-sm" id="direction" name="direction">
                        <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>Naik</option>
                        <option value="desc" {{ request('direction') === 'desc' ? 'selected' : '' }}>Turun</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Statistics -->
    <div class="row mb-3">
        <div class="col-md-2">
            <div class="card border-0 text-center">
                <div class="card-body p-2">
                    <h5 class="mb-0 text-primary">{{ $results->total() }}</h5>
                    <small class="text-muted">Total Hasil</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 text-center">
                <div class="card-body p-2">
                    <h5 class="mb-0 text-success">{{ $selection->accepted_count }}</h5>
                    <small class="text-muted">Diterima</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 text-center">
                <div class="card-body p-2">
                    <h5 class="mb-0 text-danger">{{ $selection->rejected_count }}</h5>
                    <small class="text-muted">Ditolak</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 text-center">
                <div class="card-body p-2">
                    <h5 class="mb-0 text-warning">{{ $selection->selectionResults()->where('result', 'waitlisted')->count() }}</h5>
                    <small class="text-muted">Antrian</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 text-center">
                <div class="card-body p-2">
                    <h5 class="mb-0 text-info">{{ round($selection->selectionResults()->avg('total_score'), 2) }}</h5>
                    <small class="text-muted">Rata-rata Nilai</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 text-center">
                <div class="card-body p-2">
                    <h5 class="mb-0">{{ $selection->quota_coverage }}%</h5>
                    <small class="text-muted">Coverage Kuota</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if ($results->isEmpty())
                <div class="p-4 text-center text-muted">
                    <i class="fas fa-inbox fa-3x opacity-50 mb-3 d-block"></i>
                    <p>Tidak ada hasil yang sesuai dengan filter</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">Ranking</th>
                                <th>No. Pendaftaran</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Jurusan</th>
                                <th class="text-end">Nilai</th>
                                <th>Status</th>
                                <th style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $result)
                                <tr>
                                    <td>
                                        @if ($result->result === 'passed')
                                            <span class="badge bg-success">{{ $result->rank }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $result->registrant->registration_number }}</strong>
                                    </td>
                                    <td>{{ $result->registrant->full_name }}</td>
                                    <td>
                                        <small>{{ $result->registrant->email }}</small>
                                    </td>
                                    <td>{{ $result->allocated_major ?? '-' }}</td>
                                    <td class="text-end">
                                        <strong>{{ $result->total_score ?? '-' }}</strong>
                                    </td>
                                    <td>
                                        @switch($result->result)
                                            @case('passed')
                                                <span class="badge bg-success">Diterima</span>
                                                @break
                                            @case('failed')
                                                <span class="badge bg-danger">Ditolak</span>
                                                @break
                                            @case('waitlisted')
                                                <span class="badge bg-warning">Antrian</span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-secondary">Menunggu</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="popover"
                                                    title="Detail Nilai"
                                                    data-bs-content="Test: {{ $result->test_score ?? '-' }}<br>Akademik: {{ $result->academic_score ?? '-' }}<br>Wawancara: {{ $result->interview_score ?? '-' }}">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Pagination -->
    @if ($results->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $results->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Initialize popovers
    document.addEventListener('DOMContentLoaded', function() {
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl, {
                html: true
            });
        });
    });
</script>
@endpush
@endsection
