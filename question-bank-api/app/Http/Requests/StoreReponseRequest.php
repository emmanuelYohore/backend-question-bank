<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReponseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'repondant_id' => 'required|exists:repondants,id',
            'item_id' => 'required|exists:items,id',
            'modalite_reponse_id' => 'nullable|exists:modalite_reponses,id',
            'valeur' => 'nullable|string',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (empty($this->modalite_reponse_id) && empty($this->valeur)) {
                $validator->errors()->add('reponse', 'Vous devez fournir soit une modalité de réponse, soit une valeur.');
            }
        });
    }
}