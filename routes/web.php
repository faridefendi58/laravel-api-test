<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

// posts are private for store, update and destroy
Route::middleware('auth')
    ->resource('posts', PostController::class)
    ->only(['store', 'update', 'destroy']);

// index and show are public
Route::resource('posts', PostController::class)
    ->only(['index', 'show']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
