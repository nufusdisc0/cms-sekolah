<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Backend Admin - {{ config('app.name', 'CMS Sekolahku') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @livewireStyles
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">{{ config('app.name', 'CMS Sekolahku') }} Admin</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link">Welcome, {{ Auth::user()->user_full_name }}</span>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 bg-white min-vh-100 border-end py-3">
                <div class="accordion accordion-flush" id="sidebarMenu">
                    <div class="list-group list-group-flush border-bottom">
                        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action border-0 py-2"><i class="fa fa-dashboard fa-fw me-2"></i> BERANDA</a>
                        <a href="{{ url('/') }}" target="_blank" class="list-group-item list-group-item-action border-0 py-2"><i class="fa fa-rocket fa-fw me-2"></i> LIHAT SITUS</a>
                    </div>

                    @if(Auth::user()->user_type === 'student')
                    <div class="list-group list-group-flush border-bottom">
                        <a href="{{ route('backend.student_profile.index') }}" class="list-group-item list-group-item-action border-0 py-2"><i class="fa fa-user fa-fw me-2"></i> Biodata</a>
                        <a href="{{ route('backend.achievements.index') }}" class="list-group-item list-group-item-action border-0 py-2"><i class="fa fa-trophy fa-fw me-2"></i> Prestasi</a>
                        <a href="{{ route('backend.scholarships.index') }}" class="list-group-item list-group-item-action border-0 py-2"><i class="fa fa-graduation-cap fa-fw me-2"></i> Beasiswa</a>
                    </div>
                    @endif

                    @if(Auth::user()->user_type === 'employee')
                    <div class="list-group list-group-flush border-bottom">
                        <a href="{{ route('backend.employee_profile.index') }}" class="list-group-item list-group-item-action border-0 py-2"><i class="fa fa-user fa-fw me-2"></i> Biodata</a>
                    </div>
                    @endif

                    @if(in_array(Auth::user()->user_type, ['super_user', 'administrator']))
                    <!-- BLOG -->
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed py-2 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#menuBlog">
                                <i class="fa fa-edit fa-fw me-2"></i> BLOG
                            </button>
                        </h2>
                        <div id="menuBlog" class="accordion-collapse collapse" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <div class="list-group list-group-flush bg-light">
                                    <a href="{{ route('backend.image_sliders.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Gambar Slide</a>
                                    <a href="{{ route('backend.links.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Tautan</a>
                                    <a href="{{ route('backend.pages.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Halaman</a>
                                    <a href="{{ route('backend.posts.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Semua Tulisan</a>
                                    <a href="{{ route('backend.post_categories.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Kategori Tulisan</a>
                                    <a href="{{ route('backend.tags.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Tags</a>
                                    <a href="{{ route('backend.quotes.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Kutipan</a>
                                    <a href="{{ route('backend.subscribers.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Subscriber</a>
                                    <a href="{{ route('backend.messages') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Pesan Masuk</a>
                                    <a href="{{ route('backend.post_comments_live') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Komentar</a>
                                    <a href="{{ route('backend.opening_speech') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Sambutan</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DATA INDUK -->
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed py-2 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#menuDataInduk">
                                <i class="fa fa-list fa-fw me-2"></i> DATA INDUK
                            </button>
                        </h2>
                        <div id="menuDataInduk" class="accordion-collapse collapse" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <div class="list-group list-group-flush bg-light">
                                    <a href="{{ route('backend.special_needs.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Kebutuhan Khusus</a>
                                    <a href="{{ route('backend.educations.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Pendidikan</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- AKADEMIK -->
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed py-2 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#menuAkademik">
                                <i class="fa fa-address-book-o fa-fw me-2"></i> AKADEMIK
                            </button>
                        </h2>
                        <div id="menuAkademik" class="accordion-collapse collapse" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <div class="list-group list-group-flush bg-light">
                                    <a href="{{ route('backend.alumni.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Alumni</a>
                                    <a href="{{ route('backend.majors.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Jurusan</a>
                                    <a href="{{ route('backend.class_groups.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Kelas</a>
                                    <a href="{{ route('backend.academic_students.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Siswa / Peserta Didik</a>
                                    <a href="{{ route('backend.academic_years.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Tahun Pelajaran</a>
                                    <a href="{{ route('backend.transportations') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Moda Transportasi</a>
                                    <a href="{{ route('backend.monthly_incomes') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Penghasilan Bulanan</a>
                                    <a href="{{ route('backend.residences') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Tempat Tinggal</a>
                                    <a href="{{ route('backend.student_status') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Status Siswa</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KARYAWAN -->
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed py-2 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#menuKaryawan">
                                <i class="fa fa-address-book-o fa-fw me-2"></i> KARYAWAN
                            </button>
                        </h2>
                        <div id="menuKaryawan" class="accordion-collapse collapse" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <div class="list-group list-group-flush bg-light">
                                    <a href="{{ route('backend.academic_employees.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Semua Karyawan</a>
                                    <a href="{{ route('backend.employment_types.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Jenis Karyawan</a>
                                    <a href="{{ route('backend.laboratory_skills.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Keahlian Laboratorium</a>
                                    <a href="{{ route('backend.institution_lifters.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Lembaga Pengangkat</a>
                                    <a href="{{ route('backend.ranks.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Pangkat/Golongan</a>
                                    <a href="{{ route('backend.employment_statuses.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Status Kepegawaian</a>
                                    <a href="{{ route('backend.salary_sources.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Sumber Gaji</a>
                                    <a href="{{ route('backend.employments') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Pekerjaan</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PPDB -->
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed py-2 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#menuPPDB">
                                <i class="fa fa-address-book-o fa-fw me-2"></i> PPDB
                            </button>
                        </h2>
                        <div id="menuPPDB" class="accordion-collapse collapse" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <div class="list-group list-group-flush bg-light">
                                    <a href="{{ route('backend.registrants.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Calon Peserta Didik Baru</a>
                                    <a href="{{ route('backend.admission_phases.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Gelombang Pendaftaran</a>
                                    <a href="{{ route('backend.admission_quotas.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Kuota Penerimaan</a>
                                    <a href="{{ route('backend.admission_types') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Jalur Pendaftaran</a>
                                    <a href="{{ route('backend.registrants_approved') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Diterima</a>
                                    <a href="{{ route('backend.registrants_unapproved') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Tidak Diterima</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PLUGINS -->
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed py-2 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#menuPlugins">
                                <i class="fa fa-plug fa-fw me-2"></i> PLUGINS
                            </button>
                        </h2>
                        <div id="menuPlugins" class="accordion-collapse collapse" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <div class="list-group list-group-flush bg-light">
                                    <a href="{{ Route::has('backend.banners.index') ? route('backend.banners.index') : '#' }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Iklan</a>
                                    <a href="{{ Route::has('backend.questions.index') ? route('backend.questions.index') : '#' }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Pertanyaan Jajak Pendapat</a>
                                    <a href="{{ Route::has('backend.answers.index') ? route('backend.answers.index') : '#' }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Jawaban Jajak Pendapat</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MEDIA -->
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed py-2 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#menuMedia">
                                <i class="fa fa-upload fa-fw me-2"></i> MEDIA
                            </button>
                        </h2>
                        <div id="menuMedia" class="accordion-collapse collapse" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <div class="list-group list-group-flush bg-light">
                                    <a href="{{ Route::has('backend.files.index') ? route('backend.files.index') : '#' }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> File</a>
                                    <a href="{{ Route::has('backend.file_categories.index') ? route('backend.file_categories.index') : '#' }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Kategori File</a>
                                    <a href="{{ Route::has('backend.albums.index') ? route('backend.albums.index') : '#' }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Album Foto</a>
                                    <a href="{{ Route::has('backend.videos.index') ? route('backend.videos.index') : '#' }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Video</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PENGGUNA -->
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed py-2 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#menuPengguna">
                                <i class="fa fa-users fa-fw me-2"></i> PENGGUNA
                            </button>
                        </h2>
                        <div id="menuPengguna" class="accordion-collapse collapse" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <div class="list-group list-group-flush bg-light">
                                    <a href="{{ route('backend.users.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Administrator</a>
                                    <a href="{{ route('backend.user_students.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Siswa / Peserta Didik</a>
                                    <a href="{{ route('backend.user_employees.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Karyawan</a>
                                    <a href="{{ route('backend.user_groups.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Grup Pengguna</a>
                                    <a href="{{ route('backend.user_privileges.index') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Hak Akses</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAMPILAN -->
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed py-2 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#menuTampilan">
                                <i class="fa fa-paint-brush fa-fw me-2"></i> TAMPILAN
                            </button>
                        </h2>
                        <div id="menuTampilan" class="accordion-collapse collapse" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <div class="list-group list-group-flush bg-light">
                                    <a href="{{ route('backend.menus') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Menu</a>
                                    <a href="{{ route('backend.themes') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Tema</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PENGATURAN -->
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed py-2 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#menuPengaturan">
                                <i class="fa fa-wrench fa-fw me-2"></i> PENGATURAN
                            </button>
                        </h2>
                        <div id="menuPengaturan" class="accordion-collapse collapse" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <div class="list-group list-group-flush bg-light">
                                    <a href="{{ route('settings.index', 'mail_server') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Email Server</a>
                                    <a href="{{ route('settings.index', 'social_account') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Jejaring Sosial</a>
                                    <a href="{{ route('settings.index', 'school_profile') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Profil Sekolah</a>
                                    <a href="{{ route('settings.index', 'general') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Umum</a>
                                    <a href="{{ route('backend.settings_discussion') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Diskusi</a>
                                    <a href="{{ route('backend.settings_media') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Media</a>
                                    <a href="{{ route('backend.settings_writing') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Menulis</a>
                                    <a href="{{ route('backend.settings_reading') }}" class="list-group-item list-group-item-action bg-light border-0 py-2 ps-5"><i class="fa fa-angle-right me-2"></i> Membaca</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="list-group list-group-flush mt-3">
                        <a href="{{ route('profile.index') }}" class="list-group-item list-group-item-action border-0 py-2"><i class="fa fa-user fa-fw me-2"></i> UBAH PROFIL</a>
                        <a href="{{ route('profile.password') }}" class="list-group-item list-group-item-action border-0 py-2"><i class="fa fa-key fa-fw me-2"></i> UBAH KATA SANDI</a>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="list-group-item list-group-item-action border-0 py-2 w-100 text-start"><i class="fa fa-power-off fa-fw me-2"></i> KELUAR</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-10 py-4">
                @hasSection('content')
                    @yield('content')
                @else
                    {{ $slot ?? '' }}
                @endif
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
</body>
</html>
