<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GunungController;
use App\Http\Controllers\Admin\GunungController as AdminGunungController;
use App\Http\Controllers\User\RutePendakianController as UserRutePendakianController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\PeminjamanController as AdminPeminjamanController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PembayaranController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('landing');
})->name('landing');

// Public static pages
Route::get('/panduan', function () {
    return view('panduan.index');
})->name('panduan');

Route::get('/tentang', function () {
    return view('tentang.index');
})->name('tentang');

// Public search for gunung
Route::get('/gunung/search', [GunungController::class, 'search'])->name('gunung.search');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Google OAuth
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gunung Routes (user-facing: hanya lihat)
    Route::resource('gunung', GunungController::class)->only(['index','show']);

    // Rute Pendakian Routes
    Route::get('/rute-by-gunung/{gunung_id}', [UserRutePendakianController::class, 'showRuteByGunung'])->name('rute.by-gunung');
    Route::get('/api/rute-by-gunung/{gunung_id}', [UserRutePendakianController::class, 'getByGunung'])->name('rute.by-gunung.json');
    Route::resource('rute_pendakian', UserRutePendakianController::class);

    // Pendaftaran Routes
    Route::resource('pendaftaran', PendaftaranController::class);
    Route::get('/pendaftaran/create/{gunung_id}/{rute_id}', [PendaftaranController::class, 'createWithParams'])->name('pendaftaran.create.with.params');

    // Pembayaran Routes
    Route::get('/bayar/{id}', [PendaftaranController::class, 'bayar'])->name('pendaftaran.bayar');
    Route::post('/midtrans/callback', [PendaftaranController::class, 'callback'])->name('midtrans.callback');

    // PDF Routes
    Route::get('/pendaftaran/{id}/cetak', [PendaftaranController::class, 'cetak'])->name('pendaftaran.cetak');
});

// Admin Routes
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Admin Gunung Management (CRUD penuh)
    Route::resource('gunung', AdminGunungController::class);

    // Admin Rute Pendakian Management
    Route::resource('rute-pendakian', \App\Http\Controllers\Admin\RutePendakianController::class);

    // Admin Pendaftaran Management
    Route::get('/pendaftaran', [AdminController::class, 'pendaftaran'])->name('pendaftaran.index');
    Route::get('/pendaftaran/{id}', [AdminController::class, 'showPendaftaran'])->name('pendaftaran.show');
    Route::patch('/pendaftaran/{pendaftaran}/update-status', [AdminController::class, 'updateStatus'])->name('pendaftaran.update-status');
    Route::get('/pendaftaran/{id}/edit', [PendaftaranController::class, 'edit'])->name('pendaftaran.edit');
    Route::put('/pendaftaran/{id}', [PendaftaranController::class, 'update'])->name('pendaftaran.update');

    // Admin Pembayaran Management
    Route::get('/pembayaran', [AdminController::class, 'pembayaranIndex'])->name('pembayaran.index');

    // Admin Peminjaman Management
    Route::get('/peminjaman', [AdminPeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/{id}', [AdminPeminjamanController::class, 'show'])->name('peminjaman.show');

    // Admin User Management
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
});

// Webhook Routes (no auth required for external systems)
Route::post('/webhooks/peminjaman-status', [WebhookController::class, 'handlePeminjamanStatus'])->name('webhook.peminjaman-status');
