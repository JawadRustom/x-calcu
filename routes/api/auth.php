
<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::middleware('logged.in.check')->group(function () {
        Route::post('/login', [\App\Http\Controllers\AuthenticationController::class, 'login']);
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [\App\Http\Controllers\AuthenticationController::class, 'user']);
        Route::post('/logout', [\App\Http\Controllers\AuthenticationController::class, 'logout']);
    });
});
