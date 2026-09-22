<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| Post Routes
|--------------------------------------------------------------------------
*/

// Display posts with multilingual search/filter
Route::get('/', [PostController::class, 'index'])
    ->name('posts.index');

// Show create form
Route::get('/create', [PostController::class, 'create'])
    ->name('posts.create');

// Store post
Route::post('/store', [PostController::class, 'store'])
    ->name('posts.store');

/*
|--------------------------------------------------------------------------
| Language Switching
|--------------------------------------------------------------------------
*/

Route::get('/lang/{locale}', [PostController::class, 'changeLang'])
    ->name('language.change');

/*
|--------------------------------------------------------------------------
| Translation Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [PostController::class, 'dashboard'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Translation Completeness Manager
|--------------------------------------------------------------------------
*/

Route::get('/translations', [PostController::class, 'translations'])
    ->name('translations');

Route::get('/translations/{post}/edit', [PostController::class, 'editTranslation'])
    ->name('translations.edit');

Route::put('/translations/{post}', [PostController::class, 'updateTranslation'])
    ->name('translations.update');