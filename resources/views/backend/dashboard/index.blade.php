@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Messages</h5>
                <p class="card-text display-4">{{ $widget_box->messages }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Comments</h5>
                <p class="card-text display-4">{{ $widget_box->comments }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Posts</h5>
                <p class="card-text display-4">{{ $widget_box->posts }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">Pages</h5>
                <p class="card-text display-4">{{ $widget_box->pages }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <h3>Recent Comments</h3>
        <ul class="list-group">
            @forelse($recent_comments as $comment)
                <li class="list-group-item">{{ $comment->comment_author }}: {{ $comment->comment_content }}</li>
            @empty
                <li class="list-group-item">No recent comments.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
