<!-- STEP 3: PARENT/GUARDIAN INFORMATION -->
<div class="card border-0">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <span class="badge bg-light text-dark me-2">3</span>
            Informasi Orang Tua/Wali
        </h5>
    </div>

    <form action="{{ route('admission.step3') }}" method="POST" id="step3-form" class="needs-validation" novalidate>
        @csrf

        <!-- Hidden: Data from previous steps -->
        @foreach (session('admission_form', []) as $key => $value)
            @if (!in_array($key, ['parent_name', 'parent_email', 'parent_phone', 'parent_address']))
                <input type="hidden" name="{{ $key }}" value="{{ is_array($value) ? json_encode($value) : $value }}">
            @endif
        @endforeach

        <div class="card-body">
            <div class="alert alert-info small mb-4" role="alert">
                <i class="fas fa-info-circle"></i>
                <strong>Catatan:</strong> Data orang tua/wali diperlukan untuk komunikasi penting terkait proses pendaftaran dan akademik.
            </div>

            <!-- Parent Name -->
            <div class="mb-3">
                <label for="parent_name" class="form-label">
                    Nama Orang Tua/Wali <span class="text-danger">*</span>
                </label>
                <input type="text"
                       class="form-control @error('parent_name') is-invalid @enderror"
                       id="parent_name"
                       name="parent_name"
                       value="{{ old('parent_name') }}"
                       placeholder="Nama lengkap orang tua atau wali"
                       required>
                @error('parent_name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Parent Email & Phone -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="parent_email" class="form-label">
                        Email Orang Tua <span class="text-danger">*</span>
                    </label>
                    <input type="email"
                           class="form-control @error('parent_email') is-invalid @enderror"
                           id="parent_email"
                           name="parent_email"
                           value="{{ old('parent_email') }}"
                           placeholder="orang.tua@example.com"
                           required>
                    @error('parent_email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="parent_phone" class="form-label">
                        No. Telepon Orang Tua <span class="text-danger">*</span>
                    </label>
                    <input type="tel"
                           class="form-control @error('parent_phone') is-invalid @enderror"
                           id="parent_phone"
                           name="parent_phone"
                           value="{{ old('parent_phone') }}"
                           placeholder="+62-812-3456-7890"
                           required>
                    @error('parent_phone')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Parent Address (Optional) -->
            <div class="mb-3">
                <label for="parent_address" class="form-label">Alamat Orang Tua/Wali (jika berbeda dengan alamat siswa)</label>
                <textarea class="form-control @error('parent_address') is-invalid @enderror"
                          id="parent_address"
                          name="parent_address"
                          rows="3"
                          placeholder="Biarkan kosong jika alamat sama dengan siswa">{{ old('parent_address') }}</textarea>
                @error('parent_address')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Parent Type Selection (Optional) -->
            <div class="mb-3">
                <label class="form-label">Hubungan dengan Siswa</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="parent_type" id="parent_type_father" value="father">
                    <label class="form-check-label" for="parent_type_father">
                        Ayah Kandung
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="parent_type" id="parent_type_mother" value="mother">
                    <label class="form-check-label" for="parent_type_mother">
                        Ibu Kandung
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="parent_type" id="parent_type_guardian" value="guardian">
                    <label class="form-check-label" for="parent_type_guardian">
                        Wali
                    </label>
                </div>
            </div>

            <!-- Data Protection Notice -->
            <div class="alert alert-warning small" role="alert">
                <i class="fas fa-shield-alt"></i>
                <strong>Privasi:</strong> Data orang tua/wali dijaga keamanannya dan hanya digunakan untuk keperluan akademik.
            </div>
        </div>

        <div class="card-footer bg-light d-flex justify-content-between">
            <button type="button" class="btn btn-outline-secondary" onclick="window.history.back();">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Langkah 2
            </button>
            <button type="submit" class="btn btn-primary">
                Lanjutkan ke Langkah 4 <i class="fas fa-arrow-right ms-1"></i>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Form validation
    document.getElementById('step3-form').addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        this.classList.add('was-validated');
    });

    // Phone number formatting
    document.getElementById('parent_phone').addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9+\-]/g, '');
    });
</script>
@endpush
