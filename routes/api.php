<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Models\Article;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);


// =====================================================================================

// User Management - Admin Only

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::put('/userStatus/{user}', [UserController::class, 'updateStatus']);
    Route::put('/usersRole/{id}', [UserController::class, 'updateRole']);
});

// =========================================================================

// Article Management

use App\Http\Controllers\ArticleController;

Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    Route::get('/view', [ArticleController::class, 'index']);
    Route::post('/approve/{id}', [ArticleController::class, 'approve']);
    Route::post('/reject/{id}', [ArticleController::class, 'reject']);
    
});

Route::middleware(['auth:sanctum', 'writer'])->post('/store', [ArticleController::class, 'store']);
Route::middleware(['auth:sanctum', 'writer'])->get('/my-articles', [ArticleController::class, 'myArticles']);


// =========================================================================
// Report Management
use App\Http\Controllers\ReportController;


Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    // عرض البلاغات
    Route::get('/reports', [ReportController::class, 'index']);


    // تحديث حالة بلاغ
    Route::put('/reports/{id}', [ReportController::class, 'update']);
});

// إنشاء بلاغ
Route::middleware('auth:sanctum')->post('/articles/{article}/report', [ReportController::class, 'store']);


// =========================================================================
// Sent Notifications Management
use App\Http\Controllers\Admin\SentNotificationController;

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/view/notifications', [SentNotificationController::class, 'index']);
    Route::post('/store/notifications', [SentNotificationController::class, 'store']);
});

// =========================================================================
// Ads Management

Route::post('/articles/create-link', [ArticleController::class, 'CreateLink'])
    ->middleware('auth:sanctum');

