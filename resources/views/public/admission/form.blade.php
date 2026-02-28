@extends('layouts.public')

@section('title', 'Formulir Pendaftaran Siswa Baru')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="card mb-4 border-0 bg-primary text-white">
                <div class="card-body text-center py-4">
                    <h1 class="h2 mb-2">Pendaftaran Siswa Baru</h1>
                    <p class="mb-0">Isi formulir di bawah untuk mendaftar sebagai calon siswa</p>
                </div>
            </div>

            <!-- Alerts -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading">Terjadi Kesalahan</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Download Blank Form -->
            <div class="alert alert-info" role="alert">
                <i class="fas fa-info-circle"></i>
                <strong>Tips:</strong> Anda dapat
                <a href="{{ route('admission.blank-form') }}" class="alert-link">mengunduh dan mencetak formulir kosong</a>
                untuk membantu Anda mengisi data dengan lengkap.
            </div>

            <!-- Start of Form Wizard -->
            <div id="admission-form-container">
                @include('public.admission.steps.step1')
            </div>

            <!-- Admission Phases Info -->
            <div class="card mt-4 border-0 bg-light">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-calendar-alt"></i> Jadwal Pendaftaran
                    </h5>
                    <div id="phases-info" class="text-muted">
                        Memuat jadwal pendaftaran...
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card mt-4 border-0 bg-light">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-phone"></i> Butuh Bantuan?
                    </h5>
                    <p class="mb-1">
                        <strong>Telepon:</strong> <span id="school-phone">-</span>
                    </p>
                    <p class="mb-1">
                        <strong>Email:</strong> <a id="school-email" href="mailto:#">-</a>
                    </p>
                    <p class="mb-0">
                        <strong>Jam Operasional:</strong> Senin - Jumat, 08:00 - 16:00 WIB
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Load admission phases on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadAdmissionPhases();
        loadSchoolInfo();
    });

    function loadAdmissionPhases() {
        fetch('/api/admission-phases')
            .then(response => response.json())
            .then(data => {
                let html = '';
                if (data.length === 0) {
                    html = '<p class="text-danger">Tidak ada fase pendaftaran yang aktif saat ini.</p>';
                } else {
                    data.forEach(phase => {
                        const startDate = new Date(phase.start_date).toLocaleDateString('id-ID');
                        const endDate = new Date(phase.end_date).toLocaleDateString('id-ID');
                        html += `
                            <div class="mb-3 pb-3 border-bottom">
                                <h6 class="mb-1">${phase.name}</h6>
                                <p class="mb-1 small">
                                    <i class="fas fa-calendar"></i> ${startDate} - ${endDate}
                                </p>
                                ${phase.description ? `<p class="mb-0 small text-muted">${phase.description}</p>` : ''}
                            </div>
                        `;
                    });
                }
                document.getElementById('phases-info').innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('phases-info').innerHTML =
                    '<p class="text-danger">Gagal memuat jadwal pendaftaran.</p>';
            });
    }

    function loadSchoolInfo() {
        // Load from localStorage or default values
        const schoolPhone = localStorage.getItem('school_phone') || '(XXX) XXXX-XXXX';
        const schoolEmail = localStorage.getItem('school_email') || 'info@sekolah.edu';

        document.getElementById('school-phone').textContent = schoolPhone;
        document.getElementById('school-email').href = 'mailto:' + schoolEmail;
        document.getElementById('school-email').textContent = schoolEmail;
    }
</script>
@endpush
@endsection
