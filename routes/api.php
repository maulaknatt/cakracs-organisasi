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
