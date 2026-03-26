<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/register', function () {
    return view('register');
})->name('register');
Route::post('/register', [UserController::class, 'register'])->name('register.post');
Route::get('/login', function () {
    return view('login');
})->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.post');

Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// =====================================================================================

// User Management - Admin Only 

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
    Route::put('/users/{user}/status', [UserController::class, 'updateStatus'])->name('dashboard.updateStatus');
    Route::put('/users/{id}/role', [UserController::class, 'updateRole'])->name('dashboard.updateRole');
});

// Writer Dashboard
Route::middleware(['auth', 'writer'])->group(function () {
    Route::get('/writer/dashboard', function () {
        $user = auth()->user();
        $articles = \App\Models\Article::where('user_id', $user->id)->latest()->get();
        
        $topArticles = \App\Models\Article::where('user_id', $user->id)
            ->withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->take(3)
            ->get();
            
        $recentComments = \App\Models\Comment::whereIn('article_id', $articles->pluck('id'))
            ->with(['user', 'article'])
            ->latest()
            ->take(5)
            ->get();
            
        $followersCount = $user->followers()->count();

        return view('writer.dashboard', compact('articles', 'topArticles', 'recentComments', 'followersCount'));
    })->name('writer.dashboard');
    
    Route::get('/writer/drafts', [\App\Http\Controllers\ArticleController::class, 'drafts'])->name('writer.drafts');
});

// Social Features
Route::middleware(['auth'])->group(function () {
    Route::post('/articles/{id}/like', [LikeController::class, 'toggle'])->name('likes.toggle');
    Route::post('/articles/{id}/comment', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::patch('/comments/{id}', [CommentController::class, 'update'])->name('comments.update');
    Route::post('/comments/{id}/like', [CommentController::class, 'like'])->name('comments.like');
    
    // Profiles
    Route::get('/profile/writer/{id}', [ProfileController::class, 'show'])->name('writer.profile');
    Route::put('/profile/writer/{id}', [ProfileController::class, 'update'])->name('writer.profile.update');
    
    // Follow System
    Route::post('/follow/{user}', [\App\Http\Controllers\FollowController::class, 'toggle'])->name('follows.toggle');
    
    // User Notifications
    Route::get('/my-notifications', [NotificationController::class, 'index'])->name('notifications.user_index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    
    // Follow System
    Route::post('/follow/{user}', [\App\Http\Controllers\FollowController::class, 'toggle'])->name('follows.toggle');

    // Messaging System (Messenger Style)
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{id}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
});

// Reader Dashboard
Route::middleware(['auth', 'reader'])->group(function () {
    Route::get('/reader/dashboard', function () {
        $articles = \App\Models\Article::where('status', 'active')->with('user')->latest()->get();
        return view('reader.dashboard', compact('articles'));
    })->name('reader.dashboard');
});

// Complated ✔

// =========================================================================

// Article Management

use App\Http\Controllers\ArticleController;

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/view', [ArticleController::class, 'index'])->name('articles.index');
    Route::put('/approve/{id}', [ArticleController::class, 'approve'])->name('articles.approve');
    Route::put('/reject/{id}', [ArticleController::class, 'reject'])->name('articles.reject');
});

// Shared Article View
Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show')->middleware('auth');

Route::middleware(['auth', 'writer'])->post('/store', [ArticleController::class, 'store'])->name('articles.store');
Route::middleware(['auth', 'writer'])->get('/my-articles', [ArticleController::class, 'myArticles'])->name('articles.myArticles');

// =========================================================================
// Report Management
use App\Http\Controllers\ReportController;


Route::middleware(['auth', 'admin'])->group(function () {

    // عرض جميع البلاغات
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Resolution Center (Single Page Chat with Writers)
    Route::get('/reports/resolution', [ReportController::class, 'resolution'])->name('reports.resolution');

    // تحديث حالة بلاغ
    Route::put('/reports/{id}', [ReportController::class, 'update'])->name('reports.update');
});

// إرسال بلاغ
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store')->middleware('auth');


// =========================================================================
// Sent Notifications Management
use App\Http\Controllers\Admin\SentNotificationController;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/notification', [SentNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/create', [SentNotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notification/store', [SentNotificationController::class, 'store'])->name('notifications.store');
});


// =========================================================================
// Ads Management

Route::get('/post/{id}', [ArticleController::class, 'openPostLink']);
