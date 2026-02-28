@extends('layouts.admin')

@section('title', 'Edit Seleksi - ' . $selection->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-edit text-primary"></i> Edit Proses Seleksi
            </h1>
            <p class="text-muted small">Edit konfigurasi seleksi (hanya untuk status draft)</p>
        </div>
    </div>

    <!-- Alert: Can only edit draft -->
    @if ($selection->status !== 'draft')
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Informasi:</strong> Seleksi tidak dapat diedit setelah mulai dijalankan. Status saat ini: <strong>{{ ucfirst($selection->status) }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form -->
    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('backend.selection.update', $selection) }}" method="POST" class="needs-validation" novalidate>
                @csrf @method('PUT')

                <!-- Card: Basic Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Informasi Dasar</h5>
                    </div>
                    <div class="card-body">
                        <!-- Display: Admission Phase (read-only) -->
                        <div class="mb-3">
                            <label class="form-label">Fase Pendaftaran</label>
                            <input type="text" class="form-control" value="{{ $selection->admissionPhase->name }}" readonly>
                            <small class="form-text text-muted d-block mt-2">Tidak dapat diubah setelah dibuat</small>
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
                                   value="{{ old('name', $selection->name) }}"
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
                                      rows="3">{{ old('description', $selection->description) }}</textarea>
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
                                <option value="quota_based" {{ old('ranking_method', $selection->ranking_method) === 'quota_based' ? 'selected' : '' }}>
                                    Berbasis Kuota
                                </option>
                                <option value="score_based" {{ old('ranking_method', $selection->ranking_method) === 'score_based' ? 'selected' : '' }}>
                                    Berbasis Nilai
                                </option>
                                <option value="merit_list" {{ old('ranking_method', $selection->ranking_method) === 'merit_list' ? 'selected' : '' }}>
                                    Daftar Jasa
                                </option>
                                <option value="round_robin" {{ old('ranking_method', $selection->ranking_method) === 'round_robin' ? 'selected' : '' }}>
                                    Round Robin
                                </option>
                            </select>
                            @error('ranking_method')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
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
                                <option value="first_choice" {{ old('choice_method', $selection->choice_method) === 'first_choice' ? 'selected' : '' }}>
                                    Pilihan Pertama Saja
                                </option>
                                <option value="best_match" {{ old('choice_method', $selection->choice_method) === 'best_match' ? 'selected' : '' }}>
                                    Kecocokan Terbaik
                                </option>
                                <option value="alternative" {{ old('choice_method', $selection->choice_method) === 'alternative' ? 'selected' : '' }}>
                                    Alternatif jika Penuh
                                </option>
                            </select>
                            @error('choice_method')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
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
                                       value="{{ old('total_quota', $selection->total_quota) }}"
                                       min="1"
                                       required>
                                @error('total_quota')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
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
                                       value="{{ old('batch_size', $selection->batch_size) }}"
                                       min="10"
                                       max="1000"
                                       required>
                                @error('batch_size')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex gap-2">
                    <a href="{{ route('backend.selection.show', $selection) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Sidebar -->
        <div class="col-lg-4">
            <!-- Current Status -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="card-title">Status Saat Ini</h6>
                    <p class="mb-2">
                        <strong>Status:</strong>
                        @switch($selection->status)
                            @case('draft')
                                <span class="badge bg-secondary">Draft</span>
                                @break
                            @default
                                <span class="badge bg-info">{{ ucfirst($selection->status) }}</span>
                        @endswitch
                    </p>
                    <p class="mb-2">
                        <strong>Dibuat:</strong> {{ $selection->created_at->format('d/m/Y H:i') }}
                    </p>
                    <p class="mb-0">
                        <strong>Pembuat:</strong> {{ $selection->createdBy->name ?? '-' }}
                    </p>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card border-0 shadow-sm bg-info bg-opacity-10">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle text-info"></i> Catatan
                    </h6>
                    <p class="small text-muted mb-0">
                        Anda hanya dapat mengedit seleksi yang masih dalam status draft. Setelah dimulai, konfigurasi tidak dapat diubah.
                    </p>
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
</script>
@endpush
@endsection
