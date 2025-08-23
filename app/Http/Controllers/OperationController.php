<?php

namespace App\Http\Controllers;

use App\Http\Requests\Operation\IndexOperationRequest;
use App\Http\Requests\Operation\StoreOperationRequest;
use App\Http\Resources\HomepageResource\OperationResource;
use App\Models\Operation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexOperationRequest $request, $perPage = 10): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $searchTerm = $request->search;
        
        // Base query with user access control
        $query = Operation::where('operation_type', $request->operationType)
            ->whereHas('partner', function($q) {
                $q->where('user_id', auth()->id());
            });

        // Apply date range filter
        if ($request->has(['start_date', 'end_date'])) {
            $query->whereBetween('invoice_date', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Filter by partner ID
        if ($request->partner_id) {
            $query->where('partner_id', $request->partner_id);
        }

        // Apply search term if provided
        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                // Search in operation fields
                $q->where('customer_name', 'like', "%{$searchTerm}%")
                  ->orWhere('invoice_number', 'like', "%{$searchTerm}%")
                  ->orWhere('invoice_value', 'like', "%{$searchTerm}%")
                  ->orWhere('percentage_of_bill', 'like', "%{$searchTerm}%")
                  ->orWhere('comments', 'like', "%{$searchTerm}%");
                
                // Search in partner name
                $q->orWhereHas('partner', function($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%");
                });
                
                // Search in paid bills
                $q->orWhereHas('paidBills', function($q) use ($searchTerm) {
                    $q->where('invoice_value', 'like', "%{$searchTerm}%");
                });
                
                // Search in received amounts
                $q->orWhereHas('receivedAmounts', function($q) use ($searchTerm) {
                    $q->where('invoice_value', 'like', "%{$searchTerm}%");
                });
                
                // Try to parse as date and search in date fields
                try {
                    $date = \Carbon\Carbon::parse($searchTerm);
                    if ($date) {
                        $dateString = $date->format('Y-m-d');
                        $q->orWhereDate('invoice_date', $dateString)
                          ->orWhereDate('alert_date', $dateString)
                          ->orWhereHas('paidBills', function($q) use ($dateString) {
                              $q->whereDate('invoice_date', $dateString);
                          })
                          ->orWhereHas('receivedAmounts', function($q) use ($dateString) {
                              $q->whereDate('invoice_date', $dateString);
                          });
                    }
                } catch (\Exception $e) {
                    // Ignore date parsing errors
                }
            });
        }

        // Eager load relationships and paginate
        $operations = $query->with(['partner', 'paidBills', 'receivedAmounts'])
            ->orderBy('invoice_date', $request->orderBy)
            ->paginate($perPage);

        return OperationResource::collection($operations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOperationRequest $request)
    {
        try {
            // Start a database transaction
            DB::beginTransaction();

            // Create the operation
            $operation = Operation::create([
                'partner_id' => $request->partner_id,
                'customer_name' => $request->customer_name,
                'operation_type' => $request->operationType,
                'invoice_number' => $request->invoice_number,
                'invoice_value' => $request->invoice_value,
                'percentage_of_bill' => $request->percentage_of_bill,
                'invoice_date' => $request->invoice_date,
                'alert_date' => $request->alert_date,
                'comments' => $request->comments,
            ]);


            // Create paid bills if any
            if ($request->filled('paid_bills')) {
                foreach ($request->paid_bills as $bill) {
                    $paidBill = $operation->paidBills()->create([
                        'invoice_value' => $bill['invoice_value'],
                        'invoice_date' => $bill['invoice_date']
                    ]);
                }
            }

            // Create received amounts if any
            if ($request->filled('received_amounts')) {
                foreach ($request->received_amounts as $amount) {
                    $receivedAmount = $operation->receivedAmounts()->create([
                        'invoice_value' => $amount['invoice_value'],
                        'invoice_date' => $amount['invoice_date']
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();

            // Return the created operation with related data
            return response()->json([
                'message' => 'Operation created successfully',
                'data' => new OperationResource($operation->load(['paidBills', 'receivedAmounts']))
            ], 201);

        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create operation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $operationId)
    {
        $operation = Operation::with(['paidBills', 'receivedAmounts'])->find($operationId);
        return new OperationResource($operation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreOperationRequest $request, string $operationId)
    {
        $operation = Operation::with(['paidBills', 'receivedAmounts'])->find($operationId);
        try {
            // Start a database transaction
            DB::beginTransaction();

            // Update the operation
            $operation->update([
                'partner_id' => $request->partner_id,
                'customer_name' => $request->customer_name,
                'operation_type' => $request->operationType,
                'invoice_number' => $request->invoice_number,
                'invoice_value' => $request->invoice_value,
                'percentage_of_bill' => $request->percentage_of_bill,
                'invoice_date' => $request->invoice_date,
                'alert_date' => $request->alert_date,
                'comments' => $request->comments,
            ]);

            // Sync paid bills
            if ($request->filled('paid_bills')) {
                // Delete existing paid bills
                $operation->paidBills()->delete();

                // Create new paid bills
                foreach ($request->paid_bills as $bill) {
                    $operation->paidBills()->create([
                        'invoice_value' => $bill['invoice_value'],
                        'invoice_date' => $bill['invoice_date']
                    ]);
                }
            } else {
                // If no paid bills in request, remove existing ones
                $operation->paidBills()->delete();
            }

            // Sync received amounts
            if ($request->filled('received_amounts')) {
                // Delete existing received amounts
                $operation->receivedAmounts()->delete();

                // Create new received amounts
                foreach ($request->received_amounts as $amount) {
                    $operation->receivedAmounts()->create([
                        'invoice_value' => $amount['invoice_value'],
                        'invoice_date' => $amount['invoice_date']
                    ]);
                }
            } else {
                // If no received amounts in request, remove existing ones
                $operation->receivedAmounts()->delete();
            }

            // Commit the transaction
            DB::commit();

            // Return the updated operation with related data
            return response()->json([
                'message' => 'Operation updated successfully',
                'data' => new OperationResource($operation->load(['paidBills', 'receivedAmounts']))
            ]);

        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to update operation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $operationId)
    {
        $operation = Operation::with(['paidBills', 'receivedAmounts'])->find($operationId);
        $operation->delete();
        return response()->noContent();
    }
}
