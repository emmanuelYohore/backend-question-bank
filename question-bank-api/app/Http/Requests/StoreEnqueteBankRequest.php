<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnqueteBankRequest extends FormRequest
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
            'enquete_id' => 'required|exists:enquetes,id',
            'bank_item_id'      => 'required|exists:bank_items,id',
            //'order' => 'sometimes|integer|min:0',
            'mode' => 'required|string|in:systematique,random',
            
        ];
    }
}
