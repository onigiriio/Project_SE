<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookController;

Route::get('/', function () {
    return view('library-welcome');
})->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/catalogue', [BookController::class, 'catalogue'])->name('books.catalogue');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::get('/genres/{genre}', [BookController::class, 'byGenre'])->name('books.by-genre');
    Route::post('/books/{book}/reviews', [BookController::class, 'storeReview'])->name('books.store-review');

    Route::get('/books', function () {
        return view('books');
    })->name('books.index');

    Route::get('/books/create', function () {
        // Placeholder for book creation form
        return view('books.create');
    })->name('books.create');
});
