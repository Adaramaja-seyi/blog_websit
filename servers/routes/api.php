<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\likecontroller;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// routes/api.php
Route::post('/create_users', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user', [AuthController::class, 'update']);
    Route::get('/user_data', [AuthController::class, 'getUserData']);
    Route::put('/update_profile', [AuthController::class, 'updateProfile']);

    // Dashboard routes
    Route::get('/dashboard/stats', [PostController::class, 'getDashboardStats']);
    Route::get('/posts/recent', [PostController::class, 'getRecentPosts']);
    Route::get('/comments/recent', [CommentController::class, 'getRecentComments']);
    
    // Existing routes
    Route::apiResource('posts', PostController::class);
    Route::post('posts/{post}/comments', [CommentController::class, 'store']);
    Route::post('posts/{post}/like', [LikeController::class, 'toggle']);
    Route::post('posts/{post}/comments/{comment}/like', [CommentController::class, 'toggle']);
});
