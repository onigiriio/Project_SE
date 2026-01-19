<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\RegistrationFeeController;

Route::get('/', function () {
    return view('library-welcome');
})->name('home');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// Registration Fee Payment Routes
Route::middleware('auth')->group(function () {
    Route::get('/registration/pay-fee', [RegistrationFeeController::class, 'show'])->name('registration.pay-fee');
    Route::post('/registration/pay-fee', [RegistrationFeeController::class, 'pay'])->name('registration.pay-fee.submit');
    Route::post('/registration/cancel', [RegistrationFeeController::class, 'cancel'])->name('registration.cancel');
});

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Book Routes - Public for authenticated users
    Route::get('/catalogue', [BookController::class, 'catalogue'])->name('books.catalogue');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::get('/genres/{genre}', [BookController::class, 'byGenre'])->name('books.by-genre');
    Route::post('/books/{book}/reviews', [BookController::class, 'storeReview'])->name('books.store-review');
    Route::post('/books/{book}/borrow', [BookController::class, 'borrow'])->name('books.borrow');
    Route::post('/borrows/{borrow}/return', [BookController::class, 'returnBook'])->name('books.return');

    if (!Route::has('books.index')) {
        Route::get('/books', [BookController::class, 'index'])->name('books.index');
    }

    // Fine Routes - User can view/pay their own fines
    Route::get('/fines', [FineController::class, 'index'])->name('fines.index');
    Route::get('/fines/{fine}', [FineController::class, 'show'])->name('fines.show');
    Route::post('/fines/{fine}/pay', [FineController::class, 'payFine'])->name('fines.pay');

    // Librarian-only routes
    Route::middleware('can:manage-books')->group(function () {
        // Book Management
        Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
        Route::post('/books', [BookController::class, 'store'])->name('books.store');
        Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
        Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    });

    // Librarian-only user management
    Route::middleware('can:manage-users')->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });

    // Librarian-only fine management
    Route::middleware('can:view-all-fines')->group(function () {
        Route::get('/fines/all', [FineController::class, 'all'])->name('fines.all');
        Route::get('/fines/create', [FineController::class, 'create'])->name('fines.create');
        Route::post('/fines', [FineController::class, 'store'])->name('fines.store');
        Route::get('/fines/{fine}/edit', [FineController::class, 'edit'])->name('fines.edit');
        Route::put('/fines/{fine}', [FineController::class, 'update'])->name('fines.update');
        Route::delete('/fines/{fine}', [FineController::class, 'destroy'])->name('fines.destroy');
    });
});

