<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/operations', \App\Http\Controllers\OperationController::class)->except(['update']);
    Route::post('operations/{operationId}', [\App\Http\Controllers\OperationController::class, 'update']);
});
