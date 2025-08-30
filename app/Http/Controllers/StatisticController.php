<?php

namespace App\Http\Controllers;

use App\Helpers\StatisticHelper;
use App\Http\Requests\StatisticRequest;
use App\Models\Operation;
use App\Traits\ResultTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    use ResultTrait;

    public function getStatistic(StatisticRequest $request): JsonResponse
    {
        $statistic = StatisticHelper::getStatistics($request['parentId'], $request['operationType']);
        return $this->successResponse($statistic, 'Statistic retrieved successfully', 200);
    }
}
