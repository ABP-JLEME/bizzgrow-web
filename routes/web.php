<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseAuthController;
use App\Http\Controllers\DashboardController; // Pastikan ini diimport
use App\Http\Controllers\ProfileController;

// --- Public Routes (Auth Forms, Onboarding, Welcome) ---
Route::get('/', function () {
    return view('onboarding');
})->name('onboarding');

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Routes untuk menampilkan form login/register
Route::get('/login', [FirebaseAuthController::class, 'showLoginForm'])->name('login');
Route::get('/register', [FirebaseAuthController::class, 'showRegisterForm'])->name('register');

// Endpoint API untuk menerima ID Token dari Frontend Firebase SDK
Route::post('/auth/verify-firebase-token', [FirebaseAuthController::class, 'verifyFirebaseToken'])->name('verify.firebase.token');

// --- Protected Routes (Perlu Login) ---
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile');

    Route::get('/penjualan', function () {
        return view('penjualan');
    })->name('penjualan');

    Route::post('/logout', [FirebaseAuthController::class, 'logout'])->name('logout');
});
