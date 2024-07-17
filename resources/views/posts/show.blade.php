@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $post->title }}</div>

                <div class="card-body">
                    <p>{{ $post->body }}</p>
                    <p>
                        <small class="text-muted">By {{ $post->user->name }} on {{ $post->created_at->format('M d, Y') }}</small>
                    </p>

                    @can('update-post', $post)
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary">Edit Post</a>
                        <form method="POST" action="{{ route('posts.destroy', $post) }}" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete Post</button>
                        </form>
                    @else
                        <p>You cannot edit this post.</p>
                    @endcan

                    <h5>Comments</h5>
                    @foreach ($post->comments as $comment)
                        <div class="card mb-3">
                            <div class="card-body">
                                <p>{{ $comment->body }}</p>
                                <p>
                                    <small class="text-muted">By {{ $comment->user->name }} on {{ $comment->created_at->format('M d, Y') }}</small>
                                </p>

                                @can('update-comment', $comment)
                                    <a href="{{ route('comments.edit', $comment) }}" class="btn btn-primary">Edit Comment</a>
                                    <form method="POST" action="{{ route('comments.destroy', $comment) }}" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete Comment</button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    @endforeach

                    <h5>Add a Comment</h5>
                    <form method="POST" action="{{ route('posts.addComment', $post) }}">
                        @csrf
                        <div class="form-group">
                            <textarea id="body" class="form-control @error('body') is-invalid @enderror" name="body" required></textarea>
                            @error('body')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <br>
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">Add Comment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
