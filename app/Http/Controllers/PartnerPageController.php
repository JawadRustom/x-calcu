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
use Illuminate\Support\Facades\Auth;

class PartnerPageController extends Controller
{
    use ResultTrait;

    public function getPartners(PartnerRequest $request): \Illuminate\Http\JsonResponse
    {
        $perPage = $request->perPage ?? 10;
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

    public function getSelectPartners(): \Illuminate\Http\JsonResponse
    {
        $partnerIds = Partner::whereHas('user', function ($q) {
            $q->where('id', auth()->id());
        });
        $partnerIds = $partnerIds->distinct()->pluck('id');
        $partners = \App\Models\Partner::whereIn('id', $partnerIds)->get();
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
        try {
            self::validateParentId($partnerId);
            $partner = Partner::find($partnerId);
            return $this->successResponse(new PartnerResource($partner), 'Show partner successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse('Failed to show partner' . $exception->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function partnerDetails(string $partnerId, PartnerDetailsRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            self::validateParentId($partnerId);
            $statistic = StatisticHelper::getStatistics($partnerId, $request->operationType);
            $partner = Partner::find($partnerId);
            $data = [
                'partner' => new PartnerResource($partner),
                'statistic' => $statistic,
                'operations' => OperationResource::collection($partner->operations)
            ];
            return $this->successResponse($data, 'Get partner details successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse('Failed to get partner operations' . $exception->getMessage(), null, 500);
        }
    }

    public function partnerOperations(string $partnerId, PartnerDetailsRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            self::validateParentId($partnerId);
        } catch (\Exception $exception) {
            return $this->errorResponse('Failed to get partner operations' . $exception->getMessage(), null, 500);
        }
        $perPage = $request->perPage ?? 10;
        $searchTerm = $request->search;
        $orderBy = $request->orderBy;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $operationType = $request->operationType;

        $partner = Partner::find($partnerId);

        $query = $partner->operations()->where('operation_type', $operationType)
            ->whereHas('partner', function ($q) {
                $q->where('user_id', auth()->id());
            });

        // Apply date range filter
        if ($request->has(['start_date', 'end_date'])) {
            $query->whereBetween('invoice_date', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ]);
        }

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                // Search in operation fields
                $q->where('customer_name', 'like', "%{$searchTerm}%")
                    ->orWhere('invoice_number', 'like', "%{$searchTerm}%")
                    ->orWhere('invoice_value', 'like', "%{$searchTerm}%")
                    ->orWhere('percentage_of_bill', 'like', "%{$searchTerm}%")
                    ->orWhere('comments', 'like', "%{$searchTerm}%");

                // Search in partner name
                $q->orWhereHas('partner', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%");
                });

                // Search in paid bills
                $q->orWhereHas('paidBills', function ($q) use ($searchTerm) {
                    $q->where('invoice_value', 'like', "%{$searchTerm}%");
                });

                // Search in received amounts
                $q->orWhereHas('receivedAmounts', function ($q) use ($searchTerm) {
                    $q->where('invoice_value', 'like', "%{$searchTerm}%");
                });

                // Try to parse as date and search in date fields
                try {
                    $date = \Carbon\Carbon::parse($searchTerm);
                    if ($date) {
                        $dateString = $date->format('Y-m-d');
                        $q->orWhereDate('invoice_date', $dateString)
                            ->orWhereDate('alert_date', $dateString)
                            ->orWhereHas('paidBills', function ($q) use ($dateString) {
                                $q->whereDate('invoice_date', $dateString);
                            })
                            ->orWhereHas('receivedAmounts', function ($q) use ($dateString) {
                                $q->whereDate('invoice_date', $dateString);
                            });
                    }
                } catch (\Exception $e) {
                    // Ignore date parsing errors
                }
            });
        }

        $operations = $query->with(['partner', 'paidBills', 'receivedAmounts'])
            ->orderBy('invoice_date', $orderBy)
            ->paginate($perPage);

        $data = OperationResource::collection($operations);
        return $this->successResponse($data, 'Get partner details successfully', 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePartnerRequest $request, string $partnerId): \Illuminate\Http\JsonResponse
    {
        try {
            self::validateParentId($partnerId);
            $partner = Partner::find($partnerId);
            $partner->update([
                'name' => $request['name'],
                'phone' => $request['phone'],
                'email' => $request['email'],
            ]);
            $partner->refresh();
            return $this->successResponse(new PartnerResource($partner), 'Partner updated successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse('Failed to update partner' . $exception->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $partnerId)
    {
        try {
            $partner = Partner::find($partnerId);
            self::validateParentId($partnerId);
            $partner->delete();
            return response()->noContent();
        } catch (\Exception $exception) {
            return $this->errorResponse('Failed to delete partner' . $exception->getMessage(), null, 500);
        }
    }

    private static function validateParentId(?int $parentId): void
    {
        // Skip validation if parentId is null (for all partners case)
        if ($parentId === null) {
            return;
        }

        // Get all partner IDs for the authenticated user
        $partnerIds = Auth::user()->partners()->pluck('id')->toArray();

        // If user has no partners, throw an exception
        if (empty($partnerIds)) {
            throw new \Exception('You don\'t have any partners');
        }

        // Check if the provided parentId belongs to user's partners
        if (!in_array($parentId, $partnerIds)) {
            throw new \Exception('You don\'t have access to this partner');
        }
    }

}
