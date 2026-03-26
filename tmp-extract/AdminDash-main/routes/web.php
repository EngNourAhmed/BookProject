<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [UserController::class, 'register'])->name('register.post');
Route::get('/login', function () {
    return view('login');
})->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.post');

Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// =====================================================================================

// User Management - Admin Only 

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::put('/users/{user}/status', [UserController::class, 'updateStatus'])->name('admin.users.updateStatus');
    Route::put('/users/{id}/role', [UserController::class, 'updateRole'])->name('admin.users.updateRole');
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

Route::middleware(['auth', 'writer'])->post('/store', [ArticleController::class, 'store'])->name('articles.store');
Route::middleware(['auth', 'writer'])->get('/my-articles', [ArticleController::class, 'myArticles'])->name('articles.myArticles');

// =========================================================================
// Report Management
use App\Http\Controllers\ReportController;


Route::middleware(['auth', 'admin'])->group(function () {

    // عرض جميع البلاغات
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('reports.show');


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
