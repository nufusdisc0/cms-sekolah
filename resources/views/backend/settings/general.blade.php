@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Pengaturan Umum</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('settings.update', 'general') }}" enctype="multipart/form-data">
                    @csrf
                                        
                    <!-- meta_description -->
                    <div class="mb-3 row">
                        <label for="meta_description" class="col-sm-3 col-form-label text-end">Deskripsi Meta</label>
                        <div class="col-sm-9">
                            <textarea rows="3" class="form-control" name="meta_description" id="meta_description">{{ $settings['meta_description']->setting_value ?? '' }}</textarea>
                        </div>
                    </div>

                    <!-- meta_keywords -->
                    <div class="mb-3 row">
                        <label for="meta_keywords" class="col-sm-3 col-form-label text-end">Kata Kunci Meta</label>
                        <div class="col-sm-9">
                            <textarea rows="2" class="form-control" name="meta_keywords" id="meta_keywords" placeholder="separated by commas (,)">{{ $settings['meta_keywords']->setting_value ?? '' }}</textarea>
                        </div>
                    </div>

                    <!-- map_location -->
                    <div class="mb-3 row">
                        <label for="map_location" class="col-sm-3 col-form-label text-end">Lokasi di Google Maps (Iframe/URL)</label>
                        <div class="col-sm-9">
                            <textarea rows="3" class="form-control" name="map_location" id="map_location">{{ $settings['map_location']->setting_value ?? '' }}</textarea>
                        </div>
                    </div>

                    <!-- favicon -->
                    <div class="mb-3 row">
                        <label for="favicon" class="col-sm-3 col-form-label text-end">Favicon</label>
                        <div class="col-sm-9">
                            @if(isset($settings['favicon']) && $settings['favicon']->setting_value)
                                <div class="mb-2">
                                    <img src="{{ asset('media_library/images/' . $settings['favicon']->setting_value) }}" alt="Favicon" width="32">
                                </div>
                            @endif
                            <input class="form-control" type="file" id="favicon" name="favicon">
                        </div>
                    </div>

                    <!-- header -->
                    <div class="mb-3 row">
                        <label for="header" class="col-sm-3 col-form-label text-end">Header / Logo App</label>
                        <div class="col-sm-9">
                            @if(isset($settings['header']) && $settings['header']->setting_value)
                                <div class="mb-2">
                                    <img src="{{ asset('media_library/images/' . $settings['header']->setting_value) }}" alt="Header" height="100">
                                </div>
                            @endif
                            <input class="form-control" type="file" id="header" name="header">
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
