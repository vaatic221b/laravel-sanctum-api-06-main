@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __('Posts') }}
                    <a href="{{ route('posts.create') }}" class="btn btn-primary float-right">Create Post</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @foreach ($posts as $post)
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">{{ $post->title }}</h5>
                                <p class="card-text">{{ $post->body }}</p>
                                <p class="card-text">
                                    <small class="text-muted">By {{ $post->user->name }} on {{ $post->created_at->format('M d, Y') }}</small>
                                </p>
                                <a href="{{ route('posts.show', $post) }}" class="btn btn-primary">View Post</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
