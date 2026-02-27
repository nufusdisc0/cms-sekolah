@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Akun Sosial Media</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('settings.update', 'social_account') }}">
                    @csrf
                                        
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-end">Facebook URL</label>
                        <div class="col-sm-9">
                            <input type="url" class="form-control" name="facebook" value="{{ $settings['facebook']->setting_value ?? '' }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-end">Twitter URL</label>
                        <div class="col-sm-9">
                            <input type="url" class="form-control" name="twitter" value="{{ $settings['twitter']->setting_value ?? '' }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-end">Instagram URL</label>
                        <div class="col-sm-9">
                            <input type="url" class="form-control" name="instagram" value="{{ $settings['instagram']->setting_value ?? '' }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-end">YouTube URL</label>
                        <div class="col-sm-9">
                            <input type="url" class="form-control" name="youtube" value="{{ $settings['youtube']->setting_value ?? '' }}">
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
