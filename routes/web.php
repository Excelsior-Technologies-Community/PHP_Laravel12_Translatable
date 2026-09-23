<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| Post Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [PostController::class, 'index'])
    ->name('posts.index');

Route::get('/create', [PostController::class, 'create'])
    ->name('posts.create');

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

Route::get(
    '/translations/{post}/edit',
    [PostController::class, 'editTranslation']
)->name('translations.edit');

Route::put(
    '/translations/{post}',
    [PostController::class, 'updateTranslation']
)->name('translations.update');


/*
|--------------------------------------------------------------------------
| Delete Post
|--------------------------------------------------------------------------
*/

Route::delete(
    '/posts/{post}',
    [PostController::class, 'destroy']
)->name('posts.destroy');


/*
|--------------------------------------------------------------------------
| Bulk Delete
|--------------------------------------------------------------------------
*/

Route::post(
    '/posts/bulk-delete',
    [PostController::class, 'bulkDelete']
)->name('posts.bulk-delete');


/*
|--------------------------------------------------------------------------
| Duplicate Post
|--------------------------------------------------------------------------
*/

Route::post(
    '/posts/{post}/duplicate',
    [PostController::class, 'duplicate']
)->name('posts.duplicate');


/*
|--------------------------------------------------------------------------
| CSV Export
|--------------------------------------------------------------------------
*/

Route::get(
    '/posts/export/csv',
    [PostController::class, 'exportCsv']
)->name('posts.export.csv');