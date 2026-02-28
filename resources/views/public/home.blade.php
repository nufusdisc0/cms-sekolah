@extends('layouts.public')

@section('title', ($global_settings['school_name']->setting_value ?? config('app.name', 'CMS Sekolahku')) . ' - Beranda')

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

{{-- QUOTE RUNNING TEXT --}}
@if($quotes->count())
<div class="container mb-3">
    <div class="quote-bar" style="display: flex; align-items: center; overflow: hidden; height: 50px;">
        <div class="quote-title" style="flex-shrink: 0; z-index: 2;"><i class="fa fa-comments"></i> KUTIPAN</div>
        <div id="quoteContainer" style="flex: 1; overflow: hidden; height: 50px; display: flex; align-items: center;">
            <span id="quoteMarquee" style="display: inline-block; white-space: nowrap; color: #fff; font-size: 14px; padding-left: 100%; animation: marqueeScroll 18s linear infinite;"></span>
        </div>
    </div>
</div>
<style>
    @keyframes marqueeScroll {
        0% { transform: translateX(0); }
        100% { transform: translateX(-100%); }
    }
    #quoteMarquee { will-change: transform; }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var quotes = @json($quotes->map(function($q) { return ['quote' => $q->quote, 'by' => $q->quote_by]; })->values());
        if (!quotes.length) return;
        var el = document.getElementById('quoteMarquee');
        var idx = 0;
        function setQuote() {
            el.innerHTML = '"' + quotes[idx].quote + '" — <span style="color:#ff5a2c;font-weight:bold;">' + quotes[idx].by + '</span>';
            idx = (idx + 1) % quotes.length;
        }
        setQuote();
        setInterval(function() {
            el.style.animation = 'none';
            el.offsetHeight; // trigger reflow
            el.style.animation = 'marqueeScroll 18s linear infinite';
            setQuote();
        }, 18000);
    });
</script>
@endif
@endsection

