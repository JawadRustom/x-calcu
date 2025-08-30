<?php

namespace App\Http\Controllers;

use App\Http\Resources\HomepageResource\OperationResource;
use App\Models\Operation;
use App\Traits\ResultTrait;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    use ResultTrait;
    /**
     * Search operations across multiple columns
     *
     * @param Request $request
     * @param int $perPage
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchOperations(Request $request, $perPage = 10): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'search' => 'required|string|min:1',
        ]);

        $searchTerm = $request->search;

        $query = Operation::with(['partner', 'paidBills', 'receivedAmounts'])
            ->whereHas('partner', function ($q) {
                $q->whereHas('user', function ($q) {
                    $q->where('id', auth()->id());
                });
            });

        // First, get all operations that match the basic search criteria
        $baseQuery = clone $query;
        $baseQuery->where(function ($q) use ($searchTerm) {
            $q->where('customer_name', 'like', "%{$searchTerm}%")
                ->orWhere('invoice_number', 'like', "%{$searchTerm}%")
                ->orWhereRaw('CAST(invoice_value AS CHAR) LIKE ?', ["%{$searchTerm}%"])
                ->orWhereRaw('CAST(percentage_of_bill AS CHAR) LIKE ?', ["%{$searchTerm}%"])
                ->orWhere('comments', 'like', "%{$searchTerm}%")
                ->orWhereHas('partner', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%");
                });
        });

        // Get the IDs from the base query
        $baseIds = $baseQuery->pluck('id');

        // Now get all operations and filter by the calculated attributes
        $operations = $query->get()->filter(function($operation) use ($searchTerm, $baseIds) {
            // Check if it's in the base results
            if ($baseIds->contains($operation->id)) {
                return true;
            }

            // Check calculated attributes
            $searchableAttributes = [
                'percentage_value' => $operation->percentage_value,
                'remaining_of_bill_value' => $operation->remaining_of_bill_value,
                'amount_due_value' => $operation->amount_due_value,
                'remaining_amount_value' => $operation->remaining_amount_value,
                'paid_bills_total' => $operation->paid_bills_total,
                'received_amounts_total' => $operation->received_amounts_total,
            ];

            // Check if any attribute contains the search term
            foreach ($searchableAttributes as $key => $value) {
                // Skip if the value is an array or object
                if (is_array($value) || is_object($value)) {
                    continue;
                }

                // Convert to string and check for match
                $stringValue = (string)$value;
                if ($stringValue !== '' && stripos($stringValue, $searchTerm) !== false) {
                    return true;
                }
            }

            return false;
        });

        // Convert to paginator
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage('page');
        $perPage = $perPage;
        $results = $operations->slice(($page - 1) * $perPage, $perPage)->values();
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator($results, $operations->count(), $perPage, $page, [
            'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
        ]);
        return $this->successResponse(OperationResource::collection($paginated), "Search operations successfully", 200);
    }
}
