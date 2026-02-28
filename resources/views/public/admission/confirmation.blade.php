@extends('layouts.public')

@section('title', 'Konfirmasi Pendaftaran')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Message -->
            <div class="card border-0 mb-4">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h1 class="h2 mb-3">Pendaftaran Berhasil!</h1>
                    <p class="lead text-muted mb-0">
                        Terima kasih telah mendaftar. Silakan periksa email Anda untuk informasi selanjutnya.
                    </p>
                </div>
            </div>

            <!-- Registration Details -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Detail Pendaftaran Anda</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-5">
                            <strong>Nomor Pendaftaran:</strong>
                        </div>
                        <div class="col-sm-7">
                            <span class="badge bg-primary fs-6">{{ $registrant->registration_number }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-5">
                            <strong>Nama Lengkap:</strong>
                        </div>
                        <div class="col-sm-7">
                            {{ $registrant->full_name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-5">
                            <strong>Email:</strong>
                        </div>
                        <div class="col-sm-7">
                            {{ $registrant->email }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-5">
                            <strong>No. Telepon:</strong>
                        </div>
                        <div class="col-sm-7">
                            {{ $registrant->phone }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-5">
                            <strong>Jurusan:</strong>
                        </div>
                        <div class="col-sm-7">
                            {{ $registrant->major }}
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-sm-5">
                            <strong>Tanggal Pendaftaran:</strong>
                        </div>
                        <div class="col-sm-7">
                            {{ $registrant->registration_date->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verification Message -->
            @if (!$registrant->email_verified)
                <div class="alert alert-warning" role="alert">
                    <h5 class="alert-heading">
                        <i class="fas fa-envelope"></i> Verifikasi Email Diperlukan
                    </h5>
                    <p class="mb-0">
                        Kami telah mengirimkan email verifikasi ke <strong>{{ $registrant->email }}</strong>.
                        Silakan periksa inbox atau folder spam Anda dan klik link verifikasi untuk mengaktifkan pendaftaran Anda.
                    </p>
                </div>
            @else
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check"></i> Email Anda telah terverifikasi.
                </div>
            @endif

            <!-- Next Steps -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Langkah Selanjutnya</h5>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li class="mb-3">
                            <strong>Verifikasi Email</strong>
                            <p class="text-muted mb-0">Buka link verifikasi dari email untuk mengaktifkan pendaftaran.</p>
                        </li>
                        <li class="mb-3">
                            <strong>Awaiti Hasil Seleksi</strong>
                            <p class="text-muted mb-0">Kami akan melakukan review terhadap data Anda dan mengumumkan hasil seleksi sesuai jadwal.</p>
                        </li>
                        <li class="mb-3">
                            <strong>Cek Status Pendaftaran</strong>
                            <p class="text-muted mb-0">
                                Gunakan <a href="{{ route('admission.results-lookup') }}">fitur pencarian hasil</a> dengan nomor pendaftaran dan email Anda untuk memantau status.
                            </p>
                        </li>
                        <li class="mb-0">
                            <strong>Konfirmasi Penerimaan</strong>
                            <p class="text-muted mb-0">Jika diterima, ikuti instruksi untuk melakukan konfirmasi penerimaan dan pendaftaran ulang.</p>
                        </li>
                    </ol>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-center mb-4">
                <a href="{{ route('admission.download-pdf', $registrant) }}" class="btn btn-primary" target="_blank">
                    <i class="fas fa-download me-1"></i> Unduh Formulir PDF
                </a>
                <a href="{{ route('admission.results-lookup') }}" class="btn btn-outline-primary">
                    <i class="fas fa-search me-1"></i> Cek Status Pendaftaran
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-home me-1"></i> Kembali ke Beranda
                </a>
            </div>

            <!-- Important Notes -->
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-exclamation-circle text-warning"></i> Catatan Penting
                    </h5>
                    <ul class="mb-0">
                        <li>Simpan nomor pendaftaran Anda dengan baik, akan digunakan untuk cek status seleksi</li>
                        <li>Periksa email secara berkala untuk informasi penting dari sekolah</li>
                        <li>Jangan lupa untuk verifikasi email Anda dalam waktu 24 jam</li>
                        <li>Hubungi sekolah jika ada pertanyaan atau kendala teknis</li>
                        <li>Data pribadi Anda dijaga dengan keamanan tinggi sesuai kebijakan privasi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print Form Modal -->
<div class="modal fade" id="printModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cetak Formulir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Anda akan mengunduh PDF formulir pendaftaran Anda yang dapat disimpan atau dicetak untuk referensi.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="{{ route('admission.download-pdf', $registrant) }}" class="btn btn-primary">
                    <i class="fas fa-download me-1"></i> Download PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-scroll to top
    window.scrollTo(0, 0);

    // Show success message
    document.addEventListener('DOMContentLoaded', function() {
        // Optional: Show toast notification
        if (typeof bootstrap !== 'undefined') {
            // You can add Bootstrap toast here if needed
        }
    });
</script>
@endpush
