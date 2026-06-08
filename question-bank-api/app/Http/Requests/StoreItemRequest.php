<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
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
            'format_reponse_id' => 'required|exists:AQUALI_format_reponses,id',
            'question'          => 'required|string|max:300',
            'min_case_to_check'   => 'nullable|integer|min:1',
            'max_case_to_check'   => 'nullable|integer|min:1',
            'obligatoire'       => 'sometimes|boolean',
            'nom_court' => 'required|string|max:30',             
            'archived'         => 'sometimes|boolean',
        ];
    }
}
