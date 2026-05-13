<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ProfileController;

// Public Routes
Route::get('/', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{movieId}', [MovieController::class, 'show'])->name('movies.show');
Route::get('/api/search', [MovieController::class, 'search'])->name('movies.search');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

// Protected Routes (Auth Required)
Route::middleware('auth')->group(function () {
    // Watchlist Routes
    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/watchlist', [WatchlistController::class, 'store'])->name('watchlist.store');
    Route::get('/watchlist/status/{status}', [WatchlistController::class, 'byStatus'])->name('watchlist.status');
    Route::put('/watchlist/{movieId}', [WatchlistController::class, 'update'])->name('watchlist.update');
    Route::delete('/watchlist/{movieId}', [WatchlistController::class, 'destroy'])->name('watchlist.destroy');

    // Rating Routes
    Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');
    Route::delete('/ratings/{movieId}', [RatingController::class, 'destroy'])->name('ratings.destroy');
    Route::get('/my-ratings', [RatingController::class, 'myRatings'])->name('ratings.index');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
