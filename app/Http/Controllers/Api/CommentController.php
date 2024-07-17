<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'body' => 'required|string',
        ]);

        $comment = Comment::create([
            'post_id' => $request->post_id,
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        return response()->json($comment, 201);
    }

    public function edit(Comment $comment)
    {
        if (Gate::denies('update-comment', $comment)) {
            return redirect()->route('home')->with('error', 'Unauthorized action.');
        }

        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        if (Gate::denies('update-comment', $comment)) {
            return redirect()->route('home')->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'body' => 'required|string',
        ]);

        $comment->update($request->only('body'));

        if ($request->expectsJson()) {
            return response()->json($comment);
        }

        return redirect()->route('posts.show', $comment->post)->with('success', 'Comment updated successfully.');
    }

    public function destroy(Comment $comment)
    {
        if (Gate::denies('delete-comment', $comment)) {
            return redirect()->route('home')->with('error', 'Unauthorized action.');
        }

        $comment->delete();

        if (request()->expectsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('posts.show', $comment->post)->with('success', 'Comment deleted successfully.');
    }
}
