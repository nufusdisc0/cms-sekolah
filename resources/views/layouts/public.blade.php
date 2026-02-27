<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', config('app.name', 'CMS Sekolahku'))</title>
    <meta name="description" content="@yield('meta_description', 'Website Resmi Sekolah')">
    <meta name="keywords" content="pendidikan, sekolah, education">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        /* ===== BLUE SKY THEME ===== */
        body { font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; background-color: #eaf3ff; }
        body a { color: #202020; }
        body a:hover { color: gray; text-decoration: none; }

        /* TOP BAR */
        .top-bar { background-color: #111b51; color: #fff; font-size: 14px; padding: 16px 0 0; }
        .top-bar a { color: #fff; }
        .top-bar a:hover { color: #ff5a2c; }
        .top-bar i { margin-right: 5px; }
        .top-header { display: flex; align-items: center; }

        /* MENU BAR */
        .menu-bar { text-transform: uppercase; font-weight: bold; background-color: #ff5a2c; border-bottom: 1px solid #111b51; }
        .menu-bar .navbar { padding: 0; }
        .menu-bar .nav-link { color: #fff !important; padding: 12px 16px; font-size: 14px; transition: background 0.2s; }
        .menu-bar .nav-link:hover, .menu-bar .nav-link.active { background-color: #111b51; }
        .menu-bar .navbar-toggler { border-color: rgba(255,255,255,0.5); }
        .menu-bar .navbar-toggler-icon { filter: invert(1); }

        /* BRAND */
        .brand { font-family: Arial, sans-serif, "Helvetica Neue", Helvetica; font-weight: bold; text-transform: uppercase; color: #fff; }

        /* CAROUSEL */
        .carousel-caption { border-top: 1px solid #fff; color: #fff; background: #111b51; opacity: 0.7; right: 0; left: 0; bottom: 0; text-align: left; padding: 30px; }
        .carousel-indicators .active { background-color: #ff5a2c; }

        /* PAGE TITLE */
        .page-title { font-weight: bold; border-bottom: 2px solid #ff5a2c; margin-bottom: 15px; padding-bottom: 10px; position: relative; }
        .page-title:after { border-bottom: 2px solid #111b51; width: 30%; display: block; position: absolute; content: ''; padding-bottom: 10px; }

        /* QUOTE */
        .quote-bar { overflow: hidden; background-color: #111b51; }
        .quote-title { font-size: 14px; font-weight: bold; text-transform: uppercase; display: inline-block; padding: 15px; color: #fff; float: left; background-color: #ff5a2c; height: 50px; }
        .quote-text { color: #fff; padding: 17px 15px; font-size: 14px; white-space: nowrap; }
        .quote-text span { color: #ff5a2c; font-weight: bold; }

        /* CONTENT */
        h5.card-title { font-size: 16px; font-weight: bold; }
        .card { border-radius: 0 !important; }

        /* ACTION BUTTON */
        .action-button { background-color: #ff5a2c; color: #fff; border: none; }
        .action-button:hover { background-color: #111b51; color: #fff; }

        /* SIDEBAR */
        .sidebar .list-group-item { border: 1px solid #6c757d; border-radius: 0; }

        /* FOOTER */
        footer { color: #fff; font-size: 14px; }
        footer .primary-footer { background-color: #111b51; padding: 30px 0; }
        footer .secondary-footer { border-top: 1px solid #fff; background-color: #ff5a2c; padding: 10px 0; }
        footer .copyright { color: #fff; }
        footer a { color: #fff; }
        footer a:hover { color: yellow; }
        footer .page-title { color: #fff; }
        footer .page-title:after { border-bottom: 2px solid #fff; }

        /* SOCIAL ICONS */
        .social-icon { width: 40px; height: 40px; font-size: 15px; color: #fff; text-align: center; margin-right: 10px; padding-top: 12px; border-radius: 50%; display: inline-block; }
        .facebook { background-color: #3b5998; }
        .twitter { background-color: #1da1f2; }
        .instagram { background-color: #fbbc05; }
        .youtube { background-color: #ef4e17; }

        /* TAGS */
        .tag a { border: 1px solid #fff; padding: 12px 10px 8px; color: #fff; display: inline-block; font-size: 12px; text-transform: uppercase; line-height: 11px; margin-bottom: 5px; margin-right: 2px; text-decoration: none; }
        .tag a:hover { border: 1px solid #ff5a2c; color: #fff; }

        /* FULLSCREEN SEARCH */
        #search_form { z-index: 999999; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.9); transition: all 0.5s ease-in-out; transform: translate(0px, -100%) scale(0,0); opacity: 0; }
        #search_form.open { transform: translate(0px, 0px) scale(1,1); opacity: 1; }
        #search_form input[type="text"] { position: absolute; top: 50%; width: 100%; color: #fff; background: rgba(0,0,0,0); font-size: 60px; font-weight: 300; text-align: center; border: 0; margin: 0 auto; margin-top: -51px; padding: 0 30px; outline: none; }
        #search_form .btn { position: absolute; top: 50%; left: 50%; margin-top: 61px; margin-left: -45px; }

        /* BACK TO TOP */
        #return-to-top { opacity: 0.7; z-index: 10000; position: fixed; bottom: 15px; right: 15px; background: #fbbc05; width: 30px; height: 30px; display: none; text-decoration: none; border-radius: 5px; transition: all 0.3s ease; }
        #return-to-top i { color: #fff; margin: 0; position: relative; left: 9px; top: 5px; font-size: 19px; }
        #return-to-top:hover { background: #000; }

        /* RESPONSIVE */
        @media (max-width: 767.98px) {
            .top-left { text-align: center !important; }
            .top-right { display: none; }
        }
    </style>
    @stack('styles')
    @livewireStyles
</head>
<body>
    <header>
        {{-- TOP BAR --}}
        <div class="container-fluid top-bar">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-5 col-sm-12">
                        <div class="top-header">
                            @php $logo = isset($global_settings['logo']) ? asset('media_library/images/' . $global_settings['logo']->setting_value) : asset('images/logo.png'); @endphp
                            <img src="{{ $logo }}" style="max-height: 70px; width: auto; max-width: 250px; object-fit: contain;" class="mt-2 me-4 mb-3" onerror="this.style.display='none'" alt="Logo Sekolah">
                            <ul class="list-unstyled top-left mb-0">
                                <li><h5 class="brand mb-0">{{ strtoupper($global_settings['school_name']->setting_value ?? config('app.name', 'SEKOLAH')) }}</h5></li>
                                <li><small>Website Resmi Sekolah</small></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-7 col-sm-12">
                        <ul class="list-inline float-end top-right mb-0">
                            <li class="list-inline-item ps-3"><i class="fa fa-envelope"></i> {{ $global_settings['email']->setting_value ?? 'info@sekolah.sch.id' }}</li>
                            <li class="list-inline-item ps-3"><i class="fa fa-phone"></i> {{ $global_settings['phone']->setting_value ?? '(021) 123-4567' }}</li>
                            <li class="list-inline-item ps-3"><a href="#search_form"><i class="fa fa-search"></i> Pencarian</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- NAVIGATION MENU --}}
        <div class="container-fluid menu-bar mb-3">
            <div class="container">
                <nav class="navbar navbar-expand-lg p-0">
                    <a class="navbar-brand" href="#"></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="fa fa-align-justify text-white"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link" href="{{ url('/') }}"><i class="fa fa-home"></i></a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/page/profil') }}">PROFIL</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/page/visi-misi') }}">VISI & MISI</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/contact') }}">KONTAK</a></li>
                            @auth
                                <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">DASHBOARD</a></li>
                            @else
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">LOGIN</a></li>
                            @endauth
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        @yield('header_content')
    </header>

    <section class="content">
        <div class="container">
            <div class="row">
                @yield('content')
            </div>
        </div>
    </section>

    <footer>
        <div class="container-fluid primary-footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-12 text-md-start mb-2 mt-2">
                        <h6 class="page-title">Hubungi Kami</h6>
                        <p>{{ $global_settings['school_name']->setting_value ?? config('app.name', 'Sekolah') }}</p>
                        <dl class="row">
                            <dt class="col-lg-4 col-md-4 col-sm-12"><span class="fa fa-map-marker"></span> Alamat</dt>
                            <dd class="col-lg-8 col-md-8 col-sm-12">{{ $global_settings['street_address']->setting_value ?? 'Jl. Pendidikan No. 1' }}</dd>
                            <dt class="col-lg-4 col-md-4 col-sm-12"><span class="fa fa-phone"></span> Telepon</dt>
                            <dd class="col-lg-8 col-md-8 col-sm-12">{{ $global_settings['phone']->setting_value ?? '(021) 123-4567' }}</dd>
                            <dt class="col-lg-4 col-md-4 col-sm-12"><span class="fa fa-envelope"></span> Email</dt>
                            <dd class="col-lg-8 col-md-8 col-sm-12">{{ $global_settings['email']->setting_value ?? 'info@sekolah.sch.id' }}</dd>
                        </dl>
                    </div>
                    <div class="col-md-4 col-12 text-md-start mb-2 mt-2">
                        <h6 class="page-title">Tags</h6>
                        <div class="tag-content-block tag">
                            @php $tags = \App\Models\Tag::take(10)->get(); @endphp
                            @foreach($tags as $tag)
                                <a href="{{ url('/tag/' . $tag->slug) }}">{{ $tag->tag }}</a>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-4 col-12 text-md-start mb-2 mt-2">
                        <h6 class="page-title">Ikuti Kami</h6>
                        <ul class="list-unstyled">
                            @if(!empty($global_settings['facebook']->setting_value))
                            <li class="float-start"><a href="{{ $global_settings['facebook']->setting_value }}" title="Facebook"><i class="fa fa-facebook social-icon facebook"></i></a></li>
                            @endif
                            @if(!empty($global_settings['twitter']->setting_value))
                            <li class="float-start"><a href="{{ $global_settings['twitter']->setting_value }}" title="Twitter"><i class="fa fa-twitter social-icon twitter"></i></a></li>
                            @endif
                            @if(!empty($global_settings['instagram']->setting_value))
                            <li class="float-start"><a href="{{ $global_settings['instagram']->setting_value }}" title="Instagram"><i class="fa fa-instagram social-icon instagram"></i></a></li>
                            @endif
                            @if(!empty($global_settings['youtube']->setting_value))
                            <li class="float-start"><a href="{{ $global_settings['youtube']->setting_value }}" title="YouTube"><i class="fa fa-youtube social-icon youtube"></i></a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid secondary-footer">
            <div class="container copyright">
                <div class="row pt-1 pb-1">
                    <div class="col-md-6 col-12 text-md-start text-center">
                        &copy; {{ date('Y') }} {{ $global_settings['school_name']->setting_value ?? config('app.name', 'Sekolah') }}. All rights reserved.
                    </div>
                    <div class="col-md-6 col-12 text-md-end text-center">
                        Powered by <a href="http://sekolahku.web.id">sekolahku.web.id</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    {{-- SEARCH FORM OVERLAY --}}
    <div id="search_form">
        <form action="{{ url('/search') }}" method="GET">
            <input type="text" name="keyword" autocomplete="off" placeholder="Masukan kata kunci pencarian" />
            <button type="submit" class="btn btn-lg btn-outline-light rounded-0"><i class="fa fa-search"></i> CARI</button>
        </form>
    </div>

    <a href="javascript:" id="return-to-top"><i class="fa fa-angle-double-up"></i></a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search form toggle
        document.querySelectorAll('a[href="#search_form"]').forEach(function(el) {
            el.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('search_form').classList.toggle('open');
            });
        });
        document.getElementById('search_form').addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('open');
        });

        // Scroll to top
        window.addEventListener('scroll', function() {
            var btn = document.getElementById('return-to-top');
            if (window.scrollY > 300) { btn.style.display = 'block'; } else { btn.style.display = 'none'; }
        });
        document.getElementById('return-to-top').addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
