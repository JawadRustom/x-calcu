<?php

namespace App\Http\Requests\Operation;

use Illuminate\Foundation\Http\FormRequest;

class IndexOperationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'operationType' => 'required',
            'orderBy' => 'required',
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
            'partner_id' => 'nullable|numeric|exists:partners,id',
            'search' => 'nullable|string|max:255',
        ];
    }
}
