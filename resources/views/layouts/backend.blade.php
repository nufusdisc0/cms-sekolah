<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Backend Admin - {{ $global_settings['school_name']->setting_value ?? config('app.name', 'CMS Sekolahku') }}</title>
    @php $favicon = isset($global_settings['logo']) && !empty($global_settings['logo']->setting_value) ? asset('media_library/images/' . $global_settings['logo']->setting_value) : asset('images/logo.png'); @endphp
    <link rel="icon" type="image/png" href="{{ $favicon }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    @livewireStyles
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* Modern Sidebar */
        .sidebar { background-color: #212529; min-height: 100vh; transition: all 0.3s; box-shadow: inset -1px 0 0 rgba(0, 0, 0, 0.1); }
        .sidebar .brand { padding: 1.25rem 1.5rem; color: #fff; font-size: 1.25rem; font-weight: 700; text-decoration: none; display: flex; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .sidebar .list-group-item { background: transparent; color: #c2c7d0; border: none; padding: 0.8rem 1.5rem; transition: all 0.2s; font-size: 0.95rem; }
        .sidebar .list-group-item:hover, .sidebar .list-group-item.active { background-color: rgba(255,255,255,0.05); color: #fff; border-left: 4px solid #0d6efd; padding-left: calc(1.5rem - 4px); }
        .sidebar .list-group-item i { width: 25px; text-align: center; margin-right: 10px; font-size: 1.1rem;}
        
        /* Accordion in Sidebar */
        .sidebar .accordion-button { background: transparent; color: #c2c7d0; box-shadow: none; font-size: 0.95rem; padding: 0.8rem 1.5rem; }
        .sidebar .accordion-button:not(.collapsed) { color: #fff; background-color: rgba(255,255,255,0.05); }
        .sidebar .accordion-button::after { filter: invert(1) grayscale(100%) brightness(200%); }
        .sidebar .accordion-body { padding: 0; background-color: #1a1d20; }
        .sidebar .accordion-body .list-group-item { padding-left: 3rem; font-size: 0.9rem; padding-top: 0.6rem; padding-bottom: 0.6rem; }
        .sidebar .accordion-item { background: transparent; border: none; border-bottom: 1px solid rgba(255,255,255,0.05); }
        
        /* Sidebar User Panel */
        .sidebar-user { border-top: 1px solid rgba(255,255,255,0.05); padding: 1.5rem; margin-top: 1rem; }
        .sidebar-user .list-group-item { padding: 0.5rem 0; font-size: 0.9rem; }
        .sidebar-user .list-group-item:hover { background-color: transparent; color: #fff; border-left: none; padding-left: 0; }
        
        /* Header Nav */
        .top-nav { background-color: #fff; box-shadow: 0 0.15rem 1.75rem 0 rgba(58,59,69,0.15); z-index: 1040; padding: 0.5rem 1.5rem; }
        .content-area { padding: 1.5rem; }
        
        /* Quick Links */
        .top-nav .nav-icon { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: #858796; transition: 0.2s; }
        .top-nav .nav-icon:hover { background-color: #eaecf4; color: #3a3b45; }
        
        @media (max-width: 767.98px) {
            .sidebar { position: fixed; z-index: 1050; left: -100%; transition: left 0.3s ease; width: 250px; }
            .sidebar.show { left: 0; }
            .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1040; }
            .sidebar.show ~ .sidebar-overlay { display: block; }
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-md-3 col-xl-2 sidebar d-md-block" id="mobileSidebar">
                <a href="{{ route('dashboard') }}" class="brand">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" height="30" class="me-2" onerror="this.style.display='none'">
                    <div>
                        <div class="fs-6 lh-1">{{ $global_settings['school_name']->setting_value ?? config('app.name', 'CMS Sekolah') }}</div>
                        <div class="small fw-normal text-white-50" style="font-size: 0.75rem;">Administrator Panel</div>
                    </div>
                </a>
                
                <div class="accordion accordion-flush mt-3" id="sidebarMenu">
                    <div class="ps-3 pe-3 mb-2 small text-uppercase fw-bold text-white-50">Utama</div>
                    <div class="list-group list-group-flush mb-3">
                        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fa fa-dashboard fa-fw"></i> Beranda
                        </a>
                        <a href="{{ url('/') }}" target="_blank" class="list-group-item list-group-item-action">
                            <i class="fa fa-external-link fa-fw"></i> Lihat Situs
                        </a>
                    </div>

                    @if(Auth::user()->user_type === 'student')
                    <div class="ps-3 pe-3 mb-2 small text-uppercase fw-bold text-white-50">Siswa</div>
                    <div class="list-group list-group-flush mb-3">
                        <a href="{{ route('backend.student_profile.index') }}" class="list-group-item list-group-item-action"><i class="fa fa-user fa-fw"></i> Biodata</a>
                        <a href="{{ route('backend.achievements.index') }}" class="list-group-item list-group-item-action"><i class="fa fa-trophy fa-fw"></i> Prestasi</a>
                        <a href="{{ route('backend.scholarships.index') }}" class="list-group-item list-group-item-action"><i class="fa fa-graduation-cap fa-fw"></i> Beasiswa</a>
                    </div>
                    @endif

                    @if(Auth::user()->user_type === 'employee')
                    <div class="ps-3 pe-3 mb-2 small text-uppercase fw-bold text-white-50">Pegawai</div>
                    <div class="list-group list-group-flush mb-3">
                        <a href="{{ route('backend.employee_profile.index') }}" class="list-group-item list-group-item-action"><i class="fa fa-user fa-fw"></i> Biodata</a>
                    </div>
                    @endif

                    @if(in_array(Auth::user()->user_type, ['super_user', 'administrator']))
                    <div class="ps-3 pe-3 mb-2 small text-uppercase fw-bold text-white-50">Manajemen Konten</div>
                    <!-- BLOG -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menuBlog">
                                <i class="fa fa-pencil-square-o fa-fw me-2"></i> Publikasi
                            </button>
                        </h2>
                        <div id="menuBlog" class="accordion-collapse collapse" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body">
                                <div class="list-group list-group-flush">
                                    <a href="{{ route('backend.posts.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Semua Tulisan</a>
                                    <a href="{{ route('backend.post_categories.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Kategori Tulisan</a>
                                    <a href="{{ route('backend.tags.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Tags</a>
                                    <a href="{{ route('backend.pages.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Halaman</a>
                                    <a href="{{ route('backend.image_sliders.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Gambar Slide</a>
                                    <a href="{{ route('backend.quotes.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Kutipan</a>
                                    <a href="{{ route('backend.opening_speech') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Sambutan</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MEDIA -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menuMedia">
                                <i class="fa fa-picture-o fa-fw me-2"></i> Galeri & Media
                            </button>
                        </h2>
                        <div id="menuMedia" class="accordion-collapse collapse" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body">
                                <div class="list-group list-group-flush">
                                    <a href="{{ Route::has('backend.albums.index') ? route('backend.albums.index') : '#' }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Album Foto</a>
                                    <a href="{{ Route::has('backend.videos.index') ? route('backend.videos.index') : '#' }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Video</a>
                                    <a href="{{ Route::has('backend.files.index') ? route('backend.files.index') : '#' }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> File Unduhan</a>
                                    <a href="{{ Route::has('backend.file_categories.index') ? route('backend.file_categories.index') : '#' }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Kategori File</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- INTERAKSI -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menuInteraksi">
                                <i class="fa fa-comments-o fa-fw me-2"></i> Interaksi
                            </button>
                        </h2>
                        <div id="menuInteraksi" class="accordion-collapse collapse" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body">
                                <div class="list-group list-group-flush">
                                    <a href="{{ route('backend.messages') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Pesan Masuk</a>
                                    <a href="{{ route('backend.post_comments_live') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Komentar</a>
                                    <a href="{{ route('backend.subscribers.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Subscribers</a>
                                    <a href="{{ route('backend.links.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Tautan Keluar</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ps-3 pe-3 mt-4 mb-2 small text-uppercase fw-bold text-white-50">Data Sekolah</div>
                    
                    <!-- DATA INDUK -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menuDataInduk">
                                <i class="fa fa-database fa-fw me-2"></i> Data Referensi
                            </button>
                        </h2>
                        <div id="menuDataInduk" class="accordion-collapse collapse" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body">
                                <div class="list-group list-group-flush">
                                    <a href="{{ route('backend.special_needs.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Kebutuhan Khusus</a>
                                    <a href="{{ route('backend.educations.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Pendidikan</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- AKADEMIK -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menuAkademik">
                                <i class="fa fa-graduation-cap fa-fw me-2"></i> Akademik & Siswa
                            </button>
                        </h2>
                        <div id="menuAkademik" class="accordion-collapse collapse" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body">
                                <div class="list-group list-group-flush">
                                    <a href="{{ route('backend.academic_students.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Data Siswa</a>
                                    <a href="{{ route('backend.class_groups.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Rombongan Belajar</a>
                                    <a href="{{ route('backend.majors.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Kompetensi/Jurusan</a>
                                    <a href="{{ route('backend.academic_years.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Tahun Pelajaran</a>
                                    <a href="{{ route('backend.alumni.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Direktori Alumni</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KARYAWAN -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menuKaryawan">
                                <i class="fa fa-address-card-o fa-fw me-2"></i> GTK / Pegawai
                            </button>
                        </h2>
                        <div id="menuKaryawan" class="accordion-collapse collapse" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body">
                                <div class="list-group list-group-flush">
                                    <a href="{{ route('backend.academic_employees.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Data Pegawai</a>
                                    <a href="{{ route('backend.employment_types.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Jenis Pegawai</a>
                                    <a href="{{ route('backend.employments') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Jabatan / Tugas</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PPDB -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menuPPDB">
                                <i class="fa fa-users fa-fw me-2"></i> PPDB
                            </button>
                        </h2>
                        <div id="menuPPDB" class="accordion-collapse collapse" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body">
                                <div class="list-group list-group-flush">
                                    <a href="{{ route('backend.registrants.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Calon Siswa</a>
                                    <a href="{{ route('backend.admission_phases.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Gelombang</a>
                                    <a href="{{ route('backend.admission_quotas.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Kuota</a>
                                    <a href="{{ route('backend.registrants_approved') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Lulus Seleksi</a>
                                    <a href="{{ route('backend.registrants_unapproved') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Tidak Lulus</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ps-3 pe-3 mt-4 mb-2 small text-uppercase fw-bold text-white-50">Sistem</div>

                    <!-- PENGGUNA -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menuPengguna">
                                <i class="fa fa-user-circle-o fa-fw me-2"></i> Pengguna Sistem
                            </button>
                        </h2>
                        <div id="menuPengguna" class="accordion-collapse collapse" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body">
                                <div class="list-group list-group-flush">
                                    <a href="{{ route('backend.users.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Administrator</a>
                                    <a href="{{ route('backend.user_students.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Akun Siswa</a>
                                    <a href="{{ route('backend.user_employees.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Akun Pegawai</a>
                                    <a href="{{ route('backend.user_groups.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Grup & Akses</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAMPILAN -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menuTampilan">
                                <i class="fa fa-desktop fa-fw me-2"></i> Tampilan Web
                            </button>
                        </h2>
                        <div id="menuTampilan" class="accordion-collapse collapse" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body">
                                <div class="list-group list-group-flush">
                                    <a href="{{ route('backend.themes') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Tema</a>
                                                                        <a href="{{ route('backend.banners.index') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Banner & Iklan</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PENGATURAN -->
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menuPengaturan">
                                <i class="fa fa-cogs fa-fw me-2"></i> Pengaturan
                            </button>
                        </h2>
                        <div id="menuPengaturan" class="accordion-collapse collapse" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body">
                                    <a href="{{ route('settings.index', 'school_profile') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Profil Sekolah</a>
                                    <a href="{{ route('settings.index', 'mail_server') }}" class="list-group-item"><i class="fa fa-angle-right fa-fw"></i> Email Server</a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="sidebar-overlay d-md-none" onclick="document.getElementById('mobileSidebar').classList.remove('show')"></div>

            <!-- Main Content Area -->
            <div class="col-md-9 col-xl-10 d-flex flex-column min-vh-100 bg-light">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand top-nav px-3 py-3 align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-link text-dark d-md-none me-3" type="button" onclick="document.getElementById('mobileSidebar').classList.add('show')">
                            <i class="fa fa-bars fs-5"></i>
                        </button>
                        
                        <!-- Search input -->
                        <div class="d-none d-sm-block position-relative">
                            <input type="text" class="form-control bg-light border-0 small rounded-pill ps-4 pe-5" placeholder="Cari data..." style="width: 250px;">
                            <i class="fa fa-search text-muted position-absolute" style="top: 50%; transform: translateY(-50%); right: 15px;"></i>
                        </div>
                    </div>

                    <ul class="navbar-nav align-items-center">
                        <!-- Notifications -->
                        @php
                            $pendingMessages = \App\Models\Comment::where('comment_type', 'message')->where('comment_status', 'unapproved')->count();
                            $pendingComments = \App\Models\Comment::where('comment_type', 'post')->where('comment_status', 'unapproved')->count();
                            $totalNotifications = $pendingMessages + $pendingComments;
                        @endphp
                        <li class="nav-item me-3 dropdown">
                            <a class="nav-link nav-icon position-relative dropdown-toggle" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #5a5c69;">
                                <i class="fa fa-bell"></i>
                                @if($totalNotifications > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                    {{ $totalNotifications }}
                                </span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3" aria-labelledby="alertsDropdown" style="width: 250px;">
                                <li><h6 class="dropdown-header text-uppercase text-muted fw-bold pb-2 border-bottom">Notifikasi Interaksi</h6></li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center py-3 border-bottom" href="{{ route('backend.messages') }}">
                                        <div class="me-3">
                                            <div class="bg-primary text-white d-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width: 36px; height: 36px;">
                                                <i class="fa fa-envelope"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block">{{ $pendingMessages }} Pesan Baru</span>
                                            <small class="text-muted">Cek Kotak Masuk</small>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center py-3" href="{{ route('backend.post_comments_live') }}">
                                        <div class="me-3">
                                            <div class="bg-success text-white d-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width: 36px; height: 36px;">
                                                <i class="fa fa-comments"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block">{{ $pendingComments }} Komentar Baru</span>
                                            <small class="text-muted">Moderasi Komentar</small>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <!-- User Dropdown -->
                        <li class="nav-item dropdown px-2 border-start">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #5a5c69;">
                                <div class="d-none d-lg-block text-end me-3">
                                    <div class="small fw-bold">{{ Auth::user()->user_full_name }}</div>
                                    <div class="small text-muted" style="font-size: 0.75rem;">{{ Auth::user()->user_type == 'super_user' ? 'Super Administrator' : 'Administrator' }}</div>
                                </div>
                                <img class="rounded-circle shadow-sm object-fit-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->user_full_name) }}&background=0d6efd&color=fff&size=40" height="40" width="40">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item py-2 fw-medium text-secondary" href="{{ route('profile.index') }}"><i class="fa fa-user fa-sm fa-fw me-3 text-gray-400"></i> Profil Saya</a></li>
                                <li><a class="dropdown-item py-2 fw-medium text-secondary" href="{{ route('profile.password') }}"><i class="fa fa-cogs fa-sm fa-fw me-3 text-gray-400"></i> Ubah Password</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 fw-medium text-danger"><i class="fa fa-sign-out fa-sm fa-fw me-3"></i> Keluar</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>

                <!-- Page Content -->
                <main class="content-area flex-grow-1">
                    @hasSection('content')
                        @yield('content')
                    @else
                        {{ $slot ?? '' }}
                    @endif
                </main>

                <!-- Footer -->
                <footer class="bg-white py-4 mt-auto border-top">
                    <div class="container-fluid text-center text-muted small fw-medium">
                        <span>Hak Cipta &copy; {{ $global_settings['school_name']->setting_value ?? config('app.name', 'CMS Sekolah') }} {{ date('Y') }}. Dikembangkan dengan <i class="fa fa-heart text-danger"></i>.</span>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-close sidebar on mobile when clicking a link
        document.querySelectorAll('#mobileSidebar .list-group-item').forEach(link => {
            link.addEventListener('click', () => {
                if(window.innerWidth < 768) {
                    document.getElementById('mobileSidebar').classList.remove('show');
                }
            })
        });
    </script>
    @livewireScripts
</body>
</html>
