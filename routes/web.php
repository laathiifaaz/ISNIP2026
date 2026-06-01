<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

// ==========================================
// PUBLIC & GUEST-ACCESSIBLE ROUTES (Bisa Diakses Semua Orang)
// ==========================================

// Rute Beranda & Pencarian Terbuka (Public Mode)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Rute Dashboard & Detail Job Terbuka (Guest & Auth bisa masuk)
Route::get('/jobs', [JobController::class, 'index'])->name('jobs');
Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs.show');

// Hubungi Kami (Bisa diakses publik)
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');

// Rute Otentikasi Khusus Pengguna yang BELUM LOGIN
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});


// ==========================================
// PROTECTED ROUTES (Wajib Login / Anggota Saja)
// ==========================================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Aksi Manipulasi Data Job yang Memerlukan Identitas User Asli
    Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
    Route::post('/jobs/{id}/cancel', [JobController::class, 'cancel'])->name('jobs.cancel');
    Route::get('/jobs/{id}/download', [JobController::class, 'downloadPdf'])->name('jobs.download');
});