@extends('layouts.public')

@section('title', $page->post_title . ' - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1>{{ $page->post_title }}</h1>
            <hr>
            <div class="page-content">
                {!! $page->post_content !!}
            </div>
        </div>
    </div>
</div>
@endsection
