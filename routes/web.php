<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

// Display all posts based on the selected language
Route::get('/', [PostController::class, 'index']);

// Show the form to create a new post
Route::get('/create', [PostController::class, 'create']);

// Store a new post with translations
Route::post('/store', [PostController::class, 'store']);

// Change the application language (English / Hindi)
Route::get('/lang/{locale}', [PostController::class, 'changeLang']);
