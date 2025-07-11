<?php

namespace App\Http\Resources\HomepageResource;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OperationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'operation_type' => $this->operation_type->value,
            'comments' => $this->comments,
            'amount_due_value' => $this->amount_due_value,
            'partner' => new PartnerResource($this->partner),
            'invoice_date' => Carbon::parse($this->invoice_date)->format('Y-m-d H:i:s'),
        ];
    }
}
