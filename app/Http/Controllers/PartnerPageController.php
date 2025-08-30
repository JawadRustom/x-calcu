<?php

namespace App\Http\Controllers;

use App\Helpers\StatisticHelper;
use App\Http\Requests\PartnerDetailsRequest;
use App\Http\Requests\PartnerPage\PartnerRequest;
use App\Http\Requests\PartnerPage\StorePartnerRequest;
use App\Http\Requests\PartnerPage\UpdatePartnerRequest;
use App\Http\Resources\HomepageResource\OperationResource;
use App\Http\Resources\PartnerPage\PartnerResource;
use App\Models\Operation;
use App\Models\Partner;
use App\Traits\ResultTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PartnerPageController extends Controller
{
    use ResultTrait;

    public function getPartners(PartnerRequest $request, int $perPage = 10): \Illuminate\Http\JsonResponse
    {
        // Get distinct partner IDs first
        $partnerIds = Operation::select('partner_id')
            ->whereHas('partner.user', function ($q) {
                $q->where('id', auth()->id());
            })
            ->where('operation_type', $request['operationType']);

        // Get unique partner IDs
        $partnerIds = $partnerIds->distinct()->pluck('partner_id');

        // Get the partners with pagination
        $partners = \App\Models\Partner::whereIn('id', $partnerIds)
            ->paginate($perPage);
        return $this->successResponse(PartnerResource::collection($partners), 'Get partners successfully', 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePartnerRequest $request): \Illuminate\Http\JsonResponse
    {
        $partner = Partner::create([
            'name' => $request['name'],
            'phone' => $request['phone'],
            'email' => $request['email'],
            'user_id' => auth()->id(),
        ]);
        return $this->successResponse(new PartnerResource($partner), 'Partner created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $partnerId): \Illuminate\Http\JsonResponse
    {
        $partner = Partner::find($partnerId);
        return $this->successResponse(new PartnerResource($partner), 'Show partner successfully', 200);
    }

    /**
     * Display the specified resource.
     */
    public function partnerDetails(string $partnerId, PartnerDetailsRequest $request): \Illuminate\Http\JsonResponse
    {
        $statistic = StatisticHelper::getStatistics($partnerId, $request->operationType);
        $partner = Partner::find($partnerId);
        $data = [
            'partner' => new PartnerResource($partner),
            'statistic' => $statistic,
            'operations' => OperationResource::collection($partner->operations)
        ];
        return $this->successResponse($data, 'Get partner details successfully', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePartnerRequest $request, string $partnerId): \Illuminate\Http\JsonResponse
    {
        $partner = Partner::find($partnerId);
        $partner->update([
            'name' => $request['name'],
            'phone' => $request['phone'],
            'email' => $request['email'],
        ]);
        $partner->refresh();
        return $this->successResponse(new PartnerResource($partner), 'Partner updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $partnerId): \Illuminate\Http\Response
    {
        $partner = Partner::find($partnerId);
        $partner->delete();
        return response()->noContent();
    }

}
