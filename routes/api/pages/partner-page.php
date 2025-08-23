<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/partner-page/partners', [\App\Http\Controllers\PartnerPageController::class, 'getPartners']);
    Route::apiResource('/partner-page/partners', \App\Http\Controllers\PartnerPageController::class)->except(['index','update']);
    Route::post('/partner-page/partners/{partner}', [\App\Http\Controllers\PartnerPageController::class, 'update']);
});
