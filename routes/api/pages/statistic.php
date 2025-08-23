<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/statistic', [\App\Http\Controllers\StatisticController::class, 'getStatistic']);
});
