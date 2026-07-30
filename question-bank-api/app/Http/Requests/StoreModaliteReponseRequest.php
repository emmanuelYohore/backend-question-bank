<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreModaliteReponseRequest extends FormRequest
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
             'format_reponse_id'  => 'required|exists:AQUALI_format_reponses,id',
            'item_id' => 'required|exists:AQUALI_items,id',
             'intitule' => 'nullable|string|max:255',
                'ordre' => 'sometimes|integer|min:0',
            'min_value' => 'nullable|string',
             'max_value' => 'nullable|string',
        ];
    }
}
