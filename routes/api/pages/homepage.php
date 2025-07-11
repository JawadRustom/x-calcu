<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/home-page/operations', [\App\Http\Controllers\HomepageController::class, 'getOperations']);
    Route::get('/home-page/operations/search', [\App\Http\Controllers\HomepageController::class, 'searchOperations']);
});
