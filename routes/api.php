<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\U_userController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [U_userController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/google', [AuthController::class, 'loginWithGoogle']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [U_userController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
