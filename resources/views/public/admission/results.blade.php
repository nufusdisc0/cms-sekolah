@extends('layouts.public')

@section('title', 'Hasil Pendaftaran')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Back Button -->
            <a href="{{ route('admission.results-lookup') }}" class="btn btn-outline-secondary mb-4">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>

            <!-- Status Card -->
            <div class="card border-0 mb-4">
                <div class="card-body text-center py-5">
                    @switch($registrant->selection_status)
                        @case('passed')
                            <div class="mb-4">
                                <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                            </div>
                            <h2 class="h3 text-success mb-3">Selamat!</h2>
                            <p class="lead text-muted mb-0">Anda <strong>DITERIMA</strong> sebagai calon siswa baru</p>
                            @break

                        @case('failed')
                            <div class="mb-4">
                                <i class="fas fa-times-circle text-danger" style="font-size: 3rem;"></i>
                            </div>
                            <h2 class="h3 text-danger mb-3">Hasil Seleksi</h2>
                            <p class="lead text-muted mb-0">Sayangnya, Anda <strong>TIDAK DITERIMA</strong> dalam seleksi kali ini</p>
                            @break

                        @default
                            <div class="mb-4">
                                <i class="fas fa-hourglass-end text-warning" style="font-size: 3rem;"></i>
                            </div>
                            <h2 class="h3 text-warning mb-3">Status: Sedang Diproses</h2>
                            <p class="lead text-muted mb-0">Pendaftaran Anda masih dalam proses review</p>
                    @endswitch
                </div>
            </div>

            <!-- Status Information -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Pendaftaran</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>Nomor Pendaftaran:</strong></div>
                        <div class="col-sm-7">
                            <span class="badge bg-primary">{{ $registrant->registration_number }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>Nama:</strong></div>
                        <div class="col-sm-7">{{ $registrant->full_name }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>Jurusan:</strong></div>
                        <div class="col-sm-7">{{ $registrant->major }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>Status Pendaftaran:</strong></div>
                        <div class="col-sm-7">
                            @switch($registrant->application_status)
                                @case('submitted')
                                    <span class="badge bg-info">Dikirim</span>
                                    @break
                                @case('under_review')
                                    <span class="badge bg-warning">Sedang Diproses</span>
                                    @break
                                @case('confirmed')
                                    <span class="badge bg-success">Dikonfirmasi</span>
                                    @break
                                @case('enrolled')
                                    <span class="badge bg-success">Terdaftar</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ $registrant->application_status }}</span>
                            @endswitch
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>Status Seleksi:</strong></div>
                        <div class="col-sm-7">
                            @switch($registrant->selection_status)
                                @case('pending')
                                    <span class="badge bg-warning">Menunggu</span>
                                    @break
                                @case('passed')
                                    <span class="badge bg-success">Lulus Seleksi</span>
                                    @break
                                @case('failed')
                                    <span class="badge bg-danger">Tidak Lulus Seleksi</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ $registrant->selection_status }}</span>
                            @endswitch
                        </div>
                    </div>

                    @if ($registrant->selection_date)
                        <div class="row mb-0">
                            <div class="col-sm-5"><strong>Tanggal Seleksi:</strong></div>
                            <div class="col-sm-7">{{ $registrant->selection_date->format('d/m/Y H:i') }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Next Steps Based on Status -->
            @if ($registrant->selection_status === 'passed')
                <div class="card border-0 mb-4 bg-success bg-opacity-10">
                    <div class="card-body">
                        <h5 class="card-title text-success">
                            <i class="fas fa-star me-2"></i> Langkah Selanjutnya
                        </h5>
                        <ol class="mb-0">
                            <li class="mb-2">Periksa email untuk surat penerimaan resmi</li>
                            <li class="mb-2">Lakukan pendaftaran ulang (daftar ulang) sesuai jadwal yang diberikan</li>
                            <li class="mb-2">Siapkan dokumen-dokumen yang diperlukan untuk daftar ulang</li>
                            <li class="mb-0">Hadiri acara orientasi siswa baru sesuai jadwal yang ditentukan</li>
                        </ol>
                    </div>
                </div>
            @elseif ($registrant->selection_status === 'failed')
                <div class="card border-0 mb-4 bg-danger bg-opacity-10">
                    <div class="card-body">
                        <h5 class="card-title text-danger">
                            <i class="fas fa-info-circle me-2"></i> Informasi Penting
                        </h5>
                        <p class="mb-2">
                            Kami menghargai minat Anda untuk bergabung dengan sekolah kami. Jika Anda memiliki pertanyaan terkait hasil seleksi,
                            silakan hubungi bagian penerimaan siswa.
                        </p>
                        <p class="mb-0">
                            <strong>Kesempatan Mendaftar Kembali:</strong>
                            Anda dapat mendaftar kembali pada fase pendaftaran berikutnya jika tersedia.
                        </p>
                    </div>
                </div>
            @else
                <div class="card border-0 mb-4 bg-info bg-opacity-10">
                    <div class="card-body">
                        <h5 class="card-title text-info">
                            <i class="fas fa-clock me-2"></i> Status: Sedang Diproses
                        </h5>
                        <p class="mb-0">
                            Kami sedang melakukan review terhadap pendaftaran Anda. Hasil akan diumumkan sesuai jadwal yang telah ditentukan.
                            Periksa email secara berkala untuk update terbaru.
                        </p>
                    </div>
                </div>
            @endif

            <!-- Contact Information -->
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-phone me-2"></i> Hubungi Kami
                    </h5>
                    <p class="mb-1">
                        <strong>Email:</strong> <a href="mailto:admissions@school.edu">admissions@school.edu</a>
                    </p>
                    <p class="mb-1">
                        <strong>Telepon:</strong> (XXX) XXXX-XXXX
                    </p>
                    <p class="mb-0">
                        <strong>Jam Operasional:</strong> Senin-Jumat, 08:00-16:00 WIB
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print Button -->
<div class="fixed-bottom p-3">
    <div class="container">
        <div class="d-flex justify-content-center gap-2">
            <button class="btn btn-secondary" onclick="window.print();">
                <i class="fas fa-print me-1"></i> Cetak
            </button>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-home me-1"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<style>
    .fixed-bottom {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(255, 255, 255, 0.95);
        border-top: 1px solid #dee2e6;
    }

    body {
        padding-bottom: 80px;
    }

    @media print {
        .fixed-bottom {
            display: none;
        }
        body {
            padding-bottom: 0;
        }
    }
</style>
@endsection
