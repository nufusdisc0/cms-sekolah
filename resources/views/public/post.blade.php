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

            <!-- Comments Section -->
            <div class="mt-5">
                <h4 class="mb-4">{{ $comments->count() }} Komentar</h4>
                
                @if(session('success'))
                    <div class="alert alert-success"><i class="fa fa-check-circle me-1"></i> {{ session('success') }}</div>
                @endif

                @forelse($comments as $comment)
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 20px;">
                                <i class="fa fa-user"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-bold mb-1">{{ $comment->comment_author }}</h6>
                            <small class="text-muted d-block mb-2">{{ $comment->created_at->format('d M Y H:i') }}</small>
                            <p class="mb-0">{{ nl2br(e($comment->comment_content)) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                @endforelse

                <hr class="my-5">

                <!-- Comment Form -->
                <h4 class="mb-4">Tinggalkan Komentar</h4>
                <form action="{{ route('public.post.comment', $post->post_slug) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="comment_author" class="form-control @error('comment_author') is-invalid @enderror" value="{{ old('comment_author') }}" required>
                            @error('comment_author') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="comment_email" class="form-control @error('comment_email') is-invalid @enderror" value="{{ old('comment_email') }}" required>
                            @error('comment_email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Situs Web</label>
                        <input type="url" name="comment_url" class="form-control @error('comment_url') is-invalid @enderror" value="{{ old('comment_url') }}">
                        @error('comment_url') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Komentar <span class="text-danger">*</span></label>
                        <textarea name="comment_content" rows="4" class="form-control @error('comment_content') is-invalid @enderror" required>{{ old('comment_content') }}</textarea>
                        @error('comment_content') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="btn" style="background-color: #ff5a2c; color: white;">Kirim Komentar</button>
                </form>
            </div>

            <hr class="my-5">
            <a href="{{ url('/') }}" class="btn btn-outline-primary">&larr; Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection
