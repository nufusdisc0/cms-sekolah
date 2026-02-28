<!doctype html>
<html lang="id">
<head>
    <title>{{ $global_settings['school_name']->setting_value ?? config('app.name', 'CMS Sekolahku') }} - Login</title>
    @php $favicon = isset($global_settings['logo']) && !empty($global_settings['logo']->setting_value) ? asset('media_library/images/' . $global_settings['logo']->setting_value) : asset('images/logo.png'); @endphp
    <link rel="icon" type="image/png" href="{{ $favicon }}">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: stretch;
        }
        .login-image {
            background-image: linear-gradient(rgba(0, 50, 100, 0.7), rgba(0, 0, 0, 0.6)), url('{{ asset('storage/sliders/school_banner_2.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 3rem;
            text-align: center;
        }
        .login-form-wrapper {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 4rem 2rem;
            background-color: white;
        }
        .form-signin {
            width: 100%;
            max-width: 400px;
        }
        .form-floating > label {
            color: #6c757d;
        }
        .form-control {
            border-radius: 0.5rem;
            padding: 1rem 0.75rem;
            border-color: #ced4da;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .btn-primary {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
        }
        @media (max-width: 991.98px) {
            .login-image {
                display: none !important;
            }
            .login-form-wrapper {
                min-height: 100vh;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0 login-container">
            <!-- Left Side: Image & Welcome Text -->
            <div class="col-lg-6 login-image d-none d-lg-flex position-relative">
                <div class="position-absolute top-0 start-0 p-4">
                    <a href="{{ url('/') }}" class="text-white text-decoration-none fw-bold" style="font-size: 1.2rem;">
                        <i class="fa fa-arrow-left me-2"></i> Kembali ke Beranda
                    </a>
                </div>
                <img src="{{ asset('images/logo.png') }}" alt="Logo" width="150" class="mb-4" onerror="this.style.display='none'">
                <h1 class="display-5 fw-bold mb-3">Selamat Datang di Portal Admin</h1>
                <p class="lead fw-normal mb-0" style="max-width: 80%;">Kelola konten situs web, data guru, berita sekolah, dan konfigurasi lainnya dari satu dasbor yang terpusat dan modern.</p>
            </div>

            <!-- Right Side: Login Form -->
            <div class="col-lg-6 login-form-wrapper shadow-lg">
                <form class="form-signin" method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="text-center mb-5 d-lg-none">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" width="100" class="mb-3" onerror="this.style.display='none'">
                        <h3 class="fw-bold" style="color:#343a40;">{{ $global_settings['school_name']->setting_value ?? config('app.name', 'CMS Sekolahku') }}</h3>
                    </div>

                    <div class="mb-5 d-none d-lg-block">
                        <h2 class="fw-bold text-dark mb-1">Masuk ke Dasbor</h2>
                        <p class="text-muted">Silakan masukkan username dan password Anda.</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger p-3 mb-4 rounded-3 d-flex align-items-center border-0 bg-danger text-white">
                            <i class="fa fa-exclamation-circle me-2 fs-5"></i>
                            <div>{{ $errors->first() }}</div>
                        </div>
                    @endif

                    @if(session('status'))
                        <div class="alert alert-success p-3 mb-4 rounded-3 d-flex align-items-center border-0 bg-success text-white">
                            <i class="fa fa-check-circle me-2 fs-5"></i>
                            <div>{{ session('status') }}</div>
                        </div>
                    @endif

                    <div class="form-floating mb-4">
                        <input autofocus autocomplete="off" type="text" id="user_name" name="user_name" value="{{ old('user_name') }}" class="form-control bg-light" placeholder="Username" required>
                        <label for="user_name"><i class="fa fa-user me-2"></i>Username</label>
                    </div>

                    <div class="form-floating mb-4 position-relative">
                        <input type="password" id="password" name="password" class="form-control bg-light" placeholder="Password" required>
                        <label for="password"><i class="fa fa-lock me-2"></i>Password</label>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label text-muted" for="remember">
                                Ingat Saya
                            </label>
                        </div>
                        <a href="#" class="text-decoration-none small text-primary fw-semibold">Lupa Password?</a>
                    </div>

                    <button class="btn btn-primary btn-lg w-100 mb-4" type="submit">
                        <i class="fa fa-sign-in me-2"></i> Masuk Sekarang
                    </button>
                    
                    <div class="text-center mt-auto">
                        <p class="text-muted small mb-0">&copy; {{ date('Y') }} {{ $global_settings['school_name']->setting_value ?? config('app.name', 'CMS Sekolahku') }}. All rights reserved.</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
