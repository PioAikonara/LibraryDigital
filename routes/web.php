<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Categories management
    Route::resource('categories', CategoryController::class);

    // Books management
    Route::resource('books', BookController::class);

    // Borrows management
    Route::group(['prefix' => 'borrows', 'as' => 'borrows.'], function () {
        Route::get('/', [BorrowController::class, 'index'])->name('index');
        Route::post('{borrow}/approve', [BorrowController::class, 'approve'])->name('approve');
        Route::post('{borrow}/return', [BorrowController::class, 'return'])->name('return');
    });
});

// User routes
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');

    // User book routes
    Route::get('/books', [BookController::class, 'userIndex'])->name('user.books.index');

    // User borrow routes
    Route::post('/borrows', [BorrowController::class, 'store'])->name('user.borrows.store');
    Route::get('/my-borrows', [BorrowController::class, 'userBorrows'])->name('user.borrows');
    Route::post('/borrows/{borrow}/return', [BorrowController::class, 'return'])->name('user.borrows.return');
});

// Common authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Public routes
Route::get('/', function () {
    return view('welcome');
});
