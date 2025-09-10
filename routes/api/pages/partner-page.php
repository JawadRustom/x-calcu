<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('partners', [\App\Http\Controllers\PartnerPageController::class, 'getPartners']);
    Route::get('partners-select', [\App\Http\Controllers\PartnerPageController::class, 'getSelectPartners']);
    Route::apiResource('partners', \App\Http\Controllers\PartnerPageController::class)->except(['index','update']);
    Route::post('partners/{partner}', [\App\Http\Controllers\PartnerPageController::class, 'update']);
    Route::get('partners/{partner}/details', [\App\Http\Controllers\PartnerPageController::class, 'partnerDetails']);
    Route::get('partners/{partner}/operations', [\App\Http\Controllers\PartnerPageController::class, 'partnerOperations']);
});
