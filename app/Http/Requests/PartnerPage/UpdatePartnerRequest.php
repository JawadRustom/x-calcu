<?php

namespace App\Http\Requests\PartnerPage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePartnerRequest extends FormRequest
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

        $partnerId = $this->route('partner');
        $partner = \App\Models\Partner::find($partnerId);
        return [
            'name' => ['string'],
            'email' => ['email', Rule::unique('partners', 'email')->ignore($partner)],
        ];
    }
}
