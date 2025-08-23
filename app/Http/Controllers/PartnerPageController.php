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
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PartnerPageController extends Controller
{
    public function getPartners(PartnerRequest $request, int $perPage = 10): AnonymousResourceCollection
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
        return PartnerResource::collection($partners);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePartnerRequest $request): \Illuminate\Http\JsonResponse
    {
        Partner::create([
            'name' => $request['name'],
            'phone' => $request['phone'],
            'email' => $request['email'],
            'user_id' => auth()->id(),
        ]);
        return response()->json(['message' => 'Partner created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $partnerId): PartnerResource
    {
        $partner = Partner::find($partnerId);
        return new PartnerResource($partner);
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
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePartnerRequest $request, string $partnerId): PartnerResource
    {
        $partner = Partner::find($partnerId);
        $partner->update([
            'name' => $request['name'],
            'phone' => $request['phone'],
            'email' => $request['email'],
        ]);
        $partner->refresh();
        return new PartnerResource($partner);
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
