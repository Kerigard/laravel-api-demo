<?php

use App\Http\Controllers\V1\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->middleware(['throttle:api-v1-auth'])->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('me', [AuthController::class, 'me'])->middleware(['auth']);
});
