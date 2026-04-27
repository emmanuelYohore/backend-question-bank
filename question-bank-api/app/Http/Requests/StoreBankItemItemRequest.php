<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBankItemItemRequest extends FormRequest
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
            'bank_item_id' => 'required|exists:bank_items,id',
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:items,id',
            'ordre' => 'sometimes|integer|min:0',
            
        ];
    }
}
