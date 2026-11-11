<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PeminjamanApiController;
use App\Http\Controllers\Api\PeminjamanStatusController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Peminjaman API Routes
Route::prefix('peminjaman')->group(function () {
    // Get available equipment
    Route::get('/alats', [PeminjamanApiController::class, 'getAlats']);

    // Create loan request
    Route::post('/', [PeminjamanApiController::class, 'createPeminjaman']);

    // Get loan status
    Route::get('/{id}/status', [PeminjamanApiController::class, 'getPeminjamanStatus']);

    // Admin routes (protected)
    Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
        Route::get('/admin/peminjamans', [PeminjamanApiController::class, 'getAllPeminjamans']);
        Route::patch('/admin/peminjamans/{id}/verifikasi', [PeminjamanApiController::class, 'verifyPeminjaman']);
    });
});

// User Peminjaman Status API Route
Route::middleware('auth:sanctum')->get('/user/peminjaman-status', [PeminjamanStatusController::class, 'getUserPeminjamanStatus']);

// Lightweight user data endpoint for integration (secured by INTEGRATION_TOKEN)
Route::get('/integration/users/{user}', function (\App\Models\User $user, Illuminate\Http\Request $request) {
    $expected = 'Bearer ' . env('INTEGRATION_TOKEN');
    if (trim($request->header('Authorization')) !== trim($expected)) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }
    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'phone' => $user->phone ?? null,
    ]);
});

// Webhook endpoint for peminjaman status updates
Route::post('/webhooks/peminjaman-status', [App\Http\Controllers\WebhookController::class, 'handlePeminjamanStatus']);

// Endpoint untuk mengambil data user (untuk integrasi dengan sistem peminjaman)
Route::middleware('auth:sanctum')->get('/users/{user}', [App\Http\Controllers\UserController::class, 'show']);

// Optional endpoint to save peminjaman data from external system
Route::post('/peminjaman/save', [App\Http\Controllers\Api\PeminjamanApiController::class, 'savePeminjamanData']);
