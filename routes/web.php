<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin'])->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.attempt');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:6,1')->name('register.store');
    Route::view('/lupa-password', 'auth.forgot-password')->name('password.request');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function (): void {
    Route::view('/pelanggan', 'dashboard', ['role' => 'Pelanggan'])->middleware('level:1')->name('pelanggan');
    Route::view('/mekanik', 'dashboard', ['role' => 'Mekanik'])->middleware('level:2')->name('mekanik');
    Route::view('/admin', 'dashboard', ['role' => 'Admin'])->middleware('level:3')->name('admin');
    Route::view('/pemilik', 'dashboard', ['role' => 'Pemilik'])->middleware('level:4')->name('pemilik');
});
