@extends('layouts.public')

@section('title', config('app.name', 'CMS Sekolahku') . ' - Beranda')

@section('header_content')
{{-- IMAGE SLIDERS --}}
@if($sliders->count())
<div class="container {{ $quotes->count() == 0 ? 'mb-3' : '' }}">
    <div id="slide-indicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators mt-3 mb-3">
            @foreach($sliders as $idx => $slider)
                <button type="button" data-bs-target="#slide-indicators" data-bs-slide-to="{{ $idx }}" class="{{ $idx == 0 ? 'active' : '' }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner pt-0">
            @foreach($sliders as $idx => $slider)
            <div class="carousel-item {{ $idx == 0 ? 'active' : '' }}">
                @if($slider->image)
                    <img src="{{ asset('storage/' . $slider->image) }}" class="img-fluid w-100" alt="{{ $slider->caption }}">
                @else
                    <div style="height:350px;background:#111b51;"></div>
                @endif
                <div class="carousel-caption d-none d-md-block">
                    <p class="text-center mb-3">{{ $slider->caption }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- QUOTE --}}
@if($quotes->count())
<div class="container mb-3">
    <div class="quote-bar">
        <div class="quote-title"><i class="fa fa-comments"></i> KUTIPAN</div>
        <div class="quote-text">
            @foreach($quotes as $q)
                {{ $q->quote }}. <span>{{ $q->quote_by }}</span>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection

