<?php

namespace App\Http\Controllers;

use App\Helpers\StatisticHelper;
use App\Http\Requests\StatisticRequest;
use App\Models\Operation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function getStatistic(StatisticRequest $request): JsonResponse
    {
        $statistic = StatisticHelper::getStatistics($request['parentId'], $request['operationType']);
        return response()->json($statistic);
    }
}
