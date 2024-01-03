<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\PostImageController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}/posts', [UserController::class, 'post']);
    Route::post('/users/{user}/toggle_followings', [UserController::class, 'toggleFollowing']);
    Route::get('/users/followed_posts', [UserController::class, 'followingPost']);

    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::post('/post_images', [PostImageController::class, 'store']);
    Route::post('/posts/{post}/toggle_likes', [PostController::class, 'toggleLike']);
    Route::post('/posts/{post}/repost', [PostController::class, 'repost']);
    Route::post('/posts/{post}/comments', [PostController::class, 'comment']);
    Route::get('/posts/{post}/comments', [PostController::class, 'commentList']);

});
