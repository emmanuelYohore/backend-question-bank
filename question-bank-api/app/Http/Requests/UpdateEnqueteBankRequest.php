<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnqueteBankRequest extends FormRequest
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
            'enquete_id' => 'sometimes|exists:enquetes,id',
            'bank_item_id' => 'sometimes|exists:bank_items,id',
            'ordre' => 'sometimes|integer|min:0',
            'mode' => 'sometimes|string|in:systematique,aleatoire,adaptatif',
            'nombre_items_aleatoires' => 'sometimes|nullable|integer|min:1',

            
        ];
    }
}
