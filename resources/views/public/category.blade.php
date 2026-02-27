@extends('layouts.public')

@section('title', 'Kategori: ' . $category->category_name . ' - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <h2>Kategori: {{ $category->category_name }}</h2>
    @if($category->category_description)
        <p class="text-muted">{{ $category->category_description }}</p>
    @endif
    <hr>
    <div class="row">
        @forelse($posts as $post)
        <div class="col-md-4 mb-4">
            <div class="card post-card h-100 shadow-sm">
                @if($post->post_image)
                    <img src="{{ asset('storage/' . $post->post_image) }}" class="card-img-top" alt="{{ $post->post_title }}" style="height:200px;object-fit:cover;">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $post->post_title }}</h5>
                    <p class="card-text text-muted">{{ Str::limit(strip_tags($post->post_content), 120) }}</p>
                </div>
                <div class="card-footer bg-white border-0">
                    <a href="{{ url('/post/' . $post->post_slug) }}" class="btn btn-sm btn-outline-primary">Baca Selengkapnya</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12"><p class="text-muted">Belum ada berita dalam kategori ini.</p></div>
        @endforelse
    </div>
    {{ $posts->links() }}
</div>
@endsection
