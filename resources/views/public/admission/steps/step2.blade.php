<!-- STEP 2: CONTACT & ADDRESS INFORMATION -->
<div class="card border-0">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <span class="badge bg-light text-dark me-2">2</span>
            Informasi Kontak & Alamat
        </h5>
    </div>

    <form action="{{ route('admission.step2') }}" method="POST" id="step2-form" class="needs-validation" novalidate>
        @csrf

        <!-- Hidden: Data from previous step -->
        @foreach (session('admission_form', []) as $key => $value)
            @if (!in_array($key, ['email', 'phone', 'address', 'district', 'city', 'province', 'postal_code']))
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endforeach

        <div class="card-body">
            <!-- Email & Phone -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">
                        Email <span class="text-danger">*</span>
                    </label>
                    <input type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="nama@example.com"
                           required>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Email aktif untuk notifikasi</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">
                        No. Telepon <span class="text-danger">*</span>
                    </label>
                    <input type="tel"
                           class="form-control @error('phone') is-invalid @enderror"
                           id="phone"
                           name="phone"
                           value="{{ old('phone') }}"
                           placeholder="+62-812-3456-7890"
                           required>
                    @error('phone')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Nomor dapat dihubungi</small>
                </div>
            </div>

            <!-- Address -->
            <div class="mb-3">
                <label for="address" class="form-label">
                    Alamat <span class="text-danger">*</span>
                </label>
                <textarea class="form-control @error('address') is-invalid @enderror"
                          id="address"
                          name="address"
                          rows="3"
                          placeholder="Jl. ... No. ..."
                          required>{{ old('address') }}</textarea>
                @error('address')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Alamat lengkap termasuk nomor rumah/gedung</small>
            </div>

            <!-- Location Info -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="city" class="form-label">
                        Kota/Kabupaten <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           class="form-control @error('city') is-invalid @enderror"
                           id="city"
                           name="city"
                           value="{{ old('city') }}"
                           placeholder="Contoh: Jakarta Pusat"
                           required>
                    @error('city')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="province" class="form-label">
                        Provinsi <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           class="form-control @error('province') is-invalid @enderror"
                           id="province"
                           name="province"
                           value="{{ old('province') }}"
                           placeholder="Contoh: DKI Jakarta"
                           required>
                    @error('province')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- District & Postal Code -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="district" class="form-label">Kelurahan/Desa</label>
                    <input type="text"
                           class="form-control @error('district') is-invalid @enderror"
                           id="district"
                           name="district"
                           value="{{ old('district') }}"
                           placeholder="Contoh: Menteng">
                    @error('district')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="postal_code" class="form-label">Kode Pos</label>
                    <input type="text"
                           class="form-control @error('postal_code') is-invalid @enderror"
                           id="postal_code"
                           name="postal_code"
                           value="{{ old('postal_code') }}"
                           placeholder="12345"
                           maxlength="10">
                    @error('postal_code')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Info Alert -->
            <div class="alert alert-info small" role="alert">
                <i class="fas fa-info-circle"></i>
                <strong>Catatan:</strong> Data kontak yang Anda berikan digunakan untuk mengirimkan hasil seleksi dan informasi penting lainnya.
            </div>
        </div>

        <div class="card-footer bg-light d-flex justify-content-between">
            <button type="button" class="btn btn-outline-secondary" onclick="window.history.back();">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Langkah 1
            </button>
            <button type="submit" class="btn btn-primary">
                Lanjutkan ke Langkah 3 <i class="fas fa-arrow-right ms-1"></i>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Form validation
    document.getElementById('step2-form').addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        this.classList.add('was-validated');
    });

    // Phone number formatting
    document.getElementById('phone').addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9+\-]/g, '');
    });

    // Postal code - only numbers
    document.getElementById('postal_code').addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>
@endpush
