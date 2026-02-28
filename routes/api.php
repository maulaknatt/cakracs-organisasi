<?php

use App\Http\Controllers\Api\AIChatController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/chat/history', [AIChatController::class, 'history']);
    Route::post('/chat', [AIChatController::class, 'chat']);
});

// Route Migrasi Aman (Bypass Session Middleware)
Route::get('/migrate-database-sekarang', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh --seed --force');
        return "Berhasil Mas! Database sudah terisi semua tabel. Output: " . \Illuminate\Support\Facades\Artisan::output();
    } catch (\Exception $e) {
        return "Error Migrasi: " . $e->getMessage();
    }
});
