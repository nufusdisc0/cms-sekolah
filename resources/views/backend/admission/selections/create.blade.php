@extends('layouts.admin')

@section('title', 'Buat Proses Seleksi Baru')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-plus-circle text-primary"></i> Buat Proses Seleksi Baru
            </h1>
            <p class="text-muted small">Siapkan proses seleksi untuk fase pendaftaran yang dipilih</p>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('backend.selection.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf

                <!-- Card: Basic Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Informasi Dasar</h5>
                    </div>
                    <div class="card-body">
                        <!-- Admission Phase -->
                        <div class="mb-3">
                            <label for="admission_phase_id" class="form-label">
                                Fase Pendaftaran <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('admission_phase_id') is-invalid @enderror"
                                    id="admission_phase_id"
                                    name="admission_phase_id"
                                    required>
                                <option value="">-- Pilih Fase Pendaftaran --</option>
                                @forelse ($phases as $phase)
                                    <option value="{{ $phase->id }}" {{ old('admission_phase_id') == $phase->id ? 'selected' : '' }}>
                                        {{ $phase->name }}
                                        ({{ $phase->registrants()->submitted()->count() }} pendaftar)
                                    </option>
                                @empty
                                    <option value="" disabled>Tidak ada fase pendaftaran aktif</option>
                                @endforelse
                            </select>
                            @error('admission_phase_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted d-block mt-2">
                                <i class="fas fa-info-circle"></i> Pilih fase pendaftaran untuk seleksi ini
                            </small>
                        </div>

                        <!-- Selection Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Nama Seleksi <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="Contoh: Seleksi Round 1, Seleksi Final"
                                   required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-0">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="3"
                                      placeholder="Deskripsi lengkap proses seleksi ini">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Card: Selection Configuration -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Konfigurasi Seleksi</h5>
                    </div>
                    <div class="card-body">
                        <!-- Ranking Method -->
                        <div class="mb-3">
                            <label for="ranking_method" class="form-label">
                                Metode Ranking <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('ranking_method') is-invalid @enderror"
                                    id="ranking_method"
                                    name="ranking_method"
                                    required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="quota_based" {{ old('ranking_method') === 'quota_based' ? 'selected' : '' }}>
                                    Berbasis Kuota (Rekomendasi)
                                </option>
                                <option value="score_based" {{ old('ranking_method') === 'score_based' ? 'selected' : '' }}>
                                    Berbasis Nilai
                                </option>
                                <option value="merit_list" {{ old('ranking_method') === 'merit_list' ? 'selected' : '' }}>
                                    Daftar Jasa
                                </option>
                                <option value="round_robin" {{ old('ranking_method') === 'round_robin' ? 'selected' : '' }}>
                                    Round Robin
                                </option>
                            </select>
                            @error('ranking_method')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted d-block mt-2">
                                <strong>Berbasis Kuota:</strong> Alokasi kursi per jurusan<br>
                                <strong>Berbasis Nilai:</strong> Ranking berdasarkan total nilai<br>
                                <strong>Daftar Jasa:</strong> Merit-based ranking
                            </small>
                        </div>

                        <!-- Choice Method -->
                        <div class="mb-3">
                            <label for="choice_method" class="form-label">
                                Metode Pilihan <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('choice_method') is-invalid @enderror"
                                    id="choice_method"
                                    name="choice_method"
                                    required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="first_choice" {{ old('choice_method') === 'first_choice' ? 'selected' : '' }}>
                                    Pilihan Pertama Saja
                                </option>
                                <option value="best_match" {{ old('choice_method') === 'best_match' ? 'selected' : '' }}>
                                    Kecocokan Terbaik
                                </option>
                                <option value="alternative" {{ old('choice_method') === 'alternative' ? 'selected' : '' }}>
                                    Alternatif jika Penuh
                                </option>
                            </select>
                            @error('choice_method')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted d-block mt-2">
                                Bagaimana menangani pilihan program studi alternatif
                            </small>
                        </div>

                        <!-- Total Quota -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="total_quota" class="form-label">
                                    Total Kuota <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                       class="form-control @error('total_quota') is-invalid @enderror"
                                       id="total_quota"
                                       name="total_quota"
                                       value="{{ old('total_quota', 100) }}"
                                       min="1"
                                       required>
                                @error('total_quota')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Total kursi tersedia</small>
                            </div>

                            <!-- Batch Size -->
                            <div class="col-md-6 mb-3">
                                <label for="batch_size" class="form-label">
                                    Ukuran Batch <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                       class="form-control @error('batch_size') is-invalid @enderror"
                                       id="batch_size"
                                       name="batch_size"
                                       value="{{ old('batch_size', 50) }}"
                                       min="10"
                                       max="1000"
                                       required>
                                @error('batch_size')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Jumlah peserta per batch</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Scoring Configuration -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Konfigurasi Penilaian <span class="badge bg-info">Opsional</span></h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">
                            Tetapkan bobot penilaian untuk setiap komponen (opsional, sistem akan menggunakan default jika tidak diisi)
                        </p>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bobot Nilai Tes (%)</label>
                                <input type="number" class="form-control" name="test_weight" min="0" max="100" value="30">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bobot Nilai Akademik (%)</label>
                                <input type="number" class="form-control" name="academic_weight" min="0" max="100" value="20">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bobot Nilai Wawancara (%)</label>
                                <input type="number" class="form-control" name="interview_weight" min="0" max="100" value="20">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bobot Ekstrakurikuler (%)</label>
                                <input type="number" class="form-control" name="extracurricular_weight" min="0" max="100" value="15">
                            </div>
                            <div class="col-md-6 mb-0">
                                <label class="form-label">Bobot Lain-lain (%)</label>
                                <input type="number" class="form-control" name="other_weight" min="0" max="100" value="15">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex gap-2">
                    <a href="{{ route('backend.selection.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-1"></i> Buat Seleksi
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Sidebar: Information -->
        <div class="col-lg-4">
            <!-- Help Card -->
            <div class="card border-0 shadow-sm mb-4 bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-lightbulb text-warning"></i> Tips Pemilihan Metode
                    </h6>
                    <ul class="small mb-0">
                        <li><strong>Kuota Berbasis:</strong> Terbaik untuk alokasi per jurusan</li>
                        <li><strong>Nilai Berbasis:</strong> Untuk ranking murni berdasarkan nilai</li>
                        <li><strong>Merit List:</strong> Untuk sistem penilaian kompleks</li>
                    </ul>
                </div>
            </div>

            <!-- Notes Card -->
            <div class="card border-0 shadow-sm bg-info bg-opacity-10">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle text-info"></i> Catatan Penting
                    </h6>
                    <ul class="small text-muted mb-0">
                        <li>Seleksi hanya dapat dibuat untuk fase dengan peserta terdaftar</li>
                        <li>Status draft dapat diubah sebelum eksekusi</li>
                        <li>Setelah dijalankan, seleksi tidak dapat diedit</li>
                        <li>Backup data sebelum menjalankan seleksi besar</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        this.classList.add('was-validated');
    });

    // Show registrant count when phase changes
    document.getElementById('admission_phase_id').addEventListener('change', function() {
        const phaseId = this.value;
        if (phaseId) {
            // Optional: Fetch registrant count via AJAX
            console.log('Phase selected:', phaseId);
        }
    });

    // Validate weights sum to 100 (optional)
    const weightInputs = document.querySelectorAll('input[name$="_weight"]');
    function validateWeights() {
        const sum = Array.from(weightInputs).reduce((acc, input) => {
            return acc + (parseInt(input.value) || 0);
        }, 0);

        if (sum > 0 && sum !== 100) {
            console.warn('Warning: Total weight is ' + sum + '%, not 100%');
        }
    }

    weightInputs.forEach(input => {
        input.addEventListener('change', validateWeights);
    });
</script>
@endpush
@endsection
