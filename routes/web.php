<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ResetPasswordController;

// ========================
//  AUTH
// ========================

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/reset-password', [ResetPasswordController::class, 'showForm'])->name('reset.password');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('reset.password.post');

// ========================
//  REGISTER
// ========================

Route::get('/register', [RegisterController::class, 'showRegisAdmin'])->name('register');
Route::post('/register', [RegisterController::class, 'regisAdmin'])->name('register.post');

Route::get('/register/mahasiswa', [RegisterController::class, 'showRegisMhs'])->name('register.mhs');
Route::post('/register/mahasiswa', [RegisterController::class, 'regisMhs'])->name('register.mhs.post');

// ========================
//  ADMIN (role: super_admin, admin, teknisi)
// ========================

Route::prefix('admin')->middleware(['auth', 'role:super_admin,admin,teknisi'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/mahasiswa', [AdminController::class, 'daftarMahasiswa'])->name('admin.mahasiswa');
    Route::delete('/mahasiswa/{id}', [AdminController::class, 'hapusMahasiswa'])->name('admin.mahasiswa.hapus');
});

// ========================
//  MAHASISWA (role: mahasiswa, dosen)
// ========================

Route::prefix('mahasiswa')->middleware(['auth', 'role:mahasiswa,dosen'])->group(function () {
    Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('mahasiswa.dashboard');
    Route::get('/biodata/{id}', [MahasiswaController::class, 'biodata'])->name('mahasiswa.biodata');
    Route::post('/biodata/{id}', [MahasiswaController::class, 'updateBiodata'])->name('mahasiswa.biodata.update');
    Route::get('/laporan/status', [LaporanController::class, 'status'])->name('laporan.status');
    Route::get('/ganti-password', [MahasiswaController::class, 'showGantiPassword'])->name('mahasiswa.ganti.password');
    Route::post('/ganti-password', [MahasiswaController::class, 'gantiPassword'])->name('mahasiswa.ganti.password.post');
});
