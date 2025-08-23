<?php

namespace App\Http\Requests\Operation;

use Illuminate\Foundation\Http\FormRequest;

class StoreOperationRequest extends FormRequest
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
            'partner_id' => 'required|exists:partners,id',
            'customer_name' => 'required|string|max:255',
            'operationType' => 'required|in:Input,Output',
            'invoice_number' => 'required|string|max:255',
            'invoice_value' => 'required|numeric|min:0',
            'percentage_of_bill' => 'required|numeric|min:0|max:100',
            'invoice_date' => 'required|date',
            'alert_date' => 'nullable|date|after_or_equal:invoice_date',
            'comments' => 'nullable|string',
            'paid_bills' => 'nullable|array',
            'paid_bills.*.invoice_value' => 'required_with:paid_bills|numeric|min:0',
            'paid_bills.*.invoice_date' => 'required_with:paid_bills|date',
            'received_amounts' => 'nullable|array',
            'received_amounts.*.invoice_value' => 'required_with:received_amounts|numeric|min:0',
            'received_amounts.*.invoice_date' => 'required_with:received_amounts|date',
        ];
    }
}
