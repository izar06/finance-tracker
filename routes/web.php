<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Redirect root
Route::get('/', fn() => auth()->check() ? redirect()->route('dashboard') : redirect()->route('login'));

// Auth routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', \App\Livewire\Dashboard::class)->name('dashboard');
    Route::get('/transaksi', \App\Livewire\Transactions::class)->name('transactions');
    Route::get('/tujuan', \App\Livewire\Goals::class)->name('goals');
    Route::get('/aset', \App\Livewire\Assets::class)->name('assets');
    Route::get('/laporan', \App\Livewire\Reports::class)->name('reports');
    Route::get('/anggaran', \App\Livewire\Budgets::class)->name('budgets');
    Route::get('/kategori', \App\Livewire\Categories::class)->name('categories');
    Route::get('/tagihan', \App\Livewire\Bills::class)->name('bills');

    // Superadmin only
    Route::middleware(\App\Http\Middleware\SuperAdminMiddleware::class)->group(function () {
        Route::get('/superadmin', \App\Livewire\SuperAdmin::class)->name('superadmin');
    });
});
