@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Pengaturan Profil Sekolah</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('settings.update', 'school_profile') }}" enctype="multipart/form-data">
                    @csrf
                                        
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-end">NPSN / NSS</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="npsn" value="{{ $settings['npsn']->setting_value ?? '' }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-end">Nama Sekolah</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="school_name" value="{{ $settings['school_name']->setting_value ?? '' }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-end">Moto / Tagline</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="tagline" value="{{ $settings['tagline']->setting_value ?? '' }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-end">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" name="email" value="{{ $settings['email']->setting_value ?? '' }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-end">Website</label>
                        <div class="col-sm-9">
                            <input type="url" class="form-control" name="website" value="{{ $settings['website']->setting_value ?? '' }}">
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-end">Alamat</label>
                        <div class="col-sm-9">
                            <textarea rows="3" class="form-control" name="street_address">{{ $settings['street_address']->setting_value ?? '' }}</textarea>
                        </div>
                    </div>

                    <!-- Logo -->
                    <div class="mb-3 row">
                        <label for="logo" class="col-sm-3 col-form-label text-end">Logo Utama</label>
                        <div class="col-sm-9">
                            @if(isset($settings['logo']) && $settings['logo']->setting_value)
                                <div class="mb-2">
                                    <img src="{{ asset('media_library/images/' . $settings['logo']->setting_value) }}" alt="Logo" height="100">
                                </div>
                            @endif
                            <input class="form-control" type="file" id="logo" name="logo">
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> SIMPAN PENGATURAN</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
