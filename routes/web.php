<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Forum\CategoryController;
use App\Http\Controllers\Forum\CommunityController;
use App\Http\Controllers\Forum\GameController;
use App\Http\Controllers\Forum\HomeController;
use App\Http\Controllers\Forum\ThreadController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('forum.index');
Route::get('/games/{game:slug}', [GameController::class, 'show'])->name('games.show');
Route::get('/communities/{community:slug}', [CommunityController::class, 'show'])->name('communities.show');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/threads/create', [ThreadController::class, 'create'])
    ->middleware('auth')
    ->name('threads.create');
Route::post('/threads', [ThreadController::class, 'store'])
    ->middleware('auth')
    ->name('threads.store');
Route::get('/threads/{thread}', [ThreadController::class, 'show'])->name('threads.show');
Route::post('/threads/{thread}/posts', [ThreadController::class, 'storePost'])
    ->middleware('auth')
    ->name('threads.posts.store');
Route::get('/threads/{thread}/edit', [ThreadController::class, 'edit'])
    ->middleware('auth')
    ->name('threads.edit');
Route::put('/threads/{thread}', [ThreadController::class, 'update'])
    ->middleware('auth')
    ->name('threads.update');
Route::delete('/threads/{thread}', [ThreadController::class, 'destroy'])
    ->middleware('auth')
    ->name('threads.destroy');

Route::get('/posts/{post}/edit', [ThreadController::class, 'editPost'])
    ->middleware('auth')
    ->name('posts.edit');
Route::put('/posts/{post}', [ThreadController::class, 'updatePost'])
    ->middleware('auth')
    ->name('posts.update');
Route::delete('/posts/{post}', [ThreadController::class, 'destroyPost'])
    ->middleware('auth')
    ->name('posts.destroy');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