@section('content')
{{-- MAIN CONTENT (8 col) --}}
<div class="col-lg-8 col-md-8 col-sm-12">
    {{-- TULISAN TERBARU --}}
    @if($posts->count())
    <h5 class="page-title mb-3">Tulisan Terbaru</h5>
    @foreach($posts as $post)
    <div class="card border border-secondary mb-3">
        <div class="row g-0">
            <div class="col-md-5">
                @if($post->post_image)
                    <img src="{{ asset('storage/' . $post->post_image) }}" class="card-img rounded-0" alt="{{ $post->post_title }}" style="height:100%;object-fit:cover;">
                @else
                    <div style="height:180px;background:#ddd;display:flex;align-items:center;justify-content:center;"><i class="fa fa-image fa-3x text-muted"></i></div>
                @endif
            </div>
            <div class="col-md-7">
                <div class="card-body p-3">
                    <h5 class="card-title"><a href="{{ url('/post/' . $post->post_slug) }}">{{ $post->post_title }}</a></h5>
                    <p class="card-text mb-0">{{ Str::limit(strip_tags($post->post_content), 165) }}</p>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <small class="text-muted">{{ $post->created_at ? $post->created_at->format('d/m/Y H:i') : '' }} - Oleh {{ $post->post_author ?? 'Admin' }} - Dilihat {{ $post->post_counter ?? 0 }} kali</small>
                        <a href="{{ url('/post/' . $post->post_slug) }}" class="btn btn-sm action-button rounded-0"><i class="fa fa-search"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @else
    <h5 class="page-title mb-3">Tulisan Terbaru</h5>
    <p class="text-muted">Belum ada tulisan.</p>
    @endif

    {{-- FOTO TERBARU (ALBUMS) --}}
    @if($albums->count())
    <h5 class="page-title mt-4 mb-3">Foto Terbaru</h5>
    <div class="row">
        @foreach($albums as $album)
        <div class="col-md-6 mb-3">
            <div class="card h-100 shadow-sm border border-secondary rounded-0">
                @if($album->album_cover)
                    <img src="{{ asset('storage/' . $album->album_cover) }}" class="card-img-top rounded-0 img-fluid p-2" alt="{{ $album->album_title }}">
                @else
                    <div style="height:200px;background:#ddd;display:flex;align-items:center;justify-content:center;"><i class="fa fa-image fa-3x text-muted"></i></div>
                @endif
                <div class="card-body pb-2">
                    <h5 class="card-title">{{ $album->album_title }}</h5>
                    <p class="card-text">{{ $album->album_description }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- VIDEO TERBARU --}}
    @if($videos->count())
    <h5 class="page-title mt-4 mb-3">Video Terbaru</h5>
    <div class="row">
        @foreach($videos as $video)
        <div class="col-md-6 mb-3">
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item w-100" style="min-height:250px;" src="https://www.youtube.com/embed/{{ $video->post_content }}" allowfullscreen></iframe>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- SIDEBAR (4 col) --}}
<div class="col-lg-4 col-md-4 col-sm-12 sidebar">
    {{-- SAMBUTAN KEPALA SEKOLAH --}}
    @if($headmaster)
    <div class="card rounded-0 border border-secondary mb-3">
        @if($headmaster_photo)
            <img src="{{ asset('storage/' . $headmaster_photo) }}" class="card-img-top rounded-0" alt="Headmaster">
        @endif
        <div class="card-body">
            <h5 class="card-title text-center text-uppercase">{{ $headmaster }}</h5>
            <p class="card-text text-center mt-0 text-muted">- {{ config('app.name', 'Kepala Sekolah') }} -</p>
            @if($opening_speech)
                <p class="card-text text-justify">{{ Str::limit(strip_tags($opening_speech->post_content), 120) }}</p>
            @endif
        </div>
        @if($opening_speech)
        <div class="card-footer text-center">
            <small class="text-muted text-uppercase"><a href="{{ url('/page/' . $opening_speech->post_slug) }}">Selengkapnya</a></small>
        </div>
        @endif
    </div>
    @endif

    {{-- TAUTAN --}}
    @if($links->count())
    <h5 class="page-title mb-3">Tautan</h5>
    <div class="list-group mb-3">
        @foreach($links as $link)
            <a href="{{ $link->link_url }}" class="list-group-item list-group-item-action rounded-0" target="{{ $link->link_target }}">{{ $link->link_title }}</a>
        @endforeach
    </div>
    @endif

    {{-- ARSIP --}}
    @if(isset($archives) && $archives->count())
    <h5 class="page-title mt-3 mb-3">Arsip {{ date('Y') }}</h5>
    @php
        $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    @endphp
    @foreach($archives as $archive)
        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center rounded-0 mb-1">
            {{ $bulan[$archive->month_num] ?? 'Unknown' }}
            <small class="border border-secondary pt-1 pb-1 pe-2 ps-2">{{ $archive->count }}</small>
        </a>
    @endforeach
    @endif

    {{-- PALING DIKOMENTARI --}}
    @if($most_commented->count())
    <h5 class="page-title mt-3 mb-3">Paling Dilihat / Dikomentari</h5>
    <div class="list-group mt-3 mb-3">
        @foreach($most_commented as $post)
        <a href="{{ url('/post/' . $post->post_slug) }}" class="list-group-item list-group-item-action rounded-0">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="card-text fw-bold">{{ $post->post_title }}</h6>
            </div>
            <small class="text-muted">{{ $post->created_at ? $post->created_at->format('d/m/Y H:i') : '' }} - Oleh {{ $post->post_author ?? 'Admin' }} - Dilihat {{ $post->post_counter ?? 0 }} kali</small>
        </a>
        @endforeach
    </div>
    @endif

    {{-- JAJAK PENDAPAT (POLL) --}}
    @if($active_question)
    <h5 class="page-title mt-3 mb-3">Jajak Pendapat</h5>
    <div class="card rounded-0 border border-secondary mb-3">
        <div class="card-body">
            <p>{{ $active_question->question }}</p>
            @if(count($answers))
                <form id="pollForm">
                    @foreach($answers as $option)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_id" id="answer_id_{{ $option->id }}" value="{{ $option->id }}">
                        <label class="form-check-label" for="answer_id_{{ $option->id }}">{{ $option->answer }}</label>
                    </div>
                    @endforeach
                </form>
            @endif
        </div>
        <div class="card-footer">
            <div class="btn-group">
                <button type="button" class="btn action-button rounded-0"><i class="fa fa-send"></i> Submit</button>
                <a href="#" class="btn action-button rounded-0"><i class="fa fa-bar-chart"></i> Hasil</a>
            </div>
        </div>
    </div>
    @endif

    {{-- BERLANGGANAN --}}
    <h5 class="page-title mt-3 mb-3">Berlangganan</h5>
    <form class="card p-1 border border-secondary mt-2 mb-2 rounded-0">
        <div class="input-group">
            <input type="email" class="form-control rounded-0 border border-secondary" placeholder="Email Address...">
            <button type="button" class="btn action-button rounded-0"><i class="fa fa-envelope"></i></button>
        </div>
    </form>

    {{-- IKLAN / BANNERS --}}
    @if($banners->count())
    <h5 class="page-title mt-3 mb-3">Iklan</h5>
    @foreach($banners as $banner)
        <a href="{{ $banner->link_url }}" title="{{ $banner->link_title }}">
            @if($banner->link_image)
                <img src="{{ asset('storage/' . $banner->link_image) }}" class="img-fluid mb-2 w-100" alt="{{ $banner->link_title }}">
            @else
                <div style="height:100px;background:#ddd;display:flex;align-items:center;justify-content:center;margin-bottom:8px;" class="w-100"><i class="fa fa-image fa-2x text-muted"></i></div>
            @endif
        </a>
    @endforeach
    @endif
</div>
@endsection