@section('content')
{{-- MAIN CONTENT (8 col) --}}
<div class="col-lg-8 col-md-8 col-sm-12">

    {{-- SAMBUTAN KEPALA SEKOLAH --}}
    @if(isset($opening_speech) && $opening_speech)
    <h5 class="page-title mb-3">Sambutan Kepala Sekolah</h5>
    <div class="card border-0 shadow-sm mb-4 overflow-hidden" style="border-radius: 8px;">
        <div class="card-body p-4">
            <div class="row align-items-start">
                <div class="col-md-4 text-center mb-3 mb-md-0">
                    @if(isset($headmaster_photo) && $headmaster_photo)
                        <img src="{{ asset('storage/media_library/images/' . $headmaster_photo) }}" class="img-fluid rounded shadow-sm" style="max-height: 220px; object-fit: cover; width: 100%;" alt="Kepala Sekolah">
                    @else
                        <div class="d-flex align-items-center justify-content-center rounded" style="height: 200px; background: linear-gradient(135deg, #111b51 0%, #1a2980 100%);">
                            <i class="fa fa-user-circle text-white" style="font-size: 80px; opacity: 0.5;"></i>
                        </div>
                    @endif
                    @if(isset($headmaster) && $headmaster)
                        <h6 class="fw-bold mt-3 mb-0" style="color: #111b51;">{{ $headmaster }}</h6>
                        <small class="text-muted">Kepala Sekolah</small>
                    @endif
                </div>
                <div class="col-md-8">
                    <div class="speech-content" style="font-size: 14px; line-height: 1.8; color: #444; text-align: justify;">
                        {!! Str::limit(strip_tags($opening_speech->post_content), 600) !!}
                        @if(strlen(strip_tags($opening_speech->post_content)) > 600)
                            <a href="{{ url('/page/sambutan-kepala-sekolah') }}" class="text-decoration-none fw-bold" style="color: #ff5a2c;">... Selengkapnya <i class="fa fa-arrow-right"></i></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

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
    @if(isset($archives) && count($archives) > 0)
    <h5 class="page-title mt-3 mb-3">Arsip Berita</h5>
    @php
        $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    @endphp
    <div class="accordion mb-4 shadow-sm rounded-3 overflow-hidden" id="accordionArsip">
        @foreach($archives as $year => $months)
            <div class="accordion-item border-0 border-bottom">
                <h2 class="accordion-header" id="headingArsip{{ $year }}">
                    <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }} fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseArsip{{ $year }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapseArsip{{ $year }}">
                        Tahun {{ $year }}
                    </button>
                </h2>
                <div id="collapseArsip{{ $year }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="headingArsip{{ $year }}" data-bs-parent="#accordionArsip">
                    <div class="accordion-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($months as $archive)
                                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 border-bottom" style="transition: all 0.2s; background: #fafafa;">
                                    <div class="d-flex align-items-center ps-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 28px; height: 28px; background-color: rgba(255,90,44,0.1); color: #ff5a2c;">
                                            <i class="fa fa-calendar-o" style="font-size: 0.8rem;"></i>
                                        </div>
                                        <span class="text-dark fw-medium small">{{ $bulan[$archive->month_num] ?? 'Unknown' }}</span>
                                    </div>
                                    <span class="badge rounded-pill me-2" style="background-color: #111b51; font-weight: 500;">{{ $archive->count }} Berita</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif

    {{-- PALING DIKOMENTARI --}}
    @if($most_commented->count())
    <h5 class="page-title mt-3 mb-3">Popular Post</h5>
    <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden">
        <div class="list-group list-group-flush">
            @foreach($most_commented as $index => $post)
            <a href="{{ url('/post/' . $post->post_slug) }}" class="list-group-item list-group-item-action py-3 border-bottom" style="transition: all 0.2s;">
                <div class="d-flex align-items-start">
                    <h4 class="fw-bold me-3 mb-0" style="color: {{ $index < 3 ? '#ff5a2c' : '#adb5bd' }}; opacity: 0.8;">{{ sprintf('%02d', $index + 1) }}</h4>
                    <div>
                        <h6 class="mb-1 text-dark fw-bold lh-base" style="font-size: 0.95rem;">{{ Str::limit($post->post_title, 55) }}</h6>
                        <div class="d-flex align-items-center small text-muted mt-2" style="font-size: 0.75rem;">
                            <span class="me-3"><i class="fa fa-clock-o me-1"></i> {{ $post->created_at ? $post->created_at->format('d M Y') : '' }}</span>
                            <span><i class="fa fa-eye me-1"></i> {{ $post->post_counter ?? 0 }} Views</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
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
    <div class="card p-2 border border-secondary mt-2 mb-4 rounded-0">
        <form class="mb-1">
            <div class="input-group">
                <input type="email" class="form-control rounded-0 border border-secondary" placeholder="Email Address...">
                <button type="button" class="btn action-button rounded-0"><i class="fa fa-envelope"></i></button>
            </div>
        </form>
    </div>

    {{-- IKLAN / BANNERS --}}
    @if($banners->count())
        <h5 class="page-title mt-3 mb-3">Sponsor & Iklan</h5>
        <div class="banners-section mb-4">
            @foreach($banners as $banner)
                <a href="{{ $banner->banner_url ?? '#' }}" title="{{ $banner->banner_title }}" class="d-block mb-3" target="_blank">
                    @if($banner->banner_image)
                        <img src="{{ asset('storage/banners/' . $banner->banner_image) }}" class="img-fluid w-100 shadow-sm rounded-2" style="border: 1px solid rgba(0,0,0,0.1);" alt="{{ $banner->banner_title }}">
                    @else
                        <div style="height:120px;background:#f8f9fa;display:flex;align-items:center;justify-content:center;" class="w-100 border text-muted shadow-sm rounded-2">
                            <i class="fa fa-image fa-2x"></i>
                        </div>
                    @endif
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
