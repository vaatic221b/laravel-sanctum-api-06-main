<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $posts = Post::with('user')->latest()->get();
        
        if (request()->expectsJson()) {
            return response()->json($posts);
        }

        return view('home', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $post = new Post([
            'title' => $request->title,
            'body' => $request->body,
        ]);
        $post->user()->associate(auth()->user());
        $post->save();

        if ($request->expectsJson()) {
            return response()->json($post, 201);
        }

        return redirect()->route('home')->with('success', 'Post created successfully.');
    }

    public function show(Post $post)
    {
        $post->load('user', 'comments.user');
        
        if (request()->expectsJson()) {
            return response()->json($post);
        }

        return view('posts.show', compact('post'));
    }

    public function addComment(Request $request, Post $post)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $comment = new Comment([
            'body' => $request->body,
        ]);
        $comment->user()->associate(auth()->user());
        $comment->post()->associate($post);
        $comment->save();

        if ($request->expectsJson()) {
            return response()->json($comment, 201);
        }

        return redirect()->route('posts.show', $post)->with('success', 'Comment added successfully.');
    }

    public function edit(Post $post)
    {
        if (Gate::denies('update-post', $post)) {
            return redirect()->route('home')->with('error', 'Unauthorized action.');
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if (Gate::denies('update-post', $post)) {
            return redirect()->route('home')->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $post->update($request->only('title', 'body'));

        if ($request->expectsJson()) {
            return response()->json($post);
        }

        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        if (Gate::denies('delete-post', $post)) {
            return redirect()->route('home')->with('error', 'Unauthorized action.');
        }

        $post->delete();

        if (request()->expectsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('home')->with('success', 'Post deleted successfully.');
    }

}
