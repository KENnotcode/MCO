<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ViolationController;
use App\Http\Controllers\CitationController;

Route::get('/', function () {
    return view('home');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/admin', function () {
    return view('admin');
});

// Auth routes
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// Database  users routes
Route::get('/createUsers', [UserController::class, 'index'])->name('users.index');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

Route::resource('violations', ViolationController::class);

Route::post('/violation-categories', [ViolationController::class, 'storeCategory'])->name('violations.storeCategory');
Route::delete('/violation-categories/{category}', [ViolationController::class, 'destroyCategory'])->name('violations.destroyCategory');
Route::put('/violation-categories/{category}', [ViolationController::class, 'updateCategory'])->name('violations.updateCategory');

// Citation routes
Route::get('/citations', [CitationController::class, 'index'])->name('citations.index');
Route::post('/citations', [CitationController::class, 'store'])->name('citations.store');
Route::get('/users/{user}/citations', [CitationController::class, 'showUserCitations'])->name('citations.showUserCitations');
Route::delete('/citations/{citation}', [CitationController::class, 'destroy'])->name('citations.destroy');

// API routes
Route::get('/api/violations-by-category', [ViolationController::class, 'getViolationsByCategory'])->name('api.violations.by.category');
Route::get('/api/offenses-for-violation', [ViolationController::class, 'getOffensesForViolation'])->name('api.offenses.for.violation');


