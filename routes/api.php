<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', 'logout');
        Route::get('profile', 'myProfile');
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('posts', PostController::class)->except('show');
    Route::controller(PostController::class)->group(function () {
        Route::get('/my-posts', 'myPosts');
        Route::post('/posts/{post}/comments', 'storeComment');
        Route::post('/posts/{post}/reaction', 'storeReaction');
    });
});
