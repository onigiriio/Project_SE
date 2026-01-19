<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\LibrarianController;

Route::get('/', function () {
    return view('library-welcome');
})->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/my-borrows', [DashboardController::class, 'borrows'])->name('borrows');
    
    // Membership routes
    Route::post('/membership/upgrade', [MembershipController::class, 'upgrade'])->name('membership.upgrade');

    Route::get('/catalogue', [BookController::class, 'catalogue'])->name('books.catalogue');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::get('/genres/{genre}', [BookController::class, 'byGenre'])->name('books.by-genre');
    Route::post('/books/{book}/reviews', [BookController::class, 'storeReview'])->name('books.store-review');
    Route::post('/books/{book}/borrow', [BookController::class, 'borrow'])->name('books.borrow');

    if (!Route::has('books.index')) {
        Route::get('/books', [BookController::class, 'index'])->name('books.index');
    }

    // Librarian-only book management routes
    Route::middleware('can:manage-books')->group(function () {
        Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
        Route::post('/books', [BookController::class, 'store'])->name('books.store');
        Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
        Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    });

    // Librarian-only management routes
    Route::middleware('can:manage-users')->prefix('librarian')->group(function () {
        Route::get('/dashboard', [LibrarianController::class, 'dashboard'])->name('librarian.dashboard');
        Route::get('/users', [LibrarianController::class, 'users'])->name('librarian.users');
        Route::get('/users/{user}/details', [LibrarianController::class, 'userDetails'])->name('librarian.user-details');
    });
});

