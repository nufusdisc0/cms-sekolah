@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Pengaturan Email Server</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('settings.update', 'mail_server') }}">
                    @csrf
                                        
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-end">SMTP Host</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="smtp_host" value="{{ $settings['smtp_host']->setting_value ?? '' }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-end">SMTP Port</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="smtp_port" value="{{ $settings['smtp_port']->setting_value ?? '' }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-end">SMTP Username</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="smtp_user" value="{{ $settings['smtp_user']->setting_value ?? '' }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-end">SMTP Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="smtp_pass" value="{{ $settings['smtp_pass']->setting_value ?? '' }}">
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
