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
        try {
            $statistic = StatisticHelper::getStatistics($request['parentId'], $request['operationType']);
        }catch (\Exception $exception){
            return $this->errorResponse($exception->getMessage(), null, 400);
        }
        return $this->successResponse($statistic, 'Statistic retrieved successfully', 200);
    }
}
