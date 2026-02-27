@extends('layouts.public')

@section('title', $post->post_title . ' - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <article>
                <h1>{{ $post->post_title }}</h1>
                <p class="text-muted">
                    <small>Dipublikasikan pada {{ $post->created_at ? $post->created_at->format('d M Y H:i') : '-' }}</small>
                </p>
                @if($post->post_image)
                    <img src="{{ asset('storage/' . $post->post_image) }}" class="img-fluid rounded mb-4" alt="{{ $post->post_title }}">
                @endif
                <div class="post-content">
                    {!! $post->post_content !!}
                </div>
                @if($post->post_tags)
                    <div class="mt-4">
                        <strong>Tags:</strong>
                        @foreach(explode(',', $post->post_tags) as $tag)
                            <span class="badge bg-secondary">{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                @endif
            </article>
            <hr>
            <a href="{{ url('/') }}" class="btn btn-outline-primary">&larr; Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection
