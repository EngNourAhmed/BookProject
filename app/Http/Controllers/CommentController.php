<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Article;
use App\Models\CommentLike;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $articleId)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $article = Article::findOrFail($articleId);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'article_id' => $article->id,
            'content' => $request->content,
            'parent_id' => $request->parent_id // Support for replies
        ]);

        // Send Notification to author
        if ($article->user_id !== auth()->id()) {
            $article->user->notify(new \App\Notifications\SystemNotification(
                'New Comment!',
                auth()->user()->name . ' commented on your article: ' . $article->title,
                ['article_id' => $article->id]
            ));
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'comment' => $comment->load('user')
            ]);
        }

        return back()->with('success', 'Comment posted successfully');
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        
        if($comment->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate(['content' => 'required|string|max:1000']);
        $comment->update(['content' => $request->content]);

        return response()->json(['success' => true, 'message' => 'Comment updated!']);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Comment deleted']);
        }

        return back()->with('success', 'Comment deleted');
    }

    public function like($id)
    {
        $comment = Comment::findOrFail($id);
        $user = auth()->user();

        $like = \App\Models\CommentLike::where('user_id', $user->id)
                                      ->where('comment_id', $comment->id)
                                      ->first();

        if ($like) {
            $like->delete();
            $status = 'unliked';
        } else {
            \App\Models\CommentLike::create([
                'user_id' => $user->id,
                'comment_id' => $comment->id
            ]);
            $status = 'liked';
        }

        return response()->json([
            'success' => true,
            'status' => $status,
            'likes_count' => $comment->likes()->count()
        ]);
    }
}
