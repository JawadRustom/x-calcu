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
        $operation = \App\Models\Operation::whereHas('partner', static fn($q) => $q->whereHas('user', static fn($q) => $q->where('user_id', 1)
        )
        )->where('partner_id', '=', $this->id)->first();
        if($operation){
            if($request['operationType'] === 'Input'){
                $total_percentage_value = $operation->total_percentage_value['input_operation']['لشريك واحد'];
            }else{
                $total_percentage_value = $operation->total_percentage_value['output_operation']['لشريك واحد'];
            }
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'operations_count' => $this->when(isset($this->operations_count), $this->operations_count),
            'total_percentage_value' => $total_percentage_value ?? null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
