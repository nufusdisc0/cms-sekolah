@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ubah Profil</h5>
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

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    
                    <div class="mb-3 row">
                        <label for="user_name" class="col-sm-3 col-form-label text-end">Nama Pengguna</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bg-light" id="user_name" value="{{ $user->user_name }}" disabled>
                            <div class="form-text">Nama pengguna tidak dapat diubah</div>
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <label for="user_full_name" class="col-sm-3 col-form-label text-end">Nama Lengkap</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="user_full_name" id="user_full_name" value="{{ old('user_full_name', $user->user_full_name) }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <label for="user_email" class="col-sm-3 col-form-label text-end">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" name="user_email" id="user_email" value="{{ old('user_email', $user->user_email) }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <label for="user_url" class="col-sm-3 col-form-label text-end">URL</label>
                        <div class="col-sm-9">
                            <input type="url" class="form-control" name="user_url" id="user_url" value="{{ old('user_url', $user->user_url) }}">
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <label for="user_biography" class="col-sm-3 col-form-label text-end">Biografi</label>
                        <div class="col-sm-9">
                            <textarea rows="5" class="form-control" name="user_biography" id="user_biography">{{ old('user_biography', $user->user_biography) }}</textarea>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> SIMPAN PERUBAHAN</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
