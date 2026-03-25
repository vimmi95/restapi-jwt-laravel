<?php

use App\Http\Controllers\Api\Posts\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::post('/posts/create', [PostController::class, 'store']);
    Route::post('/posts/{post}', [PostController::class, 'update'])->missing(fn () => response()->json([
        'status' => 'Failed',
        'message' => 'Post not found',
    ], 404));
});