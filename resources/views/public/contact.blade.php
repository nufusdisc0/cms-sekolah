@extends('layouts.public')

@section('title', 'Kontak - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1>Hubungi Kami</h1>
            <hr>
            <p>Silakan hubungi kami melalui informasi di bawah ini:</p>
            <div class="card">
                <div class="card-body">
                    <p><strong>Alamat:</strong> -</p>
                    <p><strong>Telepon:</strong> -</p>
                    <p><strong>Email:</strong> -</p>
                    <p class="text-muted">Informasi kontak dapat diatur melalui menu Pengaturan di halaman admin.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
