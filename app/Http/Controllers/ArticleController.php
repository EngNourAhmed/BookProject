<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Report;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    public function myArticles(Request $request)
    {
        $user = auth()->user();

        $articles = Article::where('user_id', $user->id)
            ->where('status', 'active')
            ->get();

        // لو مفيش مقالات أصلاً
        if ($articles->isEmpty()) {

            // لو API
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يوجد مقالات حالياً'
                ]);
            }

            // لو Web
            return view('articles.my', [
                'articles' => [],
                'message'  => 'لا يوجد مقالات حالياً'
            ]);
        }

        // API → JSON
        if ($request->expectsJson() || $request->is('api/*')) {

            $formatted = $articles->map(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'content' => $article->content,
                    'status' => $article->status,
                    'published_at' => $article->published_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formatted,
            ]);
        }

        // Web → View
        return view('articles.my', compact('articles'));
    }



    /**
     * عرض جميع المقالات (يدعم Web + API)
     */
    public function index(Request $request)
    {
        $query = Article::with('user');

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $articles = $query->latest()->paginate(6);

        // API
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $articles
            ], 200);
        }

        // Web
        return view('articles.index', compact('articles'));
    }

    public function show($id)
    {
        $article = Article::with('user')->findOrFail($id);
        $user = auth()->user();

        // Security: Writers can only view their own articles unless published
        // Readers can only view published articles (unless they are the author, but readers aren't authors)
        if ($user->role === 'writer' && $article->user_id !== $user->id && $article->status !== 'active') {
            return redirect()->route('writer.dashboard')->withErrors(['unauthorized' => 'You cannot view this article.']);
        }
        
        if ($user->role === 'reader' && $article->status !== 'active') {
            return redirect()->route('reader.dashboard')->withErrors(['unauthorized' => 'This article is not yet available.']);
        }

        return view('reports.show', compact('article'));
    }


    /**
     * إنشاء مقال (يدعم Web + API)
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $user = auth()->user();

        // 1) التحقق من تسجيل الدخول
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // 2) التحقق من أن للمستخدم الدور writer فقط
        if ($user->role !== 'writer') {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Only writers can create articles.'
            ], 403);
        }

        $status = $request->input('action') === 'draft' ? 'draft' : 'pending';

        // 3) إنشاء المقال
        $article = Article::create([
            'user_id' => $user->id,
            'title'   => $request->title,
            'content' => $request->content,
            'status'  => $status,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Article created successfully',
                'data' => $article
            ], 201);
        }

        $message = $status === 'draft' ? 'تم حفظ المقال كمسودة بنجاح' : 'تم إضافة المقال بنجاح';
        return back()->with('success', $message);
    }

    public function drafts(Request $request)
    {
        $user = auth()->user();
        $articles = Article::where('user_id', $user->id)
            ->where('status', 'draft')
            ->latest()
            ->get();
            
        return view('writer.drafts', compact('articles'));
    }


    public function CreateLink(Request $request)
    {
        $article = Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => auth()->id(),
        ]);

        $link = url("/post/" . $article->id);

        return response()->json([
            'article' => $article,
            'share_link' => $link
        ]);
    }

    public function openPostLink(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        // 1) لو الطلب جاي من التطبيق ومعاه توكن مستخدم → رجع المقال
        if ($request->header('X-APP') === 'mobile') {

            // لازم يكون معاه توكن تسجيل دخول
            if (!$request->user()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized - Please login first'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'article' => $article
            ]);
        }

        // 2) لو من المتصفح → تحويل لمتجر التطبيقات
        return redirect('https://play.google.com/store/apps/details?id=your.app.id');
    }




    /**
     * قبول مقال
     */
    public function approve(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $article->update([
            'status' => 'active',
            'published_at' => now(),
            'rejection_reason' => null,
        ]);

        // تحديث البلاغات المرتبطة
        Report::where('article_id', $id)->update([
            'status' => 'resolved'
        ]);

        // Notify Followers
        $writer = $article->user;
        $followerIds = $writer->followers->pluck('id')->toArray();
        if(!empty($followerIds)) {
            app(\App\Services\NotificationService::class)->sendToTarget(
                $followerIds,
                'New Release from ' . $writer->name,
                "The chapter \"{$article->title}\" is now available in the library.",
                ['article_id' => $article->id]
            );
        }

        return $request->expectsJson()
            ? response()->json(['success' => true, 'message' => 'تم قبول المقال وحل البلاغات المرتبطة.'])
            : redirect()->back()->with('success', 'تم قبول المقال وحل البلاغات المرتبطة.');
    }


    /**
     * رفض مقال
     */
    public function reject(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        // حذف البلاغات المرتبطة بالمقال قبل حذف المقال
        Report::where('article_id', $id)->delete();

        // حذف المقال نهائياً
        $article->delete();

        return $request->expectsJson()
            ? response()->json([
                'success' => true,
                'message' => 'تم رفض المقال وحذفه من قاعدة البيانات مع حذف البلاغات.'
            ])
            : redirect()->back()->with('success', 'تم رفض المقال وحذفه من قاعدة البيانات مع حذف البلاغات.');
    }
}
