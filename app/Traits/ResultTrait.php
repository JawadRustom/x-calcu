<?php

namespace App\Traits;

trait ResultTrait
{
    public function successResponse($data = null, string $message = '', $code = 200)
    {
        if (
            $data instanceof \Illuminate\Http\Resources\Json\ResourceCollection &&
            $data->resource instanceof \Illuminate\Pagination\LengthAwarePaginator
        ) {
            $responseArray = $data->toResponse(request())->getData(true);

            return response()->json([
                'status' => true,
                'msg' => $message ?? __('messages.success'),
                'data' => $responseArray['data'],
                'links' => $responseArray['links'],
                // 'meta' => $responseArray['meta'],
            ], $code);
        }

        return response()->json([
            'status' => true,
            'msg' => $message ?? __('messages.success'),
            'data' => $data,
        ], $code);
    }


    public function errorResponse(string $message = '', $data = null, $code = 500)
    {
        return response()->json([
            'status' => false,
            'msg' => $message ?? __('messages.error'),
            'data' => $data,
        ], $code);
    }

    public function notFoundResponse(string $message = '')
    {
        return response()->json([
            'status' => false,
            'msg' => $message ?? __('messages.not_found'),
            'data' => null,
        ], 404);
    }
}
