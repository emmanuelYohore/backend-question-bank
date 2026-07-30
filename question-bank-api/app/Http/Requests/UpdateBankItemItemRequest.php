<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBankItemItemRequest extends FormRequest
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
            'bank_item_id' => 'sometimes|exists:AQUALI_bank_items,id',
            'item_id' => 'sometimes|exists:AQUALI_items,id',
            'ordre' => 'sometimes|integer|min:0',

        ];
    }
}
