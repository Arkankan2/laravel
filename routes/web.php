<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\TeknisiController;

// SuperAdmin Controllers
use App\Http\Controllers\SuperAdmin\DashboardController  as SADashboard;
use App\Http\Controllers\SuperAdmin\AkunController       as SAAkun;
use App\Http\Controllers\SuperAdmin\GedungController     as SAGedung;
use App\Http\Controllers\SuperAdmin\LantaiController     as SALantai;
use App\Http\Controllers\SuperAdmin\RuanganController    as SARuangan;
use App\Http\Controllers\SuperAdmin\KategoriController   as SAKategori;
use App\Http\Controllers\SuperAdmin\AuditLogController   as SAAuditLog;
use App\Http\Controllers\SuperAdmin\BackupController     as SABackup;
use App\Http\Controllers\SuperAdmin\ProfilController     as SAProfil;

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
    Route::get('/riwayat-laporan', [AdminController::class, 'riwayat'])->name('admin.riwayat');
});

// ========================
//  TEKNISI (role: teknisi)
// ========================

Route::prefix('teknisi')->middleware(['auth', 'role:teknisi'])->group(function () {
    Route::get('/dashboard',               [TeknisiController::class, 'dashboard'])->name('teknisi.dashboard');
    Route::get('/tugas',                   [TeknisiController::class, 'tugasSaya'])->name('teknisi.tugas');
    Route::get('/tugas/{id}',              [TeknisiController::class, 'detailTugas'])->name('teknisi.detail');
    Route::patch('/tugas/{id}/mulai',      [TeknisiController::class, 'mulaiPerbaikan'])->name('teknisi.mulai');
    Route::get('/tugas/{id}/selesai',      [TeknisiController::class, 'formSelesai'])->name('teknisi.form-selesai');
    Route::post('/tugas/{id}/selesai',     [TeknisiController::class, 'selesaikanTugas'])->name('teknisi.selesai.post');
});

// ========================
//  MAHASISWA (role: mahasiswa, dosen)
// ========================

Route::prefix('mahasiswa')->middleware(['auth', 'role:mahasiswa,dosen'])->group(function () {
    Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('mahasiswa.dashboard');
    Route::get('/biodata/{id}', [MahasiswaController::class, 'biodata'])->name('mahasiswa.biodata');
    Route::post('/biodata/{id}', [MahasiswaController::class, 'updateBiodata'])->name('mahasiswa.biodata.update');
    Route::get('/laporan/status', [LaporanController::class, 'status'])->name('laporan.status');
    Route::get('/laporan/pantau', [LaporanController::class, 'pantau'])->name('laporan.pantau');
    Route::get('/laporan/buat', [LaporanController::class, 'create'])->name('laporan.create');
    Route::post('/laporan/buat', [LaporanController::class, 'store'])->name('laporan.store');
    Route::get('/ganti-password', [MahasiswaController::class, 'showGantiPassword'])->name('mahasiswa.ganti.password');
    Route::post('/ganti-password', [MahasiswaController::class, 'gantiPassword'])->name('mahasiswa.ganti.password.post');
});

// ========================
//  SUPER ADMIN (role: super_admin)
// ========================

Route::prefix('super-admin')->middleware(['auth', 'super_admin'])->name('superadmin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [SADashboard::class, 'index'])->name('dashboard');

    // Manajemen Akun
    Route::prefix('akun')->name('akun.')->group(function () {
        Route::get('/',                    [SAAkun::class, 'index'])->name('index');
        Route::get('/tambah',              [SAAkun::class, 'create'])->name('create');
        Route::post('/',                   [SAAkun::class, 'store'])->name('store');
        Route::get('/{id}/edit',           [SAAkun::class, 'edit'])->name('edit');
        Route::put('/{id}',               [SAAkun::class, 'update'])->name('update');
        Route::delete('/{id}',            [SAAkun::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle-status',[SAAkun::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{id}/reset-password',[SAAkun::class, 'resetPassword'])->name('reset-password');
    });

    // Konfigurasi Wilayah - Gedung
    Route::prefix('gedung')->name('gedung.')->group(function () {
        Route::get('/',          [SAGedung::class, 'index'])->name('index');
        Route::get('/tambah',    [SAGedung::class, 'create'])->name('create');
        Route::post('/',         [SAGedung::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SAGedung::class, 'edit'])->name('edit');
        Route::put('/{id}',      [SAGedung::class, 'update'])->name('update');
        Route::delete('/{id}',   [SAGedung::class, 'destroy'])->name('destroy');
    });

    // Konfigurasi Wilayah - Lantai
    Route::prefix('lantai')->name('lantai.')->group(function () {
        Route::get('/',          [SALantai::class, 'index'])->name('index');
        Route::get('/tambah',    [SALantai::class, 'create'])->name('create');
        Route::post('/',         [SALantai::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SALantai::class, 'edit'])->name('edit');
        Route::put('/{id}',      [SALantai::class, 'update'])->name('update');
        Route::delete('/{id}',   [SALantai::class, 'destroy'])->name('destroy');
    });

    // Konfigurasi Wilayah - Ruangan
    Route::prefix('ruangan')->name('ruangan.')->group(function () {
        Route::get('/',          [SARuangan::class, 'index'])->name('index');
        Route::get('/tambah',    [SARuangan::class, 'create'])->name('create');
        Route::post('/',         [SARuangan::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SARuangan::class, 'edit'])->name('edit');
        Route::put('/{id}',      [SARuangan::class, 'update'])->name('update');
        Route::delete('/{id}',   [SARuangan::class, 'destroy'])->name('destroy');
    });

    // Master Kategori Fasilitas
    Route::prefix('kategori')->name('kategori.')->group(function () {
        Route::get('/',          [SAKategori::class, 'index'])->name('index');
        Route::get('/tambah',    [SAKategori::class, 'create'])->name('create');
        Route::post('/',         [SAKategori::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SAKategori::class, 'edit'])->name('edit');
        Route::put('/{id}',      [SAKategori::class, 'update'])->name('update');
        Route::delete('/{id}',   [SAKategori::class, 'destroy'])->name('destroy');
    });

    // Log Sistem & Audit
    Route::prefix('audit-log')->name('audit-log.')->group(function () {
        Route::get('/',        [SAAuditLog::class, 'index'])->name('index');
        Route::get('/export',  [SAAuditLog::class, 'export'])->name('export');
    });

    // Backup Sistem
    Route::prefix('backup')->name('backup.')->group(function () {
        Route::get('/',              [SABackup::class, 'index'])->name('index');
        Route::post('/',             [SABackup::class, 'store'])->name('store');
        Route::get('/{id}/download', [SABackup::class, 'download'])->name('download');
        Route::delete('/{id}',       [SABackup::class, 'destroy'])->name('destroy');
    });

    // Profil Super Admin
    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/',            [SAProfil::class, 'edit'])->name('edit');
        Route::post('/update',     [SAProfil::class, 'update'])->name('update');
        Route::post('/foto',       [SAProfil::class, 'uploadFoto'])->name('foto');
        Route::post('/password',   [SAProfil::class, 'gantiPassword'])->name('password');
    });
});
