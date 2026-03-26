<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Article;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Request $request, $articleId)
    {
        $user = auth()->user();
        $article = Article::findOrFail($articleId);

        $like = Like::where('user_id', $user->id)
                    ->where('article_id', $article->id)
                    ->first();

        if ($like) {
            $like->delete();
            $status = 'unliked';
        } else {
            Like::create([
                'user_id' => $user->id,
                'article_id' => $article->id
            ]);
            $status = 'liked';

            // Send Notification to author
            if ($article->user_id !== $user->id) {
                $article->user->notify(new \App\Notifications\SystemNotification(
                    'New Like!',
                    $user->name . ' liked your article: ' . $article->title,
                    ['article_id' => $article->id]
                ));
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'status' => $status,
                'likes_count' => $article->likes()->count()
            ]);
        }

        return back()->with('success', 'Operation successful');
    }
}
