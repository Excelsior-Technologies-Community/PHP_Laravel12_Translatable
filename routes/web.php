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

/*
|--------------------------------------------------------------------------
| Auto-Translation Studio
|--------------------------------------------------------------------------
*/

Route::get('/translator', [PostController::class, 'translator'])->name('translator.index');
Route::post('/translator/auto-translate', [PostController::class, 'autoTranslate'])->name('translator.auto');

/*
|--------------------------------------------------------------------------
| Translation Completeness Analytics
|--------------------------------------------------------------------------
*/

Route::get('/analytics', [PostController::class, 'analytics'])->name('analytics.index');
Route::get('/analytics/data', [PostController::class, 'analyticsData'])->name('analytics.data');

/*
|--------------------------------------------------------------------------
| Bulk JSON / CSV Import & Export Studio
|--------------------------------------------------------------------------
*/

Route::get('/bulk-manage', [PostController::class, 'bulkManage'])->name('bulk.manage');
Route::post('/bulk-import/json', [PostController::class, 'importJson'])->name('bulk.import.json');
Route::post('/bulk-import/csv', [PostController::class, 'importCsv'])->name('bulk.import.csv');
Route::get('/bulk-export/json', [PostController::class, 'exportJson'])->name('bulk.export.json');