@extends('layouts.admin')

@section('title', 'Impor Data Siswa')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-upload text-primary me-2"></i>Impor Data Siswa
            </h1>
            <p class="text-muted small">Impor daftar siswa dari file CSV</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('backend.import.history') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-history me-1"></i> Riwayat Impor
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi Kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Unggah File Data Siswa</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('backend.import.students.preview') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- File Upload -->
                        <div class="mb-3">
                            <label for="file" class="form-label">
                                <strong>File CSV</strong>
                                <span class="badge bg-info ms-2">Wajib</span>
                            </label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror"
                                   id="file" name="file" accept=".csv,.xls,.xlsx" required>
                            <small class="text-muted d-block mt-2">
                                Format: CSV atau Excel | Ukuran maksimal: 10MB
                            </small>
                            @error('file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Batch Size -->
                        <div class="mb-3">
                            <label for="batch_size" class="form-label">
                                <strong>Ukuran Batch (baris per pemrosesan)</strong>
                            </label>
                            <input type="number" class="form-control @error('batch_size') is-invalid @enderror"
                                   id="batch_size" name="batch_size" value="50" min="10" max="1000" required>
                            <small class="text-muted d-block mt-2">
                                Gunakan angka lebih besar untuk file besar. Default: 50
                            </small>
                            @error('batch_size')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check me-2"></i> Pratinjau Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Format Information -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Format File CSV</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">File CSV harus memiliki kolom-kolom berikut:</p>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Kolom</th>
                                    <th>Contoh</th>
                                    <th>Opsional?</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>full_name</strong></td>
                                    <td>John Doe</td>
                                    <td>Wajib</td>
                                </tr>
                                <tr>
                                    <td><strong>nisn</strong></td>
                                    <td>123456789</td>
                                    <td>Opsional</td>
                                </tr>
                                <tr>
                                    <td><strong>nik</strong></td>
                                    <td>12345678901</td>
                                    <td>Opsional</td>
                                </tr>
                                <tr>
                                    <td><strong>gender</strong></td>
                                    <td>M atau F</td>
                                    <td>Wajib</td>
                                </tr>
                                <tr>
                                    <td><strong>birth_place</strong></td>
                                    <td>Jakarta</td>
                                    <td>Opsional</td>
                                </tr>
                                <tr>
                                    <td><strong>birth_date</strong></td>
                                    <td>2008-01-15</td>
                                    <td>Opsional</td>
                                </tr>
                                <tr>
                                    <td><strong>email</strong></td>
                                    <td>john@example.com</td>
                                    <td>Opsional</td>
                                </tr>
                                <tr>
                                    <td><strong>phone</strong></td>
                                    <td>081234567890</td>
                                    <td>Opsional</td>
                                </tr>
                                <tr>
                                    <td><strong>street_address</strong></td>
                                    <td>Jl. Merdeka No. 1</td>
                                    <td>Opsional</td>
                                </tr>
                                <tr>
                                    <td><strong>postal_code</strong></td>
                                    <td>12345</td>
                                    <td>Opsional</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-info mt-3 mb-0">
                        <strong>Catatan:</strong> Kolom dapat diurutkan dengan bebas. Sistem akan mendeteksi header secara otomatis.
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Download Template -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Template</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Unduh template CSV untuk memastikan format yang benar:</p>
                    <a href="{{ route('backend.import.students.template') }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-download me-2"></i> Unduh Template
                    </a>
                </div>
            </div>

            <!-- Tips & Tricks -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="small ps-3 mb-0">
                        <li class="mb-2">Gunakan template yang disediakan untuk menghindari kesalahan format</li>
                        <li class="mb-2">Pastikan email unik untuk setiap siswa</li>
                        <li class="mb-2">Format tanggal: YYYY-MM-DD (contoh: 2008-01-15)</li>
                        <li class="mb-2">Gunakan "M" untuk laki-laki, "F" untuk perempuan</li>
                        <li class="mb-2">Periksa data sebelum konfirmasi impor</li>
                        <li>Anda dapat memperbaiki kesalahan setelah pratinjau</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
