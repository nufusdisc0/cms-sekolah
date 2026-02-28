<!-- STEP 1: PERSONAL INFORMATION -->
<div class="card border-0">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <span class="badge bg-light text-dark me-2">1</span>
            Informasi Pribadi
        </h5>
    </div>

    <form action="{{ route('admission.step1') }}" method="POST" id="step1-form" class="needs-validation" novalidate>
        @csrf

        <div class="card-body">
            <div class="mb-3">
                <label for="full_name" class="form-label">
                    Nama Lengkap <span class="text-danger">*</span>
                </label>
                <input type="text"
                       class="form-control @error('full_name') is-invalid @enderror"
                       id="full_name"
                       name="full_name"
                       value="{{ old('full_name') }}"
                       placeholder="Masukkan nama lengkap Anda"
                       required>
                @error('full_name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Sesuai dengan akta kelahiran atau identitas resmi</small>
            </div>

            <!-- NISN & NIK Row -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nisn" class="form-label">NISN</label>
                    <input type="text"
                           class="form-control @error('nisn') is-invalid @enderror"
                           id="nisn"
                           name="nisn"
                           value="{{ old('nisn') }}"
                           placeholder="Nomor Induk Siswa Nasional"
                           maxlength="20">
                    @error('nisn')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">10 digit (jika ada)</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nik" class="form-label">NIK</label>
                    <input type="text"
                           class="form-control @error('nik') is-invalid @enderror"
                           id="nik"
                           name="nik"
                           value="{{ old('nik') }}"
                           placeholder="Nomor Induk Kependudukan"
                           maxlength="20">
                    @error('nik')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">16 digit dari KTP/Paspor</small>
                </div>
            </div>

            <!-- Gender & Birth Info Row -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="gender" class="form-label">
                        Jenis Kelamin <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('gender') is-invalid @enderror"
                            id="gender"
                            name="gender"
                            required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="M" {{ old('gender') === 'M' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="F" {{ old('gender') === 'F' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="birth_place" class="form-label">
                        Tempat Lahir <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           class="form-control @error('birth_place') is-invalid @enderror"
                           id="birth_place"
                           name="birth_place"
                           value="{{ old('birth_place') }}"
                           placeholder="Contoh: Jakarta"
                           required>
                    @error('birth_place')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Birth Date -->
            <div class="mb-3">
                <label for="birth_date" class="form-label">
                    Tanggal Lahir <span class="text-danger">*</span>
                </label>
                <input type="date"
                       class="form-control @error('birth_date') is-invalid @enderror"
                       id="birth_date"
                       name="birth_date"
                       value="{{ old('birth_date') }}"
                       required>
                @error('birth_date')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Harus sebelum hari ini</small>
            </div>

            <!-- Info Alert -->
            <div class="alert alert-info small" role="alert">
                <i class="fas fa-info-circle"></i>
                <strong>Catatan:</strong> Data pribadi yang Anda masukkan harus sesuai dengan dokumen identitas resmi (akta kelahiran, KTP, atau paspor).
            </div>
        </div>

        <div class="card-footer bg-light d-flex justify-content-between">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Beranda
            </a>
            <button type="submit" class="btn btn-primary">
                Lanjutkan ke Langkah 2 <i class="fas fa-arrow-right ms-1"></i>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Form validation
    document.getElementById('step1-form').addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        this.classList.add('was-validated');
    });

    // Additional validations
    document.getElementById('nisn').addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    document.getElementById('nik').addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    document.getElementById('birth_date').addEventListener('change', function() {
        const birthDate = new Date(this.value);
        const today = new Date();
        if (birthDate >= today) {
            this.setCustomValidity('Tanggal lahir harus sebelum hari ini');
        } else {
            this.setCustomValidity('');
        }
    });
</script>
@endpush
