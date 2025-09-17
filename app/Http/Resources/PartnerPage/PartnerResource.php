<?php

namespace App\Http\Resources\PartnerPage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $operation = \App\Models\Operation::whereHas('partner', static fn($q) => 
            $q->whereHas('user', static fn($q) => 
                $q->where('user_id', auth()->id())
            )
        )->where('partner_id', $this->id)->first();

        $totalPercentageValue = null;
        if ($operation && $request->has('operationType')) {
            $operationType = $request->input('operationType');
            $key = $operationType === 'input' ? 'input_operation' : 'output_operation';
            $totalPercentageValue = $operation->total_percentage_value[$key]['لشريك واحد'] ?? null;
        }

        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'operations_count' => $this->when(isset($this->operations_count), $this->operations_count),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];

        if ($request->has('operationType')) {
            $data['total_percentage_value'] = $totalPercentageValue;
        }

        return $data;
    }
}
