@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ubah Kata Sandi</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.password.update') }}">
                    @csrf
                    
                    <div class="mb-3 row">
                        <label for="current_password" class="col-sm-4 col-form-label text-end">Kata Sandi Saat Ini</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" name="current_password" id="current_password" required>
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <label for="new_password" class="col-sm-4 col-form-label text-end">Kata Sandi Baru</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" name="new_password" id="new_password" required minlength="6">
                            <div class="form-text">Minimal 6 karakter.</div>
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <label for="new_password_confirmation" class="col-sm-4 col-form-label text-end">Konfirmasi Kata Sandi Baru</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation" required minlength="6">
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-sm-8 offset-sm-4">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-key"></i> UBAH KATA SANDI</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
