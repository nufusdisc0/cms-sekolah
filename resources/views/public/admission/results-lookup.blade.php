@extends('layouts.public')

@section('title', 'Cek Hasil Pendaftaran')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- Page Header -->
            <div class="card mb-4 border-0 bg-primary text-white">
                <div class="card-body text-center py-4">
                    <h1 class="h2 mb-2">Cek Hasil Pendaftaran</h1>
                    <p class="mb-0">Masukkan nomor pendaftaran dan email Anda untuk melihat status</p>
                </div>
            </div>

            <!-- Form -->
            <div class="card border-0">
                <div class="card-body p-4">
                    <form action="{{ route('admission.check-results') }}" method="POST" id="check-form" class="needs-validation" novalidate>
                        @csrf

                        <!-- Registration Number -->
                        <div class="mb-3">
                            <label for="registration_number" class="form-label">Nomor Pendaftaran</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-barcode"></i>
                                </span>
                                <input type="text"
                                       class="form-control @error('registration_number') is-invalid @enderror"
                                       id="registration_number"
                                       name="registration_number"
                                       value="{{ old('registration_number') }}"
                                       placeholder="Contoh: 2026-00001"
                                       required>
                            </div>
                            @error('registration_number')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted d-block mt-2">
                                <i class="fas fa-info-circle"></i>
                                Nomor ini diberikan saat Anda menyelesaikan formulir pendaftaran
                            </small>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="email@example.com"
                                       required>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted d-block mt-2">
                                <i class="fas fa-info-circle"></i>
                                Email yang digunakan saat mendaftar
                            </small>
                        </div>

                        <!-- Error Message -->
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-search me-2"></i> Cek Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Section -->
            <div class="card mt-4 border-0 bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-question-circle text-info"></i> Lupa Nomor Pendaftaran?
                    </h6>
                    <p class="mb-0">
                        Jika Anda lupa nomor pendaftaran, hubungi bagian penerimaan siswa melalui:
                    </p>
                    <ul class="mb-0 mt-2 small">
                        <li><strong>Email:</strong> <a href="mailto:admissions@school.edu">admissions@school.edu</a></li>
                        <li><strong>Telepon:</strong> (XXX) XXXX-XXXX</li>
                        <li><strong>Jam Operasional:</strong> Senin-Jumat, 08:00-16:00 WIB</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('check-form').addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        this.classList.add('was-validated');
    });

    // Format registration number as user types
    document.getElementById('registration_number').addEventListener('input', function() {
        let value = this.value.replace(/[^0-9\-]/g, '');
        this.value = value;
    });
</script>
@endpush
@endsection
