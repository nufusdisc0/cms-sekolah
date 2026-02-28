<!-- STEP 4: ACADEMIC INFORMATION & DOCUMENTS -->
<div class="card border-0">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <span class="badge bg-light text-dark me-2">4</span>
            Informasi Akademik & Dokumen (Langkah Terakhir)
        </h5>
    </div>

    <form action="{{ route('admission.submit') }}" method="POST" id="step4-form" class="needs-validation" novalidate enctype="multipart/form-data">
        @csrf

        <!-- Hidden: Data from previous steps -->
        @foreach (session('admission_form', []) as $key => $value)
            @if (!in_array($key, ['admission_phase_id', 'major', 'admission_type', 'previous_school', 'previous_gpa', 'graduation_year', 'photo', 'documents']))
                <input type="hidden" name="{{ $key }}" value="{{ is_array($value) ? json_encode($value) : $value }}">
            @endif
        @endforeach

        <div class="card-body">
            <div class="alert alert-info small mb-4" role="alert">
                <i class="fas fa-info-circle"></i>
                <strong>Catatan:</strong> Pastikan semua dokumen sudah disiapkan dengan format yang benar sebelum mengulang proses.
            </div>

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
                            ({{ \Carbon\Carbon::parse($phase->start_date)->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::parse($phase->end_date)->format('d/m/Y') }})
                        </option>
                    @empty
                        <option value="" disabled>Tidak ada fase pendaftaran yang aktif</option>
                    @endforelse
                </select>
                @error('admission_phase_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Major Selection -->
            <div class="mb-3">
                <label for="major" class="form-label">
                    Jurusan/Program Studi <span class="text-danger">*</span>
                </label>
                <select class="form-select @error('major') is-invalid @enderror"
                        id="major"
                        name="major"
                        required>
                    <option value="">-- Pilih Jurusan --</option>
                    @forelse ($majors as $major)
                        <option value="{{ $major->name }}" {{ old('major') == $major->name ? 'selected' : '' }}>
                            {{ $major->name }}
                        </option>
                    @empty
                        <option value="" disabled>Tidak ada jurusan yang tersedia</option>
                    @endforelse
                </select>
                @error('major')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Previous School & Education Info -->
            <div class="mb-3">
                <label for="previous_school" class="form-label">
                    Nama Sekolah Asal <span class="text-danger">*</span>
                </label>
                <input type="text"
                       class="form-control @error('previous_school') is-invalid @enderror"
                       id="previous_school"
                       name="previous_school"
                       value="{{ old('previous_school') }}"
                       placeholder="Contoh: SMP Negeri 1 Jakarta"
                       required>
                @error('previous_school')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- GPA & Graduation Year -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="previous_gpa" class="form-label">Nilai Rata-rata (Opsional)</label>
                    <input type="number"
                           class="form-control @error('previous_gpa') is-invalid @enderror"
                           id="previous_gpa"
                           name="previous_gpa"
                           value="{{ old('previous_gpa') }}"
                           placeholder="Contoh: 3.75"
                           step="0.01"
                           min="0"
                           max="4.0">
                    @error('previous_gpa')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Skala 0-4.0</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="graduation_year" class="form-label">
                        Tahun Lulus <span class="text-danger">*</span>
                    </label>
                    <input type="number"
                           class="form-control @error('graduation_year') is-invalid @enderror"
                           id="graduation_year"
                           name="graduation_year"
                           value="{{ old('graduation_year') }}"
                           placeholder="Contoh: 2024"
                           min="{{ date('Y') - 10 }}"
                           max="{{ date('Y') }}"
                           required>
                    @error('graduation_year')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Photo Upload -->
            <div class="mb-3">
                <label for="photo" class="form-label">
                    Foto Diri <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="file"
                           class="form-control @error('photo') is-invalid @enderror"
                           id="photo"
                           name="photo"
                           accept="image/jpeg,image/png,image/jpg"
                           required>
                    <span class="input-group-text">
                        <i class="fas fa-image"></i>
                    </span>
                </div>
                @error('photo')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">
                    Format: JPG, PNG (Maksimal 2MB) - Foto resmi ukuran 4x6 atau 3x4 diperlukan
                </small>

                <!-- Photo Preview -->
                <div id="photo-preview" class="mt-2" style="display: none;">
                    <img id="photo-preview-img" src="#" alt="Foto Preview" class="img-thumbnail" style="max-width: 200px;">
                </div>
            </div>

            <!-- Documents Upload -->
            <div class="mb-3">
                <label for="documents" class="form-label">
                    Dokumen Pendukung (Opsional)
                </label>
                <div class="input-group">
                    <input type="file"
                           class="form-control @error('documents.*') is-invalid @enderror"
                           id="documents"
                           name="documents[]"
                           multiple
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <span class="input-group-text">
                        <i class="fas fa-paperclip"></i>
                    </span>
                </div>
                @error('documents.*')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">
                    Format: PDF, Word, JPG, PNG (Maksimal 5MB per file) - Contoh: Rapor, Sertifikat, Piagam
                </small>

                <!-- File List -->
                <div id="documents-list" class="mt-2"></div>
            </div>

            <!-- Agreement/Consent -->
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="data_agreement" required>
                <label class="form-check-label" for="data_agreement">
                    Saya menyatakan bahwa semua data yang saya masukkan adalah benar dan akan menerima konsekuensi jika terbukti ada data yang salah.
                </label>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="privacy_agreement" required>
                <label class="form-check-label" for="privacy_agreement">
                    Saya setuju dengan <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#privacyModal">kebijakan privasi</a> dan penggunaan data pribadi saya.
                </label>
            </div>

            <!-- reCAPTCHA v3 Hidden Token -->
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
        </div>

        <div class="card-footer bg-light d-flex justify-content-between">
            <button type="button" class="btn btn-outline-secondary" onclick="window.history.back();">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Langkah 3
            </button>
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-check me-1"></i> Serahkan Pendaftaran
            </button>
        </div>
    </form>
</div>

<!-- Privacy Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kebijakan Privasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>Penggunaan Data Pribadi</h6>
                <p>Data pribadi Anda akan digunakan untuk:</p>
                <ul>
                    <li>Proses pendaftaran dan seleksi</li>
                    <li>Komunikasi terkait hasil seleksi</li>
                    <li>Administrasi akademik</li>
                    <li>Keperluan yang mandapatkan persetujuan khusus</li>
                </ul>
                <p>Data Anda dijaga keamanannya dan tidak akan dibagikan kepada pihak ketiga tanpa persetujuan Anda.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Form validation
    document.getElementById('step4-form').addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        this.classList.add('was-validated');
    });

    // Photo preview
    document.getElementById('photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('photo-preview-img').src = event.target.result;
                document.getElementById('photo-preview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    // Documents list
    document.getElementById('documents').addEventListener('change', function(e) {
        const files = e.target.files;
        let html = '<div class="list-group mt-2">';

        for (let file of files) {
            html += `
                <div class="list-group-item">
                    <small>
                        <i class="fas fa-file"></i> ${file.name}
                        <span class="text-muted">(${(file.size / 1024).toFixed(2)} KB)</span>
                    </small>
                </div>
            `;
        }
        html += '</div>';
        document.getElementById('documents-list').innerHTML = html;
    });

    // Year input - limit to valid range
    document.getElementById('graduation_year').addEventListener('change', function() {
        const year = parseInt(this.value);
        const minYear = {{ date('Y') - 10 }};
        const maxYear = {{ date('Y') }};

        if (year < minYear || year > maxYear) {
            this.setCustomValidity('Tahun harus antara ' + minYear + ' dan ' + maxYear);
        } else {
            this.setCustomValidity('');
        }
    });

    /* =================================
       reCAPTCHA v3 Integration (Phase 4)
       ================================= */
    @php
        $recaptchaEnabled = \App\Services\RecaptchaService::isEnabled();
        $recaptchaSiteKey = $recaptchaEnabled ? \App\Services\RecaptchaService::getAdminKey() : null;
    @endphp

    @if($recaptchaEnabled && $recaptchaSiteKey)
    // Load reCAPTCHA v3 script
    (function() {
        const script = document.createElement('script');
        script.src = 'https://www.google.com/recaptcha/api.js';
        document.head.appendChild(script);

        // Wait for grecaptcha to be ready
        window.grecaptchaReady = false;
        window.__grecaptcha_onChange = function(token) {
            document.getElementById('g-recaptcha-response').value = token;
        };

        // Execute reCAPTCHA on page load
        if (typeof window.grecaptcha === 'undefined') {
            // Wait for script to load
            let checkCount = 0;
            const waitForGreca = setInterval(function() {
                if (typeof window.grecaptcha !== 'undefined' && window.grecaptcha.ready) {
                    window.grecaptcha.ready(function() {
                        executeRecaptcha();
                    });
                    clearInterval(waitForGreca);
                }
                checkCount++;
                if (checkCount > 50) clearInterval(waitForGreca); // Timeout after 5 seconds
            }, 100);
        } else {
            window.grecaptcha.ready(function() {
                executeRecaptcha();
            });
        }

        function executeRecaptcha() {
            window.grecaptcha.execute('{{ $recaptchaSiteKey }}', {action: 'submit'}).then(function(token) {
                document.getElementById('g-recaptcha-response').value = token;
                console.log('reCAPTCHA token generated successfully');
            });
        }
    })();
    @else
    // reCAPTCHA is disabled - token will be empty but form will still work
    console.log('reCAPTCHA v3 is disabled or not configured');
    @endif
</script>
@endpush
