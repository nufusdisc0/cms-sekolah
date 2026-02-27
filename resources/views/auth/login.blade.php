<!doctype html>
<html lang="id">
<head>
    <title>{{ config('app.name', 'CMS Sekolahku') }} - Login</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        html, body { height: 100%; }
        body {
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #ffffff;
        }
        .form-signin {
            width: 100%;
            max-width: 550px;
            padding: 15px;
            margin: auto;
        }
        .form-signin .form-control {
            position: relative;
            box-sizing: border-box;
            height: auto;
            padding: 10px;
            font-size: 16px;
        }
        .form-signin .form-control:focus {
            z-index: 2;
        }
        .form-signin input[type="text"] {
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        .form-signin input[type="password"] {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>
<body class="text-center">
    <form class="form-signin" method="POST" action="{{ route('login') }}">
        @csrf
        <img class="mb-4" src="{{ asset('images/logo.png') }}" width="120" onerror="this.style.display='none'">
        <h2 class="fw-bold" style="color:#343a40;">{{ config('app.name', 'CMS Sekolahku') }}</h2>
        <h6 class="fw-bold text-dark mb-4">Admin Login</h6>

        @if($errors->any())
            <div class="alert alert-danger p-2 text-center">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('status'))
            <div class="alert alert-success p-2 text-center">
                {{ session('status') }}
            </div>
        @endif

        <label for="user_name" class="visually-hidden">Username</label>
        <input autofocus autocomplete="off" type="text" id="user_name" name="user_name" value="{{ old('user_name') }}" placeholder="Username..." class="form-control rounded-0 border border-secondary border-bottom-0" required>

        <label for="password" class="visually-hidden">Password</label>
        <input type="password" id="password" name="password" placeholder="Password..." class="form-control rounded-0 border border-secondary" required>

        <button class="btn btn-lg btn-primary w-100 rounded-0 mt-0" type="submit">Sign in</button>

        <p class="pt-3 text-muted">
            <a href="#">Lost Password ?</a> | Back to <a href="{{ url('/') }}">{{ config('app.name', 'Home') }}</a>
        </p>
    </form>
</body>
</html>
