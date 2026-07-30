<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'format_reponse_id' => 'sometimes|exists:AQUALI_format_reponses,id', // ✅ Corrigé
            'question'          => 'sometimes|string|max:300',
            'min_case_to_check' => 'sometimes|integer|min:1',
            'max_case_to_check' => 'sometimes|integer|min:1',
            'obligatoire'       => 'sometimes|boolean',
            'nom_court'         => 'sometimes|string|max:30',
            'archived'          => 'sometimes|boolean',
        ];
    }
}