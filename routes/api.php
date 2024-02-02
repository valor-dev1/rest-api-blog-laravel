<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;


require __DIR__.'/auth.php';

Route::middleware(['auth:sanctum'])->get('/profile', [UserController::class, 'profile']);
Route::middleware(['auth:sanctum'])->get('/users/{user}/posts', [UserController::class, 'posts']);
Route::middleware('auth:sanctum')->apiResource('users', UserController::class);
Route::apiResource('posts.comments', CommentController::class)->only(['index', 'show']);
Route::middleware(['auth:sanctum'])->apiResource('posts.comments', CommentController::class)->except(['index', 'show']);
Route::apiResource('posts', PostController::class)->only(['index', 'show']);
Route::middleware('auth:sanctum')->apiResource('posts', PostController::class)->except(['index', 'show']);